<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use RuntimeException;
use Phar;
use PharData;

class BackupSupport
{
    public static function create(): string
    {
        $backupDir = self::backupDirectory();
        File::ensureDirectoryExists($backupDir);

        $timestamp = now()->format('Y-m-d_H-i-s');
        $baseName = 'atsa-backup-'.$timestamp;
        $tempDir = $backupDir.DIRECTORY_SEPARATOR.'tmp-'.$baseName;
        $tarPath = $backupDir.DIRECTORY_SEPARATOR.$baseName.'.tar';
        $gzPath = $tarPath.'.gz';

        self::cleanupIfExists($tempDir, $tarPath, $gzPath);
        File::ensureDirectoryExists($tempDir);

        try {
            self::writeMetadata($tempDir);
            self::exportDatabase($tempDir);
            self::exportStorage($tempDir);

            $archive = new PharData($tarPath);
            $archive->buildFromDirectory($tempDir);

            try {
                $archive->compress(Phar::GZ);
                unset($archive);

                if (File::exists($tarPath)) {
                    File::delete($tarPath);
                }

                return basename($gzPath);
            } catch (\Throwable) {
                unset($archive);

                return basename($tarPath);
            }
        } finally {
            if (File::isDirectory($tempDir)) {
                File::deleteDirectory($tempDir);
            }
        }
    }

    public static function all(): array
    {
        $backupDir = self::backupDirectory();

        if (! File::isDirectory($backupDir)) {
            return [];
        }

        $files = collect(File::files($backupDir))
            ->filter(fn ($file) => in_array($file->getExtension(), ['tar', 'gz'], true))
            ->sortByDesc(fn ($file) => $file->getMTime())
            ->values();

        return $files->map(function ($file): array {
            return [
                'name' => $file->getFilename(),
                'size' => self::humanSize($file->getSize()),
                'created_at' => date('d/m/Y H:i', $file->getMTime()),
            ];
        })->all();
    }

    public static function pathFor(string $filename): string
    {
        return self::backupDirectory().DIRECTORY_SEPARATOR.$filename;
    }

    public static function backupDirectory(): string
    {
        return storage_path('app/private/backups');
    }

    private static function writeMetadata(string $tempDir): void
    {
        $metadata = [
            'application' => config('app.name'),
            'environment' => config('app.env'),
            'generated_at' => now()->toIso8601String(),
            'database_connection' => config('database.default'),
            'app_url' => config('app.url'),
        ];

        File::put(
            $tempDir.DIRECTORY_SEPARATOR.'metadata.json',
            json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );
    }

    private static function exportDatabase(string $tempDir): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $databasePath = config('database.connections.sqlite.database');

            if (is_string($databasePath) && $databasePath !== ':memory:' && File::exists($databasePath)) {
                File::copy($databasePath, $tempDir.DIRECTORY_SEPARATOR.'database.sqlite');
                return;
            }
        }

        $dumpPath = $tempDir.DIRECTORY_SEPARATOR.'database.sql';
        $sql = $driver === 'mysql' || $driver === 'mariadb'
            ? self::dumpMysqlLikeDatabase()
            : self::dumpGenericDatabase();

        File::put($dumpPath, $sql);
    }

    private static function dumpMysqlLikeDatabase(): string
    {
        $output = [];
        $output[] = '-- Backup generado por ATSA Tucumán';
        $output[] = '-- Fecha: '.now()->format('Y-m-d H:i:s');
        $output[] = '';
        $output[] = 'SET FOREIGN_KEY_CHECKS=0;';
        $output[] = '';

        foreach (self::tableNames() as $table) {
            $quotedTable = self::quoteIdentifier($table);
            $createRow = (array) DB::selectOne('SHOW CREATE TABLE '.$quotedTable);
            $createSql = end($createRow);

            $output[] = '-- Estructura de '.$table;
            $output[] = 'DROP TABLE IF EXISTS '.$quotedTable.';';
            $output[] = rtrim((string) $createSql, ';').';';
            $output[] = '';

            $inserts = self::buildInsertStatements($table);
            if ($inserts !== []) {
                $output[] = '-- Datos de '.$table;
                array_push($output, ...$inserts);
                $output[] = '';
            }
        }

        $output[] = 'SET FOREIGN_KEY_CHECKS=1;';
        $output[] = '';

        return implode(PHP_EOL, $output);
    }

    private static function dumpGenericDatabase(): string
    {
        $output = [];
        $output[] = '-- Backup generado por ATSA Tucumán';
        $output[] = '-- Fecha: '.now()->format('Y-m-d H:i:s');
        $output[] = '-- Formato genérico con INSERTs';
        $output[] = '';

        foreach (self::tableNames() as $table) {
            $inserts = self::buildInsertStatements($table);

            $output[] = '-- Tabla '.$table;
            if ($inserts === []) {
                $output[] = '-- Sin registros';
            } else {
                array_push($output, ...$inserts);
            }
            $output[] = '';
        }

        return implode(PHP_EOL, $output);
    }

    private static function buildInsertStatements(string $table): array
    {
        $columns = Schema::getColumnListing($table);
        if ($columns === []) {
            return [];
        }

        $quotedTable = self::quoteIdentifier($table);
        $quotedColumns = implode(', ', array_map([self::class, 'quoteIdentifier'], $columns));
        $rows = DB::table($table)->get($columns);

        if ($rows->isEmpty()) {
            return [];
        }

        $statements = [];
        $buffer = [];

        foreach ($rows as $row) {
            $values = [];

            foreach ($columns as $column) {
                $values[] = self::quoteValue($row->{$column});
            }

            $buffer[] = '('.implode(', ', $values).')';

            if (count($buffer) >= 100) {
                $statements[] = 'INSERT INTO '.$quotedTable.' ('.$quotedColumns.') VALUES '.implode(', ', $buffer).';';
                $buffer = [];
            }
        }

        if ($buffer !== []) {
            $statements[] = 'INSERT INTO '.$quotedTable.' ('.$quotedColumns.') VALUES '.implode(', ', $buffer).';';
        }

        return $statements;
    }

    private static function exportStorage(string $tempDir): void
    {
        $storageDir = $tempDir.DIRECTORY_SEPARATOR.'storage';
        File::ensureDirectoryExists($storageDir);

        $publicSource = storage_path('app/public');
        if (File::isDirectory($publicSource)) {
            File::copyDirectory($publicSource, $storageDir.DIRECTORY_SEPARATOR.'public');
        }

        $privateSource = storage_path('app/private');
        if (File::isDirectory($privateSource)) {
            self::copyPrivateStorage($privateSource, $storageDir.DIRECTORY_SEPARATOR.'private');
        }
    }

    private static function copyPrivateStorage(string $source, string $destination): void
    {
        File::ensureDirectoryExists($destination);

        foreach (File::allFiles($source) as $file) {
            $relativePath = $file->getRelativePathname();

            if (str_starts_with(str_replace('\\', '/', $relativePath), 'backups/')) {
                continue;
            }

            $targetPath = $destination.DIRECTORY_SEPARATOR.$relativePath;
            File::ensureDirectoryExists(dirname($targetPath));
            File::copy($file->getPathname(), $targetPath);
        }
    }

    private static function tableNames(): array
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            $rows = DB::select('SHOW TABLES');
            $key = $rows !== [] ? array_key_first((array) $rows[0]) : null;

            return $key
                ? array_map(fn ($row) => ((array) $row)[$key], $rows)
                : [];
        }

        return Schema::getTableListing();
    }

    private static function quoteIdentifier(string $identifier): string
    {
        $driver = DB::getDriverName();
        $quote = $driver === 'pgsql' ? '"' : '`';
        $escaped = str_replace($quote, $quote.$quote, $identifier);

        return $quote.$escaped.$quote;
    }

    private static function quoteValue(mixed $value): string
    {
        if ($value === null) {
            return 'NULL';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return DB::getPdo()->quote((string) $value);
    }

    private static function humanSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $bytes;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return number_format($size, $unit === 0 ? 0 : 2, ',', '.').' '.$units[$unit];
    }

    private static function cleanupIfExists(string ...$paths): void
    {
        foreach ($paths as $path) {
            if (File::isDirectory($path)) {
                File::deleteDirectory($path);
                continue;
            }

            if (File::exists($path)) {
                File::delete($path);
            }
        }
    }
}

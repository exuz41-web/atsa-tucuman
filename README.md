# ATSA Tucumán

Sistema web Laravel para ATSA Tucumán y CENT N°74.

## Módulos principales

- Sitio público institucional.
- Portal de afiliados.
- Panel administrativo ATSA con Filament.
- Portal académico CENT para alumnos, docentes y directivos.
- Gestión de afiliación, carnets, pedidos, consultas, beneficios y contenido.
- Backups completos de base de datos y storage.

## Instalación local rápida

```powershell
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan storage:link
php artisan serve
```

En Laragon, el proyecto se usa desde:

```text
C:\laragon\www\atsa-tucuman
```

## Pruebas

```powershell
php artisan test
```

Si `php` no está en el PATH de Windows:

```powershell
& 'C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\php.exe' artisan test
```

## Backups

Generar backup manual:

```powershell
php artisan backups:run
```

Los archivos quedan en:

```text
storage/app/private/backups
```

El scheduler ejecuta backups diarios si `BACKUP_ENABLED=true`.

## Documentación operativa

Ver [docs/OPERACION-Y-RECUPERACION.md](docs/OPERACION-Y-RECUPERACION.md) para despliegue, restauración, checklist de producción y recuperación ante pérdida de archivos.

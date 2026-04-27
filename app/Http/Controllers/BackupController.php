<?php

namespace App\Http\Controllers;

use App\Support\BackupSupport;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BackupController extends Controller
{
    public function download(string $filename): BinaryFileResponse
    {
        abort_unless($filename === basename($filename), 404);

        $path = BackupSupport::pathFor($filename);
        abort_unless(File::isFile($path), 404);

        return response()->download($path);
    }
}

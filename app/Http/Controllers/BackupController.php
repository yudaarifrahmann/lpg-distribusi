<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupController extends Controller
{
    public function index()
    {
        $backups = Storage::disk('local')->files('backups');
        return view('settings.backup', compact('backups'));
    }

    public function create()
    {
        $filename = "backup-" . now()->format('Y-m-d-His') . ".sql";
        $path = storage_path('app/backups/' . $filename);

        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.host'),
            config('database.connections.mysql.database'),
            $path
        );

        // Note: Password in command line is insecure on linux but often works in local xampp
        // In production, use a config file or specialized package
        exec($command);

        return back()->with('success', 'Backup database berhasil dibuat: ' . $filename);
    }

    public function download($filename)
    {
        return response()->download(storage_path('app/backups/' . $filename));
    }

    public function destroy($filename)
    {
        Storage::disk('local')->delete('backups/' . $filename);
        return back()->with('success', 'File backup berhasil dihapus.');
    }
}

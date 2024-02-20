<?php

use App\Models\BackupHistoriesModel;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('backup:run-and-store-path', function () {
    Artisan::call('backup:run --only-db');

    $backupDir = storage_path('Backup/Laravel');

    $latestBackupPath = collect(File::files($backupDir))
        ->sortByDesc(function ($file) {
            return $file->getCTime();
        })
        ->first();

    $basePath = base_path();
    $fullPath = $latestBackupPath->getPathname();
    $relativePath = str_replace($basePath . DIRECTORY_SEPARATOR, '', $fullPath);
    $filename = basename($relativePath);

    BackupHistoriesModel::create(['name' => $filename]);
});

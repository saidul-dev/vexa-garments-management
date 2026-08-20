<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupBackups extends Command
{
    protected $signature = 'cleanup:backups';
    protected $description = 'Clean up old database backup files';

    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        $backupDirectory = 'backup';

        $retentionDays = 30;
        $thresholdDate = now()->subDays($retentionDays);
// for test purpose
//        $retentionMinutes = 2;
//        $thresholdDate = now()->subMinutes($retentionMinutes);

        // Get a list of backup files
        $backupFiles = Storage::disk('local')->files($backupDirectory);

        foreach ($backupFiles as $backupFile) {
            $fileDate = Storage::disk('local')->lastModified($backupFile);

            if ($fileDate <= $thresholdDate->timestamp) {
                // Delete old backup file
                Storage::disk('local')->delete($backupFile);
            }
        }
    }
}

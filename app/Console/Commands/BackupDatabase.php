<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';
    protected $description = 'Backup the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $databaseName = config('database.connections.mysql.database');
        $backupFilename = 'Backup-' . Carbon::now()->format('Y-m-d_His') . '.sql';

        $backupDirectory = 'backup';

        if (!Storage::disk('local')->exists($backupDirectory)) {
            Storage::disk('local')->makeDirectory($backupDirectory);
        }

        $command = "mysqldump --user=" . config('database.connections.mysql.username') .
            " --password=" . config('database.connections.mysql.password') .
            " " . $databaseName . " > " . storage_path("app/$backupDirectory/$backupFilename");

        exec($command);

//        $this->info("Database backup saved as: $backupFilename");
    }
}

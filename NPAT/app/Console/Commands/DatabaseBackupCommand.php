<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use BackupManager\Config\Config;
use BackupManager\Filesystems;
use BackupManager\Databases;
use BackupManager\Compressors;
use BackupManager\Manager;
use BackupManager\Procedures\BackupProcedure;
use App\Repositories\UtilityRepository;

class DatabaseBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'npat:db-backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup App DB';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $backupProcedure;

    public function __construct(BackupProcedure $backupProcedure, UtilityRepository $utilityRepo)
    {
        parent::__construct();

        $this->backupProcedure = $backupProcedure;
        $this->utilityRepo = $utilityRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = $this->utilityRepo->getCurrentDateWithFormat(config('custom.db_bkup_dateFormat'));
        $this->call('db:backup',[
            '--database' => config('custom.db_connection'),        
            '--destination' => 'local',
            '--destinationPath' => 'app-' . $date,
            '--compression' => config('custom.db_bkup_type')
            ]);
    }
}

<?php

namespace App\Console\Commands\Utilities;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;

class ResetDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset-demo {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[CUSTOM] Rebuild database and seed with demo data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (config('app.env') !== 'production' || $this->option('force')) {

            $this->info("\nResetting data to demo environment:");

            // Delete any test images
            if (config('filesystems.disks.content')) {
                $this->deleteDirectory(config('filesystems.disks.content.root'));
                mkdir(config('filesystems.disks.content.root'));
            }

            // Delete log files
            $this->deleteLogs();

            // Rebuild tables and seed static data
            $this->line("\n> Rebuilding tables and seeding static data...");
            Artisan::call('migrate:fresh --force --seed');

            // Seed demo data (if available)
            if (file_exists(base_path('database/seeds/DemoSeeder.php'))) {
                $this->line("> Seeding demo data...");
                Artisan::call('db:seed --force --class=DemoSeeder');
            }

            // Clear any cached data
            $this->line("> Clearing cached data...");
            Artisan::call('flush');

            $this->info("\nCOMPLETE\n");

        } else {

            $this->error("This will reset all data and should not be run in production mode");
            $this->error("To run it anyway, add --force to the command");
            $this->line("\n");
        }
    }

    protected function deleteLogs()
    {
        $files = glob(storage_path('logs/log*.*'));
        foreach($files as $file) {
            unlink($files);
        }
    }

    protected function deleteDirectory(string $dir = null)
    {
        if (! $dir) { return; }
        if (! file_exists($dir)) { return; }

        $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);

        foreach($files as $file) {
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        rmdir($dir);
    }
}

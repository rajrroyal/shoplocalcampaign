<?php

namespace App\Console\Commands\Utilities;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ResetData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[CUSTOM] Rebuild and reseed database';

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
        if (config('app.env') !== 'production') {

            $this->info("\nResetting data to base environment:");

            // Rebuild tables and seed static data
            $this->line("\n> Rebuilding tables and seeding static data...");
            Artisan::call('migrate:fresh --seed --force');

            // Clear cached data
            $this->line("> Clearing cached data...");
            Artisan::call('flush');

            $this->info("\nCOMPLETE\n");

        } else {

            $this->error("\nThis will reset all data and should not be run in production mode");
        }
    }
}

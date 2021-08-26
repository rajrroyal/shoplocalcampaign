<?php

namespace App\Console\Commands\Utilities;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Deploy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[CUSTOM] Rebuild all caches and optimize composer';

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
        $this->migrate();
        $this->composerOptimize();
        $this->cache();

        $this->info("\nDone\n");
    }

    // ---------------------------------------------------------------------

    protected function migrate()
    {
        $this->info("\nRunning database migrations:");
        Artisan::call("migrate --force");
    }

    protected function cache()
    {
        $this->info("\nGenerating caches:");

        $this->line("> Caching the framework bootstrap files...");
        Artisan::call("optimize");

        $this->line("> Creating a cache file for faster configuration loading...");
        Artisan::call("config:cache");

        $this->line("> Discovering and cahing the application's events and listeners...");
        Artisan::call("event:cache");

        $this->line("> Rebuilding the cached package manifest...");
        Artisan::call("package:discover");

        $this->line("> Creating a route cache file for faster route registration...");
        Artisan::call("route:cache");

        $this->line("> Compiling all of the application's Blade templates...");
        Artisan::call("view:cache");
    }

    protected function composerOptimize()
    {
        $this->info("\nInstalling composer dependencies and optimizing:");

        try {
            exec("composer install -o");
        } catch (\Exception $e) {
            try {
                exec("php composer.phar install -o");
            } catch (\Exception $e) {
                $this->error("ERROR: Unable to optimize Composer autoload files");
            }
        }
    }
}

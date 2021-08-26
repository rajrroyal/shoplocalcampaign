<?php
namespace App\Console\Commands\Utilities;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class Flush extends Command
{
    protected $signature = "flush";
    protected $description = "[CUSTOM] Clear all cached Laravel data";

    /**
     delete: vendor/composer/autoload_*.php and include_paths.php
     */
    public function handle()
    {
        $this->info("\nFlushinging cached data:\n");

        $this->line("> Removing the compiled class file...");
        Artisan::call("clear-compiled");

        $this->line("> Flushing the application cache...");
        Artisan::call("cache:clear");

        $this->line("> Removing the configuration cache file...");
        Artisan::call("config:clear");

        $this->line("> Removing the configuration cache file...");
        Artisan::call("config:clear");

        $this->line("> Clearing all cached events and listeners...");
        Artisan::call("event:clear");

        $this->line("> Removing cached bootstrap files...");
        Artisan::call("optimize:clear");

        $this->line("> Removing the route cache file...");
        Artisan::call("route:clear");

        $this->line("> Clearing all compiled view files...");
        Artisan::call("view:clear");

        $this->line("> Rebuilding the cached package manifest...");
        Artisan::call("package:discover");

        $this->info("\nDone\n\n");
    }
}

<?php

namespace App\Console\Commands\Utilities;

use App\Models\Store;
use App\Services\CacheService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ResetUpdateStatus extends Command
{
    const MAX_MINUTES = 30;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset-update-statuses {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[CUSTOM] NULL all update_started_at values';

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
        $this->info("\nReseting store update statuses...");

        $query = Store::whereNotNull('update_started_at');

        if (! $this->option('force')) {
            // Only reset updates that have been running more than max number of minutes
            $query->where('update_started_at', '<', Carbon::now()->subMinutes(self::MAX_MINUTES));
        }

        $stores = $query->get();

        $cacheService = new CacheService();

        foreach($stores as $store) {
            $this->line("> " . $store->name);
            $store->update(['update_started_at'=>null]);
            $cacheService->clearStoreCache($store->user_id);
        }

        $this->info("Complete\n");
    }
}

// console.stub

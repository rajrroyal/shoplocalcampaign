<?php

namespace App\Jobs;

use App\Events\BroadcastUpdateComplete;
use App\Models\Store;
use App\Services\CacheService;
use App\Services\Shopify\ShopifyUpdateProductsService;
use App\Services\Shopify\ShopifyUpdateShopService;
use App\Services\ToastService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateShopifyStoreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $store;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Perform updates
        (new ShopifyUpdateShopService())->updateShop($this->store);
        (new ShopifyUpdateProductsService())->updateProducts($this->store);

        // Mark that update is complete and clear cached data
        $this->store->update(['update_started_at'=>null]);
        (new CacheService())->clearProductCache($this->store->id);

        // Fire a broadcast event so client can refresh using Socket.io
        BroadcastUpdateComplete::broadcast($this->store);

        // Broadcast a toast event
        (new ToastService())->broadcast(
            $this->store->user_id,
            'Updating of ' . $this->store->name . ' complete',
            [
                'icon' => 'success'
            ]
        );
    }
}

// job.queued.stub

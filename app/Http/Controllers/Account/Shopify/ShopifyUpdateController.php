<?php

namespace App\Http\Controllers\Account\Shopify;

use App\Http\Controllers\Controller;
use App\Jobs\UpdateShopifyStoreJob;
use App\Models\Store;
use App\Services\CacheService;
use App\Services\Shopify\ShopifyUpdateShopService;
use App\Services\Utilities\AlertService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShopifyUpdateController extends Controller
{
    public function index(int $storeId)
    {
        $store = Store::where('id', $storeId)->where('user_id', auth()->id())->first();

        if ($store) {

            // Update shop and product information in queued job
            $store->update(['update_started_at'=>Carbon::now()]);
            (new CacheService())->clearStoreCache(auth()->id());
            $store = Store::where('id', $store->id)->first();

            UpdateShopifyStoreJob::dispatch($store);

            return json_encode([
                'success' => true,
                'message' => $store->name . ' is updating now...'
            ]);

        } else {

            return json_encode([
                'success' => false,
                'message' => 'Sorry, there was a problem updating the selected store'
            ]);

        }
    }
}

// controller.plain.stub

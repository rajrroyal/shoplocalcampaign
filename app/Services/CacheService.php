<?php

namespace App\Services;

use App\Models\Store;

/**
 * Class CacheService
 *
 * Clear applicable caches
 *
 * @package App\Services
 */
class CacheService
{
    /**
     * Clear caches affected by a change in store data
     *
     * @param  int|null  $userId
     */
    public function clearStoreCache(int $userId = null)
    {
        if ($userId) {
            // Clear backend dashboard store list data
            (new StoreService())->clearCache($userId);
        }
    }

    /**
     * Clear caches affected by a change in product data
     *
     * @param  int|null  $storeId
     */
    public function clearProductCache(int $storeId = null)
    {
        if ($storeId) {

            $store = Store::where('id', $storeId)->first();

            if ($store) {
                // Clear backend store products data
                (new StoreProductService())->clearCache($storeId);

                // Clear store data in case number of products has changed
                $this->clearStoreCache($store->user_id);
            }
        }

        // Clear featured product data
        (new FeaturedProductService())->clearCache();
    }
}

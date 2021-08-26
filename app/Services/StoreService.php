<?php

namespace App\Services;

use App\Models\Store;
use App\Transformers\StoreTileTransformer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

/**
 * Class StoreService
 *
 * Class to prepare data for backend dashboard store list
 *
 * @package App\Services
 */
class StoreService
{
    const PAGESIZE = 12;

    /**
     * Return a paginated collection of stores for the specified user
     *
     * Page is specified as a querystring parameter
     *
     * @param  int  $userId
     * @return LengthAwarePaginator
     */
    public function getForUserId(int $userId, int $perPage = null)
    {
        $page = request()->input('page', 1);

        $cacheTag = 'stores-for-user-' . $userId;
        $cacheKey = 'stores-for-user-' . $userId . '-page-' . $page;

        return Cache::tags([$cacheTag])->rememberForever($cacheKey, function() use($userId) {

            $stores = Store
                ::with([
                    'products'
                ])
                ->where('user_id', $userId)
                ->orderBy('name')
                ->paginate(self::PAGESIZE);

            $updated = $stores->getCollection()->map(function ($store) {
                return $this->transformStore($store);
            });

            $stores->setCollection($updated);

            return $stores;
        });
    }

    public function transformStore(Store $store)
    {
        $transformed = (new StoreTileTransformer())->transform($store);

        $transformed->num_products = $store->products->count();

        $transformed->details_url = route('account-store.show', $store->id);

        switch ($store->source) {
            case 'shopify':
                $transformed->update_url = route('account-connect-shopify-update.index', $store->id);
                break;
            case 'shopcity':
                $transformed->update_url = route('account-connect-shopcity-update.index', $store->id);
                break;
            case 'ecwid':
                $transformed->update_url = route('account-connect-ecwid-update.index', $store->id);
                break;
        }

        return $transformed;
    }

    /**
     * Clear the store cache for specified user
     *
     * @param  int  $userId
     */
    public function clearCache(int $userId)
    {
        $cacheTag = 'stores-for-user-' . $userId;
        Cache::tags([$cacheTag])->flush();
    }
}

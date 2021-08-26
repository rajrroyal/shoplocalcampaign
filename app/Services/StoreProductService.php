<?php

namespace App\Services;

use App\Models\Product;
use App\Transformers\ProductTileTransformer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

/**
 * Class StoreProductService
 *
 * Class to prepare data for backend store product list
 *
 * @package App\Services
 */
class StoreProductService
{
    const PAGESIZE = 12;

    /**
     * Return a paginated collection of products for specified store
     *
     * Page is specified as a querystring parameter
     *
     * @param  int  $storeId
     * @return LengthAwarePaginator
     */
    public function getForStoreId(int $storeId)
    {
        $page = request('page', 1);

        $cacheTag = 'products-for-store-' . $storeId;
        $cacheKey = 'products-for-store-' . $storeId . '-page-' . $page;

        return Cache::tags([$cacheTag])->rememberForever($cacheKey, function() use($storeId) {

            $products = Product
                ::where('store_id', $storeId)
                ->with([
                    'store',
                ])
                ->orderBy('name')
                ->paginate(self::PAGESIZE);

            $productTileTransformer = new ProductTileTransformer();

            $updated = $products->getCollection()->map(function($product) use($productTileTransformer) {
                $transformed = $productTileTransformer->transform($product);
                $transformed->main_image_src = optional($transformed->image)->src;
                return $transformed;
            });

            $products->setCollection($updated);

            return $products;
        });
    }

    /**
     * Clear the product cache for specified store
     *
     * @param  int  $storeId
     */
    public function clearCache(int $storeId)
    {
        $cacheTag = 'products-for-store-' . $storeId;
        Cache::tags([$cacheTag])->flush();
    }
}

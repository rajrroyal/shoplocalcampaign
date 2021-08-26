<?php

namespace App\Services;

use App\Models\Product;
use App\Transformers\ProductTileTransformer;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

/**
 * Class FeaturedProductService
 *
 * Class to prepare data for the featured products page
 *
 * @package App\Services
 */
class FeaturedProductService
{
    const PAGESIZE = 12;
    /**
     * Return a paginated collection of featured products
     *
     * @return LengthAwarePaginator
     */
    public function getProducts()
    {
        $page = request('page',1);

        return Cache::tags(['featured-products'])->rememberForever('featured-page-' . $page, function() {

            // Get paginated data
            $products = Product
                ::with([
                    'store',
                    'store.logo',
                    'mainImage' => function(MorphMany $query) {
                        $query->where('collection_name', 'main-image');
                    },
                    'images' => function(MorphMany $query) {
                        $query->where('collection_name', 'images');
                    }
                ])
                ->orderBy('name')
                ->paginate(self::PAGESIZE);

            // Transform data into preferred format
            $productToTileTransformer = new ProductTileTransformer();

            $updated = $products->getCollection()->map(function($product) use($productToTileTransformer) {
                return $productToTileTransformer->transform($product);
            });

            // Save transformations
            $products->setCollection($updated);

            return $products;
        });
    }

    /**
     * Clear the featured product cache
     */
    public function clearCache()
    {
        Cache::tags(['featured-products'])->flush();
    }
}

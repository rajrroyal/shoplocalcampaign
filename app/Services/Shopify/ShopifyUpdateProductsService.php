<?php

namespace App\Services\Shopify;

use App\Events\BroadcastUpdateProgress;
use App\Models\ErrorLog;
use App\Models\Media;
use App\Models\Product;
use App\Models\Store;
use App\Services\CacheService;
use App\Services\FeaturedProductService;
use App\Services\StoreProductService;
use App\Services\StoreService;
use App\Services\ToastService;
use App\Services\Utilities\JsonService;
use Illuminate\Support\Facades\Http;

class ShopifyUpdateProductsService
{
    public function updateProducts(Store $store)
    {
        $toastService = new ToastService();

        // Shopify API URL
        $url = 'https://'
            .config('shopify.api_key').':'.config('shopify.api_secret')
            .'@'.$store->source_url.'/admin/api/'.config('shopify.api_version')
            .'/products.json';

        // Get response from Shopify
        $response = Http
            ::withHeaders([
                'X-Shopify-Access-Token' => $store->source_access_token
            ])
            ->get($url);

        // Decode JSON
        $data = (new JsonService())->decode($response);

        if ($data && $data->success && property_exists($data, 'data')) {

            $shopifyProducts = $data->data->products;
            $totalProducts = sizeof($shopifyProducts);
            $productsUpdated = 0;

            // Send starting update for 0%
            BroadcastUpdateProgress::dispatch($store, 0, $totalProducts);

            foreach($shopifyProducts as $shopifyProduct) {

                // All Shopify products have at least one variant representing the main product
                $variant = $shopifyProduct->variants[0];

                $product = Product::updateOrCreate(
                    [
                        'store_id' => $store->id,
                        'source_ref_id' => $shopifyProduct->id
                    ],
                    [
                        'source_url' => $store->url . '/products/' . $shopifyProduct->handle,
                        'name' => $shopifyProduct->title,
                        'sku' => $variant->sku,
                        'description' => $shopifyProduct->body_html,
                        'tags' => implode(', ', [$shopifyProduct->product_type, $shopifyProduct->tags]),
                        'price' => $variant->price,

                        'raw_json' => json_encode($shopifyProduct)
                    ]
                );

                // Main Image
                $this->updateMainImage($shopifyProduct, $product);

                // Other Images
                $this->updateOtherImages($shopifyProduct, $product);

                // Broadcast an update every 5 products
                $productsUpdated++;
                if ($productsUpdated % 5 == 0) {
                    BroadcastUpdateProgress::dispatch($store, $productsUpdated, $totalProducts);
                }
                // Clear affected caches
                (new CacheService())->clearProductCache($product->store_id);
            }

            // Send last update for 100%
            BroadcastUpdateProgress::dispatch($store, $totalProducts, $totalProducts);



            return true;
        }

        ErrorLog::create([
            'event' => 'Fetching Shopify Products',
            'data' => json_encode([
                'store' => $store,
                'url' => $url,
                'data' => $data,
                'response_body' => $response->body(),
                'response_status' => $response->status()
            ])
        ]);

        return false;
    }

    // ---------------------------------------------------------------------

    protected function updateMainImage($shopifyProduct, $product)
    {
        $shopifyMainImage = (property_exists($shopifyProduct, 'image')) ? $shopifyProduct->image : null;
        $productMainImage = $product->getFirstMedia('main-image');

        // If main image has been removed on Shopify, remove locally as well
        if (! $shopifyMainImage && $productMainImage) {
            $productMainImage->delete();
            return;
        }

        // $isNewImage = ($shopifyMainImage && ! $productMainImage);
        // $imageChanged = ($productMainImage && ((int) $shopifyMainImage->id !== (int) $productMainImage->name));

        $currentMainImage = Media
            ::where('model_type', 'App\Models\Product')
            ->where('model_id', $product->id)
            ->where('collection_name', 'main-image')
            ->first();

        // If main image has changed or been added, copy to local
        if (($shopifyMainImage != null) && (! $currentMainImage || (int) $currentMainImage->name !== (int) $shopifyMainImage->id)) {

            $product
                ->addMediaFromUrl($shopifyMainImage->src)
                ->setName($shopifyMainImage->id)
                ->toMediaCollection('main-image');
        }
    }

    protected function updateOtherImages($shopifyProduct, $product)
    {
        $shopifyImages = (property_exists($shopifyProduct, 'images')) ? $shopifyProduct->images : [];
        $productImages = $product->getMedia('images') ?? [];

        // If all images have been removed from Shopify, remove local copies as well
        if (! sizeof($shopifyImages) && sizeof($productImages)) {
            $product->clearMediaCollection('images');
            return;
        }

        $currentLocalImages = Media
            ::where('model_type', 'App\Models\Product')
            ->where('model_id', $product->id)
            ->get();

        $shopifyImageIds = [];

        // Add any images from Shopify missing from local assets
        foreach($shopifyImages as $shopifyImage) {

            $name = (int) $shopifyImage->id;
            $shopifyImageIds[] = $name;

            $existingImage = $currentLocalImages
                ->where('collection_name', 'images')
                ->where('name', $name)
                ->first();

            if (! $existingImage) {

                $product
                    ->addMediaFromUrl($shopifyImage->src)
                    ->setName($name)
                    ->toMediaCollection('images');
            }
        }

        // Remove any local assets that no longer appear on Shopify
        if ($productImages && $productImages->count()) {
            foreach($productImages as $productImage) {
                if (! in_array($productImage->name, $shopifyImageIds)) {
                    $productImage->delete();
                }
            }
        }
    }
}

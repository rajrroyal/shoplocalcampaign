<?php

namespace App\Services\Shopcity;

use App\Events\BroadcastUpdateProgress;
use App\Models\Media;
use App\Models\Product;
use App\Models\Shopcity\ShopcityMicroproduct;
use App\Models\Store;
use App\Services\CacheService;

class ShopcityUpdateMicroproductService
{
    public function updateProducts(Store $store, $shopcityProducts)
    {
        $totalProducts = sizeof($shopcityProducts);
        $productsUpdated = 0;

        // Send starting update for 0%
        BroadcastUpdateProgress::dispatch($store, 0, $totalProducts);

        // Products
        foreach($shopcityProducts as $shopcityProduct) {

            $localProduct = Product::updateOrCreate(
                [
                    'store_id' => $store->id,
                    'source_ref_id' => $shopcityProduct->productid
                ],
                [
                    'source_url' => $store->url . '?listing.action=products&view=details&productid=' . $shopcityProduct->productid,
                    'name' => $shopcityProduct->title,
                    'sku' => $shopcityProduct->skunumber,
                    'description' => $shopcityProduct->descr,
                    'tags' => $this->productTags($shopcityProduct),
                    'price' => $this->productPrice($shopcityProduct),
                    'raw_json' => json_encode($shopcityProduct)
                ]
            );

            $this->updateImages($localProduct, $shopcityProduct->images);

            // Broadcast an update every 5 products
            $productsUpdated++;
            if ($productsUpdated % 5 == 0) {
                BroadcastUpdateProgress::dispatch($store, $productsUpdated, $totalProducts);
            }
        }

        // Send last update for 100%
        BroadcastUpdateProgress::dispatch($store, $totalProducts, $totalProducts);

        (new CacheService())->clearProductCache($store->id);
    }

    // ---------------------------------------------------------------------

    protected function productPrice(ShopcityMicroproduct $shopcityProduct)
    {
        if ($shopcityProduct->adtype == 1000) {
            return $shopcityProduct->onsale ? $shopcityProduct->marketsaleprice : $shopcityProduct->marketprice;
        } else {
            return $shopcityProduct->price;
        }
    }

    protected function productTags(ShopcityMicroproduct $shopcityProduct)
    {
        $tags = [];

        // Main category
        if ($shopcityProduct->category) {
            $tags[] = $shopcityProduct->category->catname;
        }

        // Microcats
        if ($shopcityProduct->microcatsToProduct) {
            foreach($shopcityProduct->microcatsToProduct as $microcatToProduct) {
                if ($microcatToProduct->microcat) {
                    $tags[] = $microcatToProduct->microcat->display;
                }
            }
        }

        return implode(', ', $tags);
    }

    protected function updateImages(Product $localProduct, $shopcityImages)
    {
        // If all pictures have been removed from Shopcity, remove local copies as well

        if (! $shopcityImages || ! $shopcityImages->count()) {

            $localProduct->clearMediaCollection('main-image');
            $localProduct->clearMediaCollection('images');
            return;
        }

        $currentLocalImages = Media
            ::where('model_type', 'App\Models\Product')
            ->where('model_id', $localProduct->id)
            ->get();

        $mainImageFound = false;
        $activeImageIds = [];

        foreach($shopcityImages as $image) {

            if ($image->main) {
                $imageUrl = $image->mainImageSrc();
            } else {
                $imageUrl = $image->extraImageSrc();
            }

            $imageExists = @getimagesize($imageUrl);

            if ($imageExists) {

                $imageId = $image->imageid;
                $activeImageIds[] = $imageId;

                // Only update if the image doesn't already exist in the collection

                $localImage = $currentLocalImages
                    ->where('collection_name', 'images')
                    ->where('name', $imageId)
                    ->first();

                if (! $localImage) {

                    $localProduct
                        ->addMediaFromUrl($imageUrl)
                        ->setName($imageId)
                        ->toMediaCollection('images');
                }

                // If this is the main image, update the main-image collection

                if ($image->main) {

                    $mainImageFound = true;

                    $currentMainImage = $currentLocalImages
                        ->where('collection_name', 'main-image')
                        ->where('name', $imageId)
                        ->first();

                    // Only update if the main image doesn't currently exist, or if it has changed

                    if (! $currentMainImage || (int) $currentMainImage->name !== (int) $imageId) {

                        $localProduct
                            ->addMediaFromUrl($imageUrl)
                            ->setName($image->imageid)
                            ->toMediaCollection('main-image');
                    }
                }
            }
        }

        // Remove any local images that are not longer used on Shopcity

        foreach($localProduct->getMedia('images') as $localImage) {
            if (! in_array($localImage->name, $activeImageIds)) {
                $localImage->delete();
            }
        }

        // If there are images, but none were explicitly marked as main, set first image as main

        if (! $mainImageFound && sizeof($activeImageIds)) {

            $firstLocalImage = $localProduct->getFirstMedia('images');

            $localProduct
                ->addMediaFromUrl($firstLocalImage->getUrl())
                ->setName($firstLocalImage->name)
                ->toMediaCollection('main-image');
        }
    }
}

<?php

namespace App\Services\Ecwid;

use App\Events\BroadcastUpdateProgress;
use App\Models\Media;
use App\Models\Product;
use App\Models\Store;
use App\Services\Utilities\JsonService;
use Illuminate\Support\Facades\Http;
use Spatie\MediaLibrary\ResponsiveImages\Exceptions\InvalidTinyJpg;
use Illuminate\Support\Facades\Log;

class EcwidUpdateProductsService
{
    const PER_PAGE = 100;

    public function update(Store $store)
    {
        $data = $this->fetchPage($store, 0);

//        var_dump($data);exit();
        if (property_exists($data, 'total')) {

            $page = 1;
            $totalProducts = (int) ($data->total ? $data->total : 0);
            $totalPages = ceil($totalProducts / $data->limit);
            $productsUpdated = 0;

            // Send starting update for 0%
            BroadcastUpdateProgress::dispatch($store, 0, $totalProducts);

            while($data) {

                // process items
                foreach($data->items as $ecwidProduct) {
                    $product = Product::updateOrCreate(
                        [
                            'store_id' => $store->id,
                            'source_ref_id' => $ecwidProduct->id
                        ],
                        [
                            'source_url' => $ecwidProduct->url,
                            'name' => $ecwidProduct->name,
                            'sku' => $ecwidProduct->sku,
                            'description' => isset($ecwidProduct->description) ? $ecwidProduct->description : '',
                            'tags' => implode(', ', $ecwidProduct->categoryIds),
                            'price' => $ecwidProduct->price,
                            'raw_json' => json_encode($ecwidProduct)
                        ]
                    );

                    $this->updateImages($ecwidProduct, $product);

                    // Broadcast an update every 5 products
                    $productsUpdated++;
                    if ($productsUpdated % 5 == 0) {
                        BroadcastUpdateProgress::dispatch($store, $productsUpdated, $totalProducts);
                    }
                }

                // Move to next page
                $page++;
                if ($page <= $totalPages) {
                    $offset = ($page - 1) * self::PER_PAGE;
                    $data = $this->fetchPage($store, $offset);
                } else {
                    $data = null;
                }
            }

            // Send last update for 100%
            BroadcastUpdateProgress::dispatch($store, $totalProducts, $totalProducts);
        }
    }

    // ---------------------------------------------------------------------

    protected function fetchPage(Store $store, int $offset = 0)
    {
        $requestUrl = 'https://app.ecwid.com/api/v3/'.$store->source_url.'/products'
            .'?token='.$store->source_access_token
            .'&enabled=true';

        $response = Http::get($requestUrl);

        $data = (new JsonService())->decode($response, JsonService::OBJECT_FORMAT);

        if ($data->success) {
            return $data->data;
        }

        return null;
    }

    protected function updateImages($ecwidProduct, $product)
    {
        if (
            !property_exists($ecwidProduct, 'media') ||
            !property_exists($ecwidProduct->media, 'images') ||
            !sizeof($ecwidProduct->media->images)
        ) {
            // If there are no images in the remote collection,
            // remove any existing images in the local collections
            $product->clearMediaCollection('main-image');
            $product->clearMediaCollection('images');
            return;
        }

        $currentMainImage = $product->getFirstMedia('main-image');

        $currentImages = Media
            ::where('model_type', 'App\Models\Product')
            ->where('model_id', $product->id)
            ->where('collection_name', 'images')
            ->get();

        $remoteImageIds = [];


        foreach($ecwidProduct->media->images as $ecwidImage) {
            try {
                if ($ecwidImage->isMain) {
                    // If there is no local main image, or the image has changed, add to main-image collection
                    if (!$currentMainImage || (int) $currentMainImage->name !== (int) $ecwidImage->id) {
                        $product
                            ->addMediaFromUrl($ecwidImage->imageOriginalUrl)
                            ->setName((int) $ecwidImage->id)
                            ->toMediaCollection('main-image');
                    }
                }

                if (!$currentImages->where('name', (int) $ecwidImage->id)->first()) {
                    // If the image does not already exist in the local image collection, add it
                    $product
                        ->addMediaFromUrl($ecwidImage->imageOriginalUrl)
                        ->setName((int) $ecwidImage->id)
                        ->toMediaCollection('images');
                }

                // Record the id of the remote image
                $remoteImageIds[] = (int) $ecwidImage->id;
             } catch (InvalidTinyJpg $exception) {
                Log::error("Error: ".$exception->getMessage()."Product ID: ".$ecwidProduct->id);
             }
        }

        // Remove any local images that no longer exist in the remote collection
        $removedImages = $currentImages->whereNotIn('name', $remoteImageIds);
        foreach($removedImages as $removedImage) {
            $removedImage->delete(); // @TODO does this also delete the image file?
        }
    }
}

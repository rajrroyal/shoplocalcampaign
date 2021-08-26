<?php

namespace App\Media;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class MediaPathGenerator implements PathGenerator
{
    /*
     * Get the path for the given media, relative to the root storage path.
     */
    public function getPath(Media $media): string
    {
        $parts = [];

        // Add a folder for the listing
        $parts[] = 'store-' . $this->getStoreId($media);

        // If this is a product image, add a folder for products and the specific product
        if ($media->model_type == 'App\Models\Product') {
            $parts[] = 'products';
            $parts[] = 'product-' . $media->model_id;
        }

        // Add a folder for the collection name
        if (strlen($media->collection_name)) {
            $parts[] = $media->collection_name;
        }

        // Add a folder for the specific image
        $parts[] = $media->id;

        // Trailing slash
        $parts[] = '';

        return implode(DIRECTORY_SEPARATOR, $parts);
    }

    /*
     * Get the path for conversions of the given media, relative to the root storage path.
     */
    public function getPathForConversions(Media $media): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            $this->getPath($media),
            'conversions',
            ''
        ]);
    }

    /*
     * Get the path for responsive images of the given media, relative to the root storage path.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            $this->getPath($media),
            'responsive-images',
            ''
        ]);
    }

    // ---------------------------------------------------------------------

    protected function getStoreId(Media $media)
    {
        switch($media->model_type) {
            case 'App\Models\Store':
                return $media->model_id;
            case 'App\Models\Product':
                return $media->model->store->id;
        }
    }
}

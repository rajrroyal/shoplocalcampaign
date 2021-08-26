<?php

namespace App\Transformers;

use App\Models\Image;
use Flugg\Responder\Transformers\Transformer;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImageTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [];

    /**
     * Transform the model.
     *
     * @param  Media  $image
     * @return array
     */
    public function transform(Media $image = null)
    {
        if ($image) {
            return (object) [
                'src' => $image->getFullUrl(),
                'img' => $image->toHtml(),
                'srcset' => $image->getSrcset(),
                'responsive_images' => $image->getResponsiveImageUrls(),
                'caption' => $image->getCustomProperty('caption'),
                'bytes' => $image->getHumanReadableSizeAttribute(),
                'media' => $image
            ];
        }

        return null;
    }
}

<?php

namespace App\Transformers;

use App\Models\Product;
use Flugg\Responder\Transformers\Transformer;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductTileTransformer extends Transformer
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
     * @param  \App\Models\Product $product
     * @return array
     */
    public function transform(Product $product)
    {
        return (object) [
            'type' => 'product',
            'id' => (int) $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'tags' => $product->tags,
            'url' => $product->source_url,
            'image' => $this->transformImage($product->getFirstMedia('main-image')),
            'updated_at' => $product->updated_at,
            'store' => (new StoreTileTransformer())->transform($product->store)
        ];
    }

    // ---------------------------------------------------------------------

    protected function transformImage(Media $media = null)
    {
        if ($media) {
            return (new ImageTransformer())->transform($media);
        }

        return null;
    }
}

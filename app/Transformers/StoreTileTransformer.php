<?php

namespace App\Transformers;

use App\Models\Store;
use Flugg\Responder\Transformers\Transformer;

class StoreTileTransformer extends Transformer
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
     * @param  \App\Models\Store $store
     * @return array
     */
    public function transform(Store $store)
    {
        return (object) [
            'type' => 'store',
            'id' => (int) $store->id,
            'source' => $store->source,
            'name' => $store->name,
            'url' => $store->url,
            'image' => (new ImageTransformer())->transform($store->getFirstMedia('logo')),
            'updating' => $store->update_started_at ? 1 : 0,
            'update_started_at' => $store->update_started_at,
            'updated_at' => $store->updated_at
        ];
    }

    // ---------------------------------------------------------------------


}

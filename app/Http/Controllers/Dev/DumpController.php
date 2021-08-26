<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use App\Services\Ecwid\EcwidUpdateProductsService;
use App\Services\ShopifyStoreService;
use Illuminate\Http\Request;

class DumpController extends Controller
{
    public function index()
    {
        $store = Store::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'source' => 'ecwid',
                'source_url' => '29511362'
            ]
        );

        (new EcwidUpdateProductsService())->update($store);

        $products = Product::where('store_id', $store->id)->get();
        dump($products->toArray());

        echo "Done";
    }
}

// controller.plain.stub

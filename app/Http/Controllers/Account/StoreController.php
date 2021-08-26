<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use App\Services\StoreProductService;
use App\Services\Utilities\DebugbarService;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function show(int $storeId)
    {
        $store = Store::where('id', $storeId)->where('user_id', auth()->id())->first();
        $products = (new StoreProductService())->getForStoreId($store->id);

        $data = [
            'store' => $store,
            'products' => $products
        ];

        (new DebugbarService())->dump($data);

        return view('account.store.show', $data);
    }
}

// controller.plain.stub

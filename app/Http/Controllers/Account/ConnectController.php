<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Services\Utilities\DebugbarService;
use Illuminate\Http\Request;

class ConnectController extends Controller
{
    /**
     * Display connection options (Shopify, PowerShop, Shopcity, ...)
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('account.connect.index');
    }
}

// controller.plain.stub

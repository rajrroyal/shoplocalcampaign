<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\FeaturedProductService;
use App\Services\Utilities\DebugbarService;
use Illuminate\Http\Request;
use Symfony\Component\ErrorHandler\Debug;

class FeaturedController extends Controller
{
    public function index()
    {
        $data = [
            'products' => (new FeaturedProductService())->getProducts()
        ];

        (new DebugbarService())->dump($data);

        return view('featured.index', $data);
    }
}

// controller.plain.stub

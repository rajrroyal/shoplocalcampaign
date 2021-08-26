<?php

namespace App\Http\Controllers;

use App\Services\Utilities\DebugbarService;
use Illuminate\Http\Request;
use Symfony\Component\ErrorHandler\Debug;

class HomeController extends Controller
{
    public function index()
    {
        $data = [

        ];

        (new DebugbarService())->dump($data);

        return view('home.index', $data);
    }
}

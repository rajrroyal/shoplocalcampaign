<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Services\StoreService;
use App\Services\Utilities\DebugbarService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'stores' => (new StoreService())->getForUserId(auth()->id())
        ];

        (new DebugbarService())->dump($data);

        return view('account.dashboard.index', $data);
    }
}

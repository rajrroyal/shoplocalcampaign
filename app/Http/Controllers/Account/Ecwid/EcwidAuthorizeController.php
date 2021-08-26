<?php

namespace App\Http\Controllers\Account\Ecwid;

use App\Http\Controllers\Controller;
use App\Services\Ecwid\EcwidAuthorizationService;
use App\Services\Utilities\AlertService;
use App\Services\Utilities\DebugbarService;
use Illuminate\Http\Request;

class EcwidAuthorizeController extends Controller
{
    public function create()
    {
        $data = [
            'oAuthRedirectUrl' => (new EcwidAuthorizationService())->oAuthRedirect()
        ];

        (new DebugbarService())->dump($data);

        return view('account.connect.ecwid.authorize', $data);
    }

    public function confirm(Request $request)
    {
        $alertService = new AlertService();

        if ($store = (new EcwidAuthorizationService())->confirm($request)) {
            $alertService->success($store->name . ' is updating now...');
        } else {
            $alertService->error('Sorry, there was a problem authenticating the selected store');
        }

        return view('account.connect.ecwid.callback');
    }
}

// controller.plain.stub

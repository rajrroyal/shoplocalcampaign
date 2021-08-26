<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Utilities\AlertService;
use http\Env\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function index()
    {
//        if(!session()->has('url.intended'))
//        {
////            session(['url.intended' => url()->previous()]);
//        }
//        session()->put('url.intended', url()->previous());

        return view('auth.login.index');
    }

    public function attempt(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember') && $request->remember;

        if (Auth::attempt($credentials, $remember)) {
//            $prev = session()->get('url.intended');
//            var_dump($prev);
//            sleep(5);
//            dd($prev);
//
//            if ($prev) {
//                return redirect('https://google.com');
//            }

            return redirect()->intended(route('account-dashboard.index'));
        } else {
            (new AlertService())->error('Invalid credentials');
            return back()->exceptInput('password');
        }
    }

    public function logout()
    {
        if (auth()->check()) {
            auth()->logout();
        }

        return redirect(route('home'));
    }
}

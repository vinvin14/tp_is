<?php

namespace App\Http\Controllers;

use App\Http\Traits\AuthTrait;
use Illuminate\Http\Request;

class MainController extends Controller
{
    use AuthTrait;

    public function login(Request $request)
    {
        list($is_auth, $response) = $this->isAuthorized($request->input('username'), $request->input('password'));
        if (!$is_auth) {
            return back()
                ->withInput()
                ->with('error', $response);
        }

        if ($request->input('rememberme')) {
            session(['username' => $request->input('username')]);
            session(['password' => $request->input('password')]);
        }

        session(['user' => $response->username]);
        session(['token' => $response->remember_token]);
        session(['account_role' => $response->role]);
        return redirect(route('dashboard'));
    }

    public function dashboard()
    {
        switch (session('account_role')) {
            case 'admin' :
                return view('dashboard')
                    ->with('page', 'dashboard');
                break;

            case 'client' :
                return view('dashboard')
                    ->with('page', 'dashboard');;
                break;
        }
    }

    public function logout(Request $request)
    {
        $this->clearToken(session('user'));
        $request->session()->flush();
        return redirect(route('login'));
    }
}

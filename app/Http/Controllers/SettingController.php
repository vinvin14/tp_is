<?php

namespace App\Http\Controllers;

use App\Http\Repositories\AccountRepository;
use App\Http\Services\SettingServices;
use App\Models\User;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function home(AccountRepository $accountRepository)
    {
        return view('setting.home')
        ->with('account', $accountRepository->getAccount(session('user')));
    }

    public function changePassword(AccountRepository $accountRepository)
    {   
        return view('setting.changepassword')
        ->with('account', $accountRepository->getAccount(session('user')));
    }

    public function savePassword(User $user, Request $request, SettingServices $settingServices)
    {
        $init = $settingServices->changePassword($user, $request->input('current_password'), $request->input('new_password'));
        
        if (@$init['error']) {
            return back()
            ->withInput()
            ->with('error', $init['error']);
        }

        return redirect(route('setting.changepassword', $user->id))
        ->with('success', 'Password successfully changed!');
    }
}

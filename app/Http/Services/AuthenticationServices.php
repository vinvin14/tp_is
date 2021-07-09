<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 13/05/2021
 * Time: 6:34 PM
 */

namespace App\Http\Services;


use App\Models\User;

class AuthenticationServices
{

    function isAuthorized($username, $password)
    {
        $curr_user = $this->hasAccount($username);
        if(empty($curr_user)) {
            return [false, "Account $username does not exist!"];
        }
        if(!password_verify($curr_user->password, $password)) {
            return [false, 'Incorrect Password!'];
        }
        $curr_user->update(
            [
                'remember_token' => $this->createUniqueToken(),
                'last_login' => date('Y-m-d'),
                'last_ip' => $_SERVER['REMOTE_ADDR']
            ]
        );

        $curr_user->fresh();
        return [true, $curr_user];
    }
    function hasAccount($username)
    {
        return User::query()
            ->whereUsername($username)
            ->first();
    }
    function isCurrentlyLogin($username)
    {
        $curr_login = User::query()
            ->whereUsername($username)
            ->first();
        $date_now = date_create(date('Y-m-d'));
        $last_login = date_create($curr_login->last_login);
        $date_difference = date_diff($date_now, $last_login);
        $date_difference = $date_difference->format('%R%');

        if($date_difference > 0) {
            return false;
        }
        return true;
    }
    function createUniqueToken()
    {
        $token = str_shuffle('qkz@-!2l9#1aeWx');
        $duplicate = User::query()->where('remember_token', $token)->count();

        while($duplicate > 0)
        {
            $token = str_shuffle($token);
        }
        return $token;
    }
}

<?php

namespace App\Http\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class AccountRepository
{
    public function getAccount($username)
    {
        return User::query()
        ->select(
            'id',
            'username',
            'account_owner',
            'role'
            )
        ->where('username', $username)
        ->first();
    }
}

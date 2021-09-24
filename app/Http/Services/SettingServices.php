<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SettingServices
{
    public function changePassword($user, $current_password, $new_password)
    {
        if (! password_verify($current_password, $user->password)) {
            return ['error' => 'Invalid current password!'];
        }

        $user->update(['password' => Hash::make($new_password)]);
    }
}

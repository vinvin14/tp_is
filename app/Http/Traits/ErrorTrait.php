<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 02/04/2021
 * Time: 2:47 PM
 */

namespace App\Http\Traits;


use App\Models\Error;

trait ErrorTrait
{
    function log_error($user, $module, $details)
    {
        Error::query()->create(
            [
                'user' => $user,
                'module' => $module,
                'details' => $details
            ]
        );
    }
}

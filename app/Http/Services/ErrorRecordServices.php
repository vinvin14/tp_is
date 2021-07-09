<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 13/05/2021
 * Time: 6:33 PM
 */

namespace App\Http\Services;


use App\Models\Error;

class ErrorRecordServices
{
    function log($module, $user, $error)
    {
        Error::query()->create(
            [
                'module' => $module,
                'user' => $user,
                'details' => $error,
            ]
        );
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 05/31/2021
 * Time: 6:14 PM
 */

namespace App\Http\Repositories;

use App\Models\PaymentMethod;

class PaymentMethodRepository
{
    public function all()
    {
        return PaymentMethod::query()
        ->orderBy('name', 'ASC')
        ->get();
    }
}

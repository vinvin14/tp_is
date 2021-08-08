<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 06/21/2021
 * Time: 8:29 PM
 */

namespace App\Http\Repositories;


use App\Models\Unit;

class UnitRepository
{
    public function all()
    {
        return Unit::query()
            ->orderBy('name', 'ASC')
            ->get();
    }
}

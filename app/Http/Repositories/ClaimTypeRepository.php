<?php

namespace App\Http\Repositories;

use App\Models\ClaimType;

class ClaimTypeRepository
{
    public function all()
    {
        return ClaimType::query()
        ->orderBy('name', 'ASC')
        ->get();
    }
}

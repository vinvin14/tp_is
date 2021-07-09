<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'address',
        'date_of_birth',
        'total_points',
        'customer_type',
        'current_points',
        'created_at',
        'updated_at',
    ];
}

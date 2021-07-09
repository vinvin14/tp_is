<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductQuantity extends Model
{
    use HasFactory;
    public $table = 'product_quantity';

    protected $fillable = [
        'product_id',
        'expiration_date',
        'received_date',
        'quantity',
        'created_at',
        'updated_at',
    ];
}

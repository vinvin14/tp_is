<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldProduct extends Model
{
    use HasFactory;
    public $table = 'sold_products';
    public $timestamps = false;
    protected $fillable = [
        'transaction_id',
        'stock_id',
        'order_id',
        'qty',
        'discounted_amount',
        'final_amount'
    ];

}

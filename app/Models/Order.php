<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    //    public $table = 'item_library';
    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'total_order_amount',
        'total_points',
        'order_status',
        'created_at',
        'updated_at',
    ];
}

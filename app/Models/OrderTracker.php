<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTracker extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'stock_id',
        'previous_qty',
        'after_qty',
        'created_at',
        'updated_at'
    ];
}

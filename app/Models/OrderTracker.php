<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTracker extends Model
{
    use HasFactory;
    public $table = 'order_tracker';
    protected $fillable = [
        'order_id',
        'stock_id',
        'order_qty',
        'stock_previous_qty',
        'stock_after_qty',
        'created_at',
        'updated_at'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreStockCount extends Model
{
    use HasFactory;
    public $table = 'pre_stock_count';
    public $timestamps = false;
    protected $fillable = [
        'product_id',
        'current_qty',
        'last_updated',
        'last_order_qty'
    ];
}

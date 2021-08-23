<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    //    public $table = 'item_library';
    public $timestamps = false;
    protected $fillable = [
        'transaction_id',
        'product_id',
        'qty',
        'discount_amount',
        'total_amount',
        'total_points'
    ];


    public function setTotalPointsAttribute($value)
    {
        $this->attributes['total_points'] = $value * $this->qty;
    }
}

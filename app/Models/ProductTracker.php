<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTracker extends Model
{
    use HasFactory;
    public $table = 'product_tracker';
    public $timestamps = false;
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
    protected $fillable = [
        'reference',
        'product_quantity_id',
        'reason',
        'transaction',
        'previous_quantity',
        'after_quantity',
        'reverted',
        'created_by',
        'updated_by',
    ];
}

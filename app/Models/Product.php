<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_title',
        'description',
        'uploaded_img',
        'original_img_file_name',
        'product_category',
        'price',
        'unit',
        'current_quantity',
        'minimum_quantity',
        'priority_level',
        'points',
        'created_at',
        'updated_at',
    ];
}

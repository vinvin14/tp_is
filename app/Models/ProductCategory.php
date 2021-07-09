<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    public $table = 'product_category';
    protected $fillable = [
        'category_name',
        'description',
        'created_at',
        'updated_at',
    ];

    public function specification()
    {
        return $this->belongsTo(Product::class);
    }
}

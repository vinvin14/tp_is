<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'uploaded_img',
        'original_img_file_name',
        'category_id',
        'current_qty',
        'price',
        'unit_id',
        'alert_level',
        'points',
        'created_at',
        'updated_at',
    ];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucfirst($value);
    }
}

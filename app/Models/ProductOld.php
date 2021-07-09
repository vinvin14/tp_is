<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOld extends Model
{
    use HasFactory;
//    public $table = 'prodc';
    protected $fillable = [
        'specifications_id',
        'initial_quantity',
        'current_quantity',
        'expiry_date',
        'date_received',
        'priority_level',
        'created_at',
        'updated_at',
    ];

    public function specification()
    {
        return $this->hasOne(Product::class, 'id','specifications_id');
    }
}

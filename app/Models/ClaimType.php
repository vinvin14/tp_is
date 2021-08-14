<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimType extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $table = 'claim_type';
    protected $fillable = [
        'name',
    
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }
}

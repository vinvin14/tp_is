<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'plural_name'
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }

    public function setPluralNameAttribute($value)
    {
        $this->attributes['plural_name'] = ucfirst($value);
    }
}

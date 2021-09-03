<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'details',
        'reference_title',
        'reference_id',
        'link',
        'created_at',
        'updated_at'
    ];
}

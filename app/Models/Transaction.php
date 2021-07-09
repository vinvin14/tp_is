<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
//    public $table = 'item_library';
    protected $fillable = [
        'customer',
        'order_ticket',
        'transaction_date',
        'total_amount',
        'total_points',
        'trans_status',
        'claim_type',
        'payment_type',
        'created_at',
        'updated_at',
    ];
}

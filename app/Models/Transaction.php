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
        'ticket_number',
        'transaction_date',
        'total_amount',
        'total_points',
        'trans_status',
        'claim_type',
        'payment_method_id',
        'remarks',
        'created_at',
        'updated_at',
    ];
}

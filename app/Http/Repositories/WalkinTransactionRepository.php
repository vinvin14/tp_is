<?php

namespace App\Http\Repositories;

use App\Models\Transaction;

class WalkinTransactionRepository
{
    public function all()
    {
        return Transaction::query()
        ->where('isWalkin', 1)
        ->get();
    }
}

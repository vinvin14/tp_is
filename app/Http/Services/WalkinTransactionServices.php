<?php

namespace App\Http\Services;

use App\Models\Transaction;
use Exception;

class WalkinTransactionServices
{
    private $error;
    public function __construct()
    {
        $this->error = new ErrorRecordServices();
    }
    public function create($request)
    {
        try {
            Transaction::query()
            ->create([
                'transaction_date' => $request['transaction_date'],
                'trans_status' => $request['trans_status'],
                'claim_type' => $request['claim_type'],
                'payment_method_id' => $request['payment_method_id'],
                'remarks' => $request['remarks'],
                'isWalkin' => 1
            ]);
        } catch (Exception $exception) {
            $this->error->log('WALKIN_TRANSACTION', session('user'), $exception->getMessage());
            return back()
                ->with('error', 'We are having technical issue, Please contact your Administrator to fix this!');
        }
    }

    public function update($transaction, $request)
    {
        try {
            $transaction->update($request);

            return $transaction;
        } catch (Exception $exception) {
            $this->error->log('WALKIN_TRANSACTION', session('user'), $exception->getMessage());
            return back()
                ->with('error', 'We are having technical issue, Please contact your Administrator to fix this!');
        }
    }
}

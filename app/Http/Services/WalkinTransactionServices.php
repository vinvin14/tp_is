<?php

namespace App\Http\Services;

use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\TransactionRepository;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

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
            return Transaction::query()
            ->create([
                'transaction_date' => $request['transaction_date'],
                'ticket_number' => $request['ticket_number'],
                'trans_status' => $request['trans_status'],
                'claim_type' => $request['claim_type'],
                'payment_method_id' => $request['payment_method_id'],
                'remarks' => $request['remarks'],
                'isWalkin' => 1
            ]);
        } catch (Exception $exception) {
            $this->error->log('WALKIN_TRANSACTION', session('user'), $exception->getMessage());
            return ['error', 'We are having technical issue, Please contact your Administrator to fix this!'];
        }
    }

    public function update($transaction, $request)
    {
        try {
            $transaction->update($request);

            return $transaction;
        } catch (Exception $exception) {
            $this->error->log('WALKIN_TRANSACTION', session('user'), $exception->getMessage());
            return ['error', 'We are having technical issue, Please contact your Administrator to fix this!'];
        }
    }

    public function delete($transaction)
    {
        try {
            $transaction->delete();
        } catch (Exception $exception) {
            $this->error->log('WALKIN_TRANSACTION', session('user'), $exception->getMessage());
            return ['error', 'We are having technical issue, Please contact your Administrator to fix this!'];
        }
    }

    public function checkout($transaction)
    {
        $transactionRepository = new TransactionRepository();
        $orderServices = new OrderServices();
        $total_amount = [];

        DB::beginTransaction();
        try {
            $orders = $transactionRepository->getAllOrdersByTransaction($transaction->id);
            foreach ($orders as $order) {
                $orderServices->finalizeOrder($order);
                array_push($total_amount, $order->total_amount);
            }
           
            $transaction->update(['total_amount' => array_sum($total_amount), 'trans_status' => 'completed']);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollback();
            $this->error->log('WALKIN_TRANSACTION', session('user'), $exception->getMessage());
            return ['error', 'We are having technical issue, Please contact your Administrator to fix this!'];
        }
    }

    public function createWalkinTicket()
    {
        // $string1 = str_shuffle('E!3R');
        $string2 = str_shuffle('KU@J');
        $ticket = 'WK-'.$string2.'-'.Carbon::now()->toDateString();

        $hasMatch = Transaction::query()
        ->where('ticket_number', $ticket)
        ->count();

        while($hasMatch != 0)
        {
            $ticket = 'WK-'.str_shuffle($string2).'-'.Carbon::now()->toStringDate();
        }

        return $ticket;
    }
}

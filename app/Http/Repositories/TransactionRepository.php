<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 06/11/2021
 * Time: 6:12 PM
 */

namespace App\Http\Repositories;


use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionRepository
{
    public function all()
    {
        return DB::table('transactions')
            ->leftJoin('customers', 'transactions.customer', '=', 'customers.id')
            ->select(
                'transactions.*',
                'transactions.id as transaction_id',
                'customers.firstname',
                'customers.middlename',
                'customers.lastname'
            )
//            ->groupBy(
//                'transactions.id',
//                'transactions.customer',
//                'transactions.order_ticket',
//                'transactions.transaction_date',
//                'transactions.total_amount',
//                'transactions.total_points',
//                'transactions.trans_status',
//                'transactions.claim_type',
//                'transactions.payment_type',
//                'transactions.created_at',
//                'transactions.updated_at'
//            )
            ->get();
    }

    public function getTransByCus($customer)
    {
        return DB::table('transactions')
            ->leftJoin('payment_method', 'transactions.payment_method_id', '=', 'payment_method.id')
            ->select(
                'transactions.*',
                'payment_method.name as paymentMethod'
            )
            ->where('customer', $customer)
            ->get();
    }

    public function getTransWithCus($id)
    {
        return DB::table('transactions')
            ->leftJoin('customers', 'transactions.customer', '=', 'customers.id')
            ->leftJoin('claim_type', 'transactions.claim_type', '=', 'claim_type.id')
            ->leftJoin('payment_method', 'transactions.payment_method_id', '=', 'payment_method.id')
            ->select(
                'transactions.*',
                'transactions.id as transaction_id',
                'customers.firstname',
                'customers.middlename',
                'customers.lastname',
                'payment_method.name as payment_method',
                'claim_type.name as claim_type'
            )
            ->where('transactions.id', $id)
            ->first();
    }

    public function create($request)
    {
        return Transaction::query()
            ->create($request);
    }

    public function update($transaction, $request)
    {
        return $transaction
            ->update($request)
            ->fresh();
    }

    public function hasOrders($id)
    {
        $orders = Order::query()
            ->where('transaction_id', $id)
            ->get();
        if ($orders) {
            return true;
        }
        return true;
    }

    public function isCompleted($transID)
    {
        $isCompleted = Transaction::query()
            ->find($transID);
        if($isCompleted->trans_status != 'pending') {
            return true;
        }
        return false;
    }
}

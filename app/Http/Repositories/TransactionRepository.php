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
            ->leftJoin('payment_types', 'transactions.payment_type', '=', 'payment_types.id')
            ->select(
                'transactions.*',
                'payment_types.type_name as payment_type'
            )
            ->where('customer', $customer)
            ->get();
    }

    public function getTransWithCus($id)
    {
        return DB::table('transactions')
            ->leftJoin('orders', 'transactions.id', '=', 'orders.transaction_id')
            ->leftJoin('customers', 'transactions.customer', '=', 'customers.id')
            ->leftJoin('payment_types', 'transactions.payment_type', '=', 'payment_types.id')
            ->select(
                'transactions.*',
                'orders.*',
                'transactions.id as transaction_id',
                'orders.id as order_id',
                'customers.firstname',
                'customers.middlename',
                'customers.lastname',
                'payment_types.type_name as payment_type',
                'payment_types.id as payment_type_id'
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

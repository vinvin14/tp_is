<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 07/04/2021
 * Time: 7:44 PM
 */

namespace App\Http\Traits;


use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

trait TransactionTrait
{
    function get_transaction($id)
    {
        return Transaction::query()->findOrFail($id);;
    }
    function get_transaction_with_cus($id)
    {
        return DB::table('transactions')
                        ->leftJoin('customers', 'transactions.customer', '=', 'customers.id')
                        ->where('id', $id)
                        ->first();
    }
    function get_order_ticket($id)
    {
        return $this->get_transaction($id)->order_ticket;
    }

    function transaction_exist($order_ticket)
    {
        $transaction = Transaction::query()->where('order_ticket', $order_ticket)->first();
        if($transaction) {
            return true;
        }
        return false;
    }
    function create_trans_order_ticket()
    {
        $last_order_ticket = Transaction::query()
                                        ->orderBy('created_at', 'DESC')
                                        ->limit(1)
                                        ->get();
        if(empty($last_order_ticket)) {
            return 'OR#'.date('Y').'-'.date('m').'1';
        }

        $ticket_reference = explode('-', $last_order_ticket);
        $ticket_date = explode('#', $ticket_reference[0]);
        if($ticket_date != date('Y')) {
            return 'OR#'.date('Y').'-'.date('m').'1';
        }

        return 'OR#'.date('Y').'-'.date('m').($ticket_reference[1]+1);
    }
    function compute_total_order($transaction_id, $order_amount)
    {
        $transaction = $this->get_transaction($transaction_id);
        $transaction->update(['total_amount' => ($transaction->total_amount + $order_amount)]);
    }

    function update_transaction_total_amount($transaction_id)
    {
        $trans_orders =  DB::table('orders')
                            ->selectRaw('SUM(total_order_amount) as trans_total_order_amount, SUM(total_order_points) as trans_total_order_points')
                            ->where('transaction', $transaction_id)
                            ->groupBy('transaction')
                            ->first();
        Transaction::query()
                        ->findOrFail($transaction_id)
                        ->update(
                            [
                                'total_amount' => $trans_orders->trans_total_order_amount,
                                'total_points' => $trans_orders->trans_total_order_price
                            ]
                        );
        return [$trans_orders->trans_total_order_amount, $trans_orders->trans_total_order_price];
    }
}

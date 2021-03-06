<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 22/05/2021
 * Time: 7:30 PM
 */

namespace App\Http\Repositories;


use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    public function all()
    {
        return DB::table('orders')
            ->leftJoin('transactions', 'orders.transaction_id', '=', 'transactions.id')
            ->leftJoin('products', 'orders.product_id', '=', 'products.id')
            ->leftJoin('units', 'products.unit', '=', 'units.id')
            ->leftJoin('customers', 'transactions.product_id', '=', 'product.id')
            ->select(
                'orders.*',
                'transactions.order_ticket',
                'customers.firstname',
                'customers.lastname',
                'customers.middlename',
                'customers.customer_type',
                'units.unit_name as unit',
                'products.item_title',
                'products.price as price',
                'products.points as points'
            )
            ->orderBy('orders.created_at', 'ASC')
            ->get();
    }

    public function getOrder($id)
    {
        return DB::table('orders')
        ->leftJoin('products', 'orders.product_id', '=', 'products.id')
        ->select(
            'orders.*',
            'products.title'
        )
        ->where('orders.id', $id)
        ->get();
    }

    public function getOrderByTransaction($transaction_id)
    {
        return DB::table('orders')
        // ->leftJoin('stocks', 'orders.stock_id', '=', 'stocks.id')
        ->leftJoin('products', 'orders.product_id', '=', 'products.id')
        ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
        ->leftJoin('units', 'products.unit_id', '=', 'units.id')
        ->where('orders.transaction_id', $transaction_id)
        ->select(
            'orders.id as id',
            'orders.qty',
            'orders.total_amount as total_amount',
            'orders.total_points as total_points',
            'orders.discount_amount',
            'products.title as title',
            'products.price as price',
            'products.points as points',
            'categories.name as category',
            'units.name as unit',
            // DB::raw('orders.qty * products.price as total_amount')
        )
        ->get();
    }

    public function orderExists($transaction_id, $product_id)
    {
        $order = Order::query()
            ->where([
                'transaction_id' => $transaction_id,
                'product_id' => $product_id
            ])
            ->first();
        if( $order ) {
            return true;
        }
        return false;
    }

    public function ticketExists($ticket)
    {
        $ticket = Transaction::query()
            ->where('order_ticket', $ticket)
            ->first();
        if ($ticket) {
            return true;
        }
        return false;
    }
}

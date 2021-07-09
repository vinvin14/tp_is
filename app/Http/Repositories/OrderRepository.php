<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 22/05/2021
 * Time: 7:30 PM
 */

namespace App\Http\Repositories;


use App\Models\Order;
use App\Models\TemporaryOrder;
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

    public function find($id)
    {
        return Order::query()->findOrFail($id);
    }

    public function findWithJoin($id)
    {
        return DB::table('orders')
//            ->leftJoin('transactions', 'orders.transaction_id', '=', 'transactions.id')
            ->leftJoin('products', 'orders.product_id', '=', 'products.id')
            ->leftJoin('product_category', 'products.product_category', '=', 'product_category.id')
            ->leftJoin('units', 'products.unit', '=', 'units.id')
            ->where('orders.id', $id)
            ->select(
                'orders.*',
                'products.*',
                'product_category.category_name as product_category_name',
                'units.unit_name as unit'
            )
            ->first();
    }

    public function findTempOrder($id)
    {
        return TemporaryOrder::query()->findOrFail($id);
    }

    public function allTempOrders()
    {
        return DB::table('temporary_orders')
            ->leftJoin('transactions', 'orders.transaction_id', '=', 'transactions.id')
            ->leftJoin('product', 'orders.product_id', '=', 'product.id')
            ->orderBy('created_at', 'ASC')
            ->get();
    }

    public function getOrdersByTrans($id)
    {
        return DB::table('orders')
            ->leftJoin('products', 'orders.product_id', '=', 'products.id')
            ->leftJoin('product_category', 'products.product_category', '=', 'product_category.id')
            ->leftJoin('units', 'products.unit', '=', 'units.id')
            ->where('transaction_id', $id)
            ->select(
                'orders.transaction_id',
                'orders.product_id',
                'orders.id as id',
                'orders.quantity',
                'orders.total_order_amount',
                'products.item_title',
                'products.uploaded_img',
                'products.price as price',
                'products.points as points',
                'product_category.category_name as category_name',
                'units.unit_name as unit'
            )
            ->get();
    }

    public function getTotalOrderAmountByTransaction($transaction)
    {
        return DB::table('orders')
            ->where('transaction_id', $transaction)
            ->sum('orders.total_order_amount');
    }

    public function getOrdersByTrans2($transaction_id)
    {
        return DB::table('orders')
            ->leftJoin('transactions', 'orders.transaction_id', '=', 'transactions.id')
            ->leftJoin('products', 'orders.product_id', '=', 'products.id')
            ->where('transaction_id', $transaction_id)
            ->selectRaw('SUM(orders.quantity) as order_total_quantity, SUM(orders.total_order_amount) as order_total_amount')
            ->select(
                'orders.transaction_id',
                'orders.product_id',
                'orders.id as order_id',
                'products.item_title',
                'products.price as price',
                'products.points as points'
            )
            ->groupBy('orders.id', 'orders.transaction_id', 'orders.product_id', 'products.item_title'
                , 'products.price', 'products.points')
            ->get();
    }

    public function create($request)
    {
        return Order::query()->create($request);
    }

    public function update($order, $request)
    {
        $order->update($request);
        return $order->fresh();
    }

    public function isFinal($id)
    {
        $order = $this->find($id);
        if ($order->order_status != 'final') {
            return false;
        }
        return true;
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

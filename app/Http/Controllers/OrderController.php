<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CategoryRepository;
use App\Http\Repositories\ProductRepository;
use App\Http\Services\OrderServices;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        return view('shop.order.show')
        ->with(compact('order'))
        ->with('page', 'shop');
    }

    public function store($transaction_id, Request $request, OrderServices  $orderServices)
    {
        $request = $request->only('product_id', 'price', 'qty', 'discount_amount', 'total_amount', 'total_points', 'stock_id');
        $request['transaction_id'] = $transaction_id;

        $init = $orderServices->create($request);
        if (@$init['error'])
        {
            return back()
            ->with('error', $init['error'])
            ->withInput();
        }

        return redirect(route('transaction.show', $transaction_id))
        ->with('success', 'Order successfully added!');
    }

    public function delete(Order $order, OrderServices $orderServices)
    {
        $transaction_id = $order->transaction_id;
        $init = $orderServices->delete($order);

        if (@$init['error']) {
            return back()
            ->with('error', $init['error'])
            ->withInput();
        }

        return redirect(route('transaction.show', $transaction_id))
        ->with('success', 'Order has been successfully deleted!');
    }
}

<?php

namespace App\Http\Controllers;

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

    public function create($transaction_id)
    {
        return view('shop.order.create')
        ->with(compact('transaction_id'))
        ->with('page', 'shop');
    }

    public function store(Request $request, OrderServices  $orderServices)
    {
        $request = $request->only('transaction_id', 'product_id', 'qty', 'discount', 'total_amount', 'total_points');


    }
}

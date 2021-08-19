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

    public function addOrder($transaction_id, Request $request, CategoryRepository $categoryRepository, ProductRepository $productRepository)
    {
        $products = $productRepository->allWithPaginate(10);
        $categories =  $categoryRepository->all();
        if ($request->get('category')) {
            $products = $productRepository->allByCategoryWithPaginate($request->get('category'), 10);
        }

        return view('shop.order.create')
        ->with(compact('transaction_id'))
        ->with(compact('products'))
        ->with(compact('categories'))
        ->with('page', 'shop');
    }

    public function store($transaction_id, Request $request, OrderServices  $orderServices)
    {
        $request = $request->only('product_id', 'price', 'qty', 'discount_amount', 'total_amount', 'total_points', 'stock_id');
        $request['transaction_id'] = $transaction_id;
        $init = $orderServices->createOrder($request);

        if (@$init['error'])
        {
            return back()
            ->with('error', $init['error'])
            ->withInput();
        }

        return redirect(route('transaction.show', $transaction_id))
        ->with('success', 'Order successfully added!');
    }


}

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
        if ($request->get('category')) {
            $products = $productRepository->allByCategoryWithPaginate($request->get('category'), 10);
        }

        return view('shop.order.create')
        ->with(compact('transaction_id'))
        ->with(compact('products'))
        ->with('categories', $categoryRepository->all())
        ->with('page', 'shop');
    }

    public function store($transaction_id, Request $request, OrderServices  $orderServices)
    {
        $request = $request->only('product_id', 'qty', 'discount', 'total_amount', 'total_points');
        $request['transaction_id'] = $transaction_id;
        dd($request);
    }
}

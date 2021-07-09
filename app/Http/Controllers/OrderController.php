<?php

namespace App\Http\Controllers;

use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\ProductRepository;
use App\Http\Services\OrderServices;
use App\Http\Services\OrderServicesOld;
use App\Http\Traits\ErrorTrait;
use App\Http\Traits\ProductTrait;
use App\Http\Traits\OrderTrait;
use App\Http\Traits\TransactionTrait;
use App\Models\ProductOld;
use App\Models\Order;
use App\Models\TemporaryOrder;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request->except('_token');
    }

    public function index(OrderRepository $orderRepository)
    {
        $orders = $orderRepository->all();
        return view('order.list')
            ->with(compact('orders'));
    }

    public function show($id, OrderRepository $orderRepository)
    {
        $order = $orderRepository->findWithJoin($id);
        return view('order.show')
            ->with(compact('order'));
    }

    public function create($transaction_id, ProductRepository $productRepository)
    {
        $products = $productRepository->getProductsWQuantity();
        return view('admin.shop.order.create')
            ->with('page', 'shop')
            ->with(compact('products'))
            ->with('transaction_id', $transaction_id);
    }

    public function store($transaction_id, Request $request, OrderServices $orderServices)
    {
//        dd($request->post());
        $init = $orderServices->create($transaction_id, $request->except('_token'));
        if(@$init['error']) {
            return back()
                ->with('error', $init['error']);
        }
        return redirect(route('transaction.show', $transaction_id))
            ->with('response', 'Order has been successfully added!');
    }

    public function update(Order $order, ProductRepository $productRepository)
    {
        $product = $productRepository->getProductWithQuantityById($order->product_id);
//        dd($product);
        return view('admin.shop.order.update')
            ->with(compact('product'))
            ->with(compact('order'))
            ->with('transaction_id', $order->transaction_id)
            ->with('page', 'shop');
    }

    public function upsave(Order $order, Request $request, OrderServices $orderServices)
    {
        $orderServices->update($order, $request->except('_token'));
        return redirect(route('transaction.show', $order->transaction_id))
            ->with('response', 'Order has been updated!');

    }

    public function delete(Order $order, OrderServices $orderServices)
    {
        $transaction_id = $order->transaction_id;
        $orderServices->delete($order);
//        return redirect(route('transaction.show', $transaction_id))
//            ->with('response', 'Order entry has been successfully deleted!');
        return back()
            ->with('response', 'Order entry has been successfully deleted!');
    }
}

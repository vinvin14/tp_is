<?php

namespace App\Http\Controllers;

use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\ProductRepository;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    public function getProductByCategory($category, ProductRepository $productRepository)
    {
        //recount

        $products = $productRepository->allByCategory($category);
        if (empty($products->toArray())) {
            return response()->json('No Record(s) Found!', 404);
        }

        return response()->json($products, 200);
    }

    public function getOrder($id)
    {
        $order = DB::table('orders')
        ->leftJoin('products', 'orders.product_id', '=', 'products.id')
        ->select(
            'orders.*',
            'products.title'
        )
        ->where('orders.id', $id)
        ->first();

        if (empty($order)) {
            return response()->json('No Record(s) Found!', 404);
        }

        return response()->json($order, 200);
    }

    public function updateOrder(Order $order, Request $request, ProductRepository $productRepository)
    {
        try {
            $price = $productRepository->getPrice($order->product_id);
            $points = $productRepository->getPoints($order->product_id);

            if (! empty($request['discount_amount'])) {
                $total_amount = ($request['qty'] * $price) - $request['discount_amount'];
            }
            else
            {
                $total_amount = ($request['qty'] * $price);
            }

            $order->update(
                [
                    'qty' => $request['qty'],
                    'discount_amount' => $request['discount_amount'],
                    'total_amount' => $total_amount,
                    'total_points' => $points
                ]
            );
            return 200;
        } catch (Exception $exception) {
            return 404;
        }
    }
}

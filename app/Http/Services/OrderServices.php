<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 07/05/2021
 * Time: 6:05 PM
 */

namespace App\Http\Services;


use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\ProductRepository;
use App\Http\Repositories\ProductTrackerRepository;
use App\Http\Repositories\TransactionRepository;
use App\Models\ProductQuantity;
use App\Models\ProductTracker;
use Illuminate\Support\Facades\DB;

class OrderServices
{
    private $error, $orderRepository, $productRepository, $transactionRepository,
        $productTrackerRepository;

    public function __construct()
    {
        $this->error = new ErrorRecordServices();
        $this->orderRepository = new OrderRepository();
        $this->productRepository = new ProductRepository();
        $this->transactionRepository = new TransactionRepository();
        $this->productTrackerRepository = new ProductTrackerRepository();
    }

    public function create($transaction_id, $request)
    {
        if ($this->orderRepository->orderExists($transaction_id, $request['product_id'])) {
            return ['error' => 'This product has been ordered, if you want to decrease or increase the amount of the order you can update it on the transaction record'];
        }
        $product = $this->productRepository->getProduct($request['product_id']);
        $productPrevQty = $product->current_quantity;
        //return if current quantity is zero
        if ($product->current_quantity == 0) {
            return ['error' => 'Request Denied, Product has been depleted!'];
        }
        DB::beginTransaction();
        try {
            //create order
            $request['transaction_id'] = $transaction_id;
//            $request['total_order_amount'] = $product->price * $request['quantity'];
//            $request['total_points'] = $product->points * $request['quantity'];
            $newOrder = $this->orderRepository->create($request);
            //update product quantity
            $product->update(['current_quantity' => $product->current_quantity - $request['quantity']]);
            //create tracker
            $tracker = new ProductTrackerServices();
            $tracker->create(
                $newOrder->id,
                $product->id,
                $tracker->trackerReason(4),
                'out',
                $productPrevQty,
                $product->current_quantity,
                'no'
            );
            DB::commit();
            return $newOrder;
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            $this->error->log('ORDER_ADD', session('user'), $exception->getMessage());
            return ['error' => 'We are having technical problem, Please contact your Administrator!'];
        }
    }

    public function update($order, $request)
    {
        if ($this->transactionRepository->isCompleted($order->transaction_id)) {
            return ['error' => 'Order record cannot be updated since its transaction has been completed!'];
        }

        DB::beginTransaction();
        try {
            //get product
            $product = $this->productRepository->getProduct($order->product_id);
            //original quantity before order
            $beforeOrderQty = $product->current_quantity + $order->quantity;
            //get tracker
            $tracker = $this->productTrackerRepository->getTracker($order->id, 'ORDER');
            //update order
            $order->update([
                'quantity' => $request['quantity'],
                'total_order_amount' => $product->price * $request['quantity'],
                'total_points' => $product->points * $request['quantity']
            ]);
            //update product quantity
            $product->update(['current_quantity' => $beforeOrderQty - $request['quantity']]);
            //update tracker
            $tracker->update(['after_quantity' => $tracker->previous_quanttiy + $request['quantity']]);
            DB::commit();
            return $order;
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->error->log('ORDER_ADD', session('user'), $exception->getMessage());
            return ['error' => 'We are having technical problem, Please contact your Administrator!'];
        }
    }

    public function delete($order)
    {
        if ($this->transactionRepository->isCompleted($order->transaction_id)) {
            return ['error' => 'Order record cannot be updated since its transaction has been completed!'];
        }
//        DB::beginTransaction();
        $product = $this->productRepository->getProduct($order->product_id);
        $tracker = $this->productTrackerRepository->getTracker($order->id, 'ORDER');
        DB::beginTransaction();
        try {
            //revert back the product quantity
            $product->update(['current_quantity' => $product->current_quantity + $order->quantity]);
            $order->delete();
            $tracker->delete();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->error->log('ORDER_ADD', session('user'), $exception->getMessage());
            return ['error' => 'We are having technical problem, Please contact your Administrator!'];
        }
    }
}

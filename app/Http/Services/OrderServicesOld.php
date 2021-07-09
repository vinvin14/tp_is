<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 22/05/2021
 * Time: 7:29 PM
 */

namespace App\Http\Services;


use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\ProductRepositoryOld;
use App\Models\Order;
use App\Models\ProductTracker;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class OrderServicesOld
{
    private $orderRepository, $error;

    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
        $this->error = new ErrorRecordServices();
    }

    public function create($transaction_id, $request)
    {
        $productRepository = new ProductRepositoryOld();

        $order_quantity = $request->request_quantity;
        while ($order_quantity != 0) {
            $product = $productRepository->productPriority($request['specifications_id']);
            unset($request['specifications_id']);
            $order_quantity = $this->processOrder($transaction_id, $request, $product);
        }

    }

    public function update($order, $request)
    {
        try {
            return $this->orderRepository->update($order, $request);
        } catch (\Exception $exception) {
            $this->error->log('UPDATE_ORDER', session('user'), $exception->getMessage());
            return back()->with('error', 'Something went wrong, Please contact your Administrator!');
        }
    }

    public function processOrder($transaction_id, $request, $product)
    {
        $productRepository = new ProductRepositoryOld();
        if ($product->current_quantity == 0) {
            back()->with('error', 'Request Denied, ProductOld has been depleted!');
        }
        if ($productRepository->isExpired($product->expiry_date)) {
            back()->with('error', 'Request Denied, please double check the expiry date since ProductOld is expired!');
        }
        $product_quantity = $product->current_quantity - $request->request_quantity;
        switch (true) {
            case $product_quantity > 0 :
                $current_quantity = $product_quantity;
                $request_quantity = $request->request_quantity;
                $unaccommodated = 0;
                break;

            case $product_quantity == 0;
                $current_quantity = $product_quantity;
                $request_quantity = $request->request_quantity;
                $unaccommodated = 0;
                break;

            case $product_quantity < 0:
                $current_quantity = 0;
                $request_quantity = $product->current_quantity;
                $unaccommodated = abs($product_quantity);
                break;
        }

        $request['transaction_id'] = $transaction_id;
        $request['product_id'] = $product->id;
        $request['quantity'] = $request_quantity;
        $request['total_order_amount'] = $request_quantity * $productRepository->getPrice($product->specifications_id);

        DB::beginTransaction();

        try {
            $new_order = Order::query()
                ->create($request);

            ProductTracker::query()
                ->create([
                    'product' => $product->id,
                    'order' => $new_order->id,
                    'previous_quantity' => $product->current_quantity,
                    'after_quantity' => $current_quantity
                ]);
            return $unaccommodated;
        } catch (\Exception $exception) {
            $this->error->log('PROCESS_ORDER', session('user'), $exception->getMessage());
            return back()->with('error', 'We are having technical issue, Please contact your Administrator!');
        }
    }

    public function delete($order)
    {
        if ($this->orderRepository->isFinal($order->id)) {
            return back()->with('error', 'Order record cannot be deleted since it has been finalized!');
        }
        try {
            $order->delete();
        } catch (\Exception $exception) {
            $this->error->log('ORDER_UPDATE', session('user'), $exception->getMessage());
            return back()->with('error', 'We are having technical problem, Please contact your Administrator!');
        }
    }

    public function createOrderTicket()
    {
        $firstString = str_shuffle('X!OET');
        $secondString = str_shuffle('1E4Z');
        $string = $firstString.'-'.$secondString;
        $ticket = $string . '@' . date('Y-m-d');
        while ($this->orderRepository->ticketExists($ticket)) {
            $string = str_shuffle($firstString).'-'.str_shuffle($secondString);
            $ticket = $string . '@' . date('Y-m-d');
        }
        return $ticket;
    }
}

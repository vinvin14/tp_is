<?php

namespace App\Http\Services;

use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\ProductRepository;
use App\Http\Repositories\StockRepository;
use App\Models\Order;
use App\Models\OrderTracker;
use App\Models\SoldProduct;
use App\Models\Stock;
use Exception;

class OrderServices
{
    private $error;
    public function __construct()
    {
        $this->error = new ErrorRecordServices;
    }

    public function create($request)
    {
        $orderRepository = new OrderRepository();
        // $productRepository = new ProductRepository();
        // $product = $productRepository->getProduct($request['product_id']);
        try {
            if ($orderRepository->orderExists($request['transaction_id'], $request['product_id'])) {
                return ['error' => 'Order already exist, try to update it if you want changes to apply!'];
            }

            $total_amount = $request['price'] * $request['qty'];

            if (! empty($request['discount_amount']))
            {
                $discounted_amount = $total_amount - $request['discount_amount'];

                if ($discounted_amount < 0)
                {
                    $discounted_amount = 0;
                }

                $request['total_amount'] = $discounted_amount;
            }
            else
            {
                $request['total_amount'] = $total_amount;
            }
            unset($request['price']);

            Order::query()
            ->create($request);

        } catch (Exception $exception) {
            $this->error->log('CREATE_ORDER', session('user'), $exception->getMessage());
            return ['error' => 'We are experiencing technical problem, Please contact your Administrator!'];
        }
    }

    public function finalizeOrder($order)
    {
        $stockRepository = new StockRepository();
        $notification = new NotificationServices();

        $available_stock = $stockRepository->getAvailableStock2($order->qty, $order->product_id);
        // dd($available_stock);

        $order_left = $order->qty;

        foreach ($available_stock as $stock)
        {
            $order_left = $stock->qty - $order_left;
            $current_qty = $order_left;
            $sold_qty = $order->qty;

            if ($order_left <= 0)
            {
                $current_qty = 0;
                $order_left = abs($order_left);
                $sold_qty = $stock->qty;
            }

            $testArray = [];
            // array_push($testArray, [
            //     'transaction_id' => $order->transaction_id,
            //     'order_id' => $order->id,
            //     'stock_id' => $stock->id,
            //     'qty' => $sold_qty,
            //     'discounted_amount' => $order->discount_amount,
            //     'final_amount' => $order->total_amount
            // ]);

            Stock::query()
            ->where('id', $stock->id)
            ->update(['qty' => $current_qty]);

            SoldProduct::query()
            ->create([
                'transaction_id' => $order->transaction_id,
                'order_id' => $order->id,
                'stock_id' => $stock->id,
                'qty' => $sold_qty,
                'discounted_amount' => $order->discount_amount,
                'final_amount' => $order->total_amount
            ]);
            //this shoud be last so that the stock qty will be updated before creating a notification
            $notification->createProductDepletion($order->product_id);
        }
        // return $testArray;
    }

    public function delete($order)
    {
        try {
            $order->delete();
        } catch (Exception $exception) {
            $this->error->log('DELETE_ORDER', session('user'), $exception->getMessage());
            return ['error' => 'We are experiencing technical problem, Please contact your Administrator!'];
        }
    }

    public function createTracker($order_id, $stock_id, $previos_qty, $after_qty)
    {
        OrderTracker::query()
        ->create(
            [
                'order_id' => $order_id,
                'stock_id' => $stock_id,
                'previous_qty' => $previos_qty,
                'after_qty' => $after_qty
            ]
        );
    }

}

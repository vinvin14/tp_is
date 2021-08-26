<?php

namespace App\Http\Services;

use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\ProductRepository;
use App\Http\Repositories\StockRepository;
use App\Models\Order;
use App\Models\OrderTracker;
use App\Models\SoldProduct;
use Exception;
use Illuminate\Support\Facades\DB;

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
        $orderQty = $order->qty;

        while ($orderQty != 0)
        {
            $stock = $stockRepository->getAvailableStock($order->product_id);

            $orderQty = $this->distributeOrder($stock, $order, intval($orderQty));
        }
        return $order;
    }

    public function distributeOrder($stock, $order, $order_qty)
    {
        $stockRepository = new StockRepository();

        $stock_qty = ($stock->qty - $order_qty);

        switch (true)
        {
            case $stock_qty > 0:
                $after_qty = $stock_qty;
                $previos_qty = $stock->qty;
                $unaccommodated = 0;
                $sold_qty = $order_qty;
                break;

            case $stock_qty < 0:
                $after_qty = 0;
                $previos_qty = $stock->qty;
                $unaccommodated = abs($stock_qty);
                $sold_qty = $stock->qty;
                break;

            case $stock_qty == 0:
                $after_qty = 0;
                $previos_qty = $stock->qty;
                $unaccommodated = 0;
                $sold_qty = $order_qty;

                break;
        }

        OrderTracker::query()
        ->create([
            'order_id' => $order->id,
            'order_qty' => $order_qty,
            'stock_id' => $stock->id,
            'stock_previous_qty' => $previos_qty,
            'stock_after_qty' => $after_qty
        ]);

        SoldProduct::query()
        ->create([
            'transaction_id' => $order->transaction_id,
            'stock_id' => $stock->id,
            'qty' => $order_qty,
            'discounted_amount' => $order->discount_amount,
            'final_amount' => $order->total_amount
        ]);

        $currentStock = $stockRepository->getStockById($stock->id);
        $currentStock->update(
            [
                'qty' => $after_qty,
                'sold_qty' => $sold_qty
            ]);

        return $unaccommodated;
    }

    public function udpateOrder($product_id)
    {

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

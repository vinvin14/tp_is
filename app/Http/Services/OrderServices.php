<?php

namespace App\Http\Services;

use App\Http\Repositories\ProductRepository;
use App\Http\Repositories\StockRepository;
use App\Models\Order;
use App\Models\OrderTracker;
use Exception;

class OrderServices
{
    private $error;
    public function __construct()
    {
        $this->error = new ErrorRecordServices;
    }
    public function createOrder($request)
    {
        $stockRepository = new StockRepository();
        $orderQty = $request['qty'];
        try
        {
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
            unset($request['price']);

            $order = Order::query()
            ->create($request);

            while ($orderQty != 0)
            {
                $stock = $stockRepository->getAvailableStock($request['product_id']);

                $orderQty = $this->distributeOrder($stock, $order->id, $orderQty);
            }

            return $order;
        }
        catch (Exception $exception)
        {
            $this->error->log('CREATE_ORDER', session('user'), $exception->getMessage());
            return ['error' => 'We are experiencing technical problem, Please contact your Administrator!'];
        }
    }

    public function distributeOrder($stock, $order_id, $order_qty)
    {
        $stockRepository = new StockRepository();
        $stock_qty = $stock->qty - $order_qty;

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

            case $stock == 0:
                $after_qty = 0;
                $previos_qty = $stock->qty;
                $unaccommodated = 0;
                $sold_qty = $order_qty;
                break;
        }
        try
        {
            OrderTracker::query()
            ->create([
                'order_id' => $order_id,
                'order_qty' => $order_qty,
                'stock_id' => $stock->id,
                'stock_previous_qty' => $previos_qty,
                'stock_after_qty' => $after_qty
            ]);

            $currentStock = $stockRepository->getStockById($stock->id);
            $currentStock->update(
                [
                    'qty' => $after_qty,
                    'sold_qty' => $sold_qty
                ]);

            return $unaccommodated;
        }
        catch (Exception $exception)
        {
            $this->error->log('CREATE_ORDER', session('user'), $exception->getMessage());
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

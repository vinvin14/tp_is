<?php

namespace App\Http\Services;

use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\StockRepository;
use App\Models\Order;
use App\Models\OrderTracker;
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

            $order = Order::query()
            ->create($request);
        } catch (Exception $exception) {
            $this->error->log('CREATE_ORDER', session('user'), $exception->getMessage());
            return ['error' => 'We are experiencing technical problem, Please contact your Administrator!'];
        }
    }

    public function finalizeOrder($product_id, $order)
    {
        $stockRepository = new StockRepository();
        $orderQty = $order->qty;
        DB::beginTransaction();
        try
        {
            while ($orderQty != 0)
            {
                $stock = $stockRepository->getAvailableStock($product_id);

                $orderQty = $this->distributeOrder($stock, $order->id, intval($orderQty));
            }

            DB::commit();
            return $order;
        }
        catch (Exception $exception)
        {
            DB::rollback();
            dd($exception->getMessage());
            $this->error->log('FINALIZE_ORDER', session('user'), $exception->getMessage());
            return ['error' => 'We are experiencing technical problem, Please contact your Administrator!'];
        }
    }

    public function distributeOrder($stock, $order_id, $order_qty)
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

            case $stock == 0:
                $after_qty = 0;
                $previos_qty = $stock->qty;
                $unaccommodated = 0;
                $sold_qty = $order_qty;
                break;
        }

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

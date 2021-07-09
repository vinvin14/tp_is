<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 07/04/2021
 * Time: 7:46 PM
 */

namespace App\Http\Traits;


use App\Models\Order;
use App\Models\ProductOld;
use App\Models\ProductTracker;
use Illuminate\Support\Facades\DB;
use App\Models\TemporaryOrder;

trait OrderTrait
{
    use ErrorTrait, ProductTrait, TransactionTrait, CustomerTrait;
    function get_order_by_ticket($ticket)
    {
        return Order::query()
                ->where('order_ticket', $ticket)
                ->get();
    }
    function get_order_by_transaction($transaction_id)
    {
        return DB::table('orders')
            ->leftJoin('product', 'orders.product_id', '=', 'product.id')
            ->leftJoin('product_specifications', 'product.specifications_id', '=', 'product_specifications.id')
            ->orderBy('created_at', 'DESC')
            ->whereTransaction($transaction_id)
            ->select(
                'orders.*',
                'product_specifications.item_title',
                'product_specifications.price'
            )
            ->get();
    }
    function get_temporary_order_total_amount($transaction_id)
    {
        return DB::table('temporary_orders')
            ->selectRaw('SUM(total_order_amount) as temp_total_order_amount')
            ->whereTransaction($transaction_id)
            ->groupBy('transaction')
            ->first()
            ->temp_total_order_amount;
    }
    function get_final_order_total_amount($transaction_id)
    {
        return DB::table('orders')
            ->selectRaw('SUM(total_order_amount) as temp_total_order_amount')
            ->whereTransaction($transaction_id)
            ->groupBy('transaction')
            ->first()
            ->temp_total_order_amount;
    }
    function get_temporary_order_by_transaction($transction)
    {
        return DB::table('temporary_orders')
            ->leftJoin('product_specifications', 'temporary_orders.specifications_id', '=', 'product_specifications.id')
            ->where('transaction', $transction)
            ->select(
                'temporary_orders.*',
                'product_specifications.item_title',
                'product_specifications.price'
            )
            ->get();
    }
    function get_temporary_order($order_id)
    {
        return DB::table('temporary_orders')
                            ->leftJoin('product_specifications', 'temporary_orders.specifications_id', '=', 'product_specifications.id')
                            ->where('id', $order_id)
                            ->select(
                                'temporary_orders.*',
                                'product_specifications.item_title',
                                'product_specifications.price'
                            )
                            ->get();
    }
    function can_accommodate_order($request)
    {
        $avail_product = $this->get_total_products($request['specifications_id']);
        if($avail_product == 0) {
            return [404, 0, 0];
        }
        $current_quantity = $avail_product - intval($request['quantity']);

        switch (true)
        {
            case $current_quantity > 0:
                //status, accommodated_quantity, unaccommodated_quantity
                return [200, $request['quantity'], 0];
                break;
            case $current_quantity == 0:
                //status, accommodated_quantity, unaccommodated_quantity
                return [200, $request['quantity'], 0];
                break;

            case $current_quantity < 0:
                //status, accommodated_quantity, unaccommodated_quantity
                return [200, $avail_product, abs($current_quantity)];
                break;
        }

    }
    function process_order($order, $transaction_id)
    {
        $order_quantity = $order->quantity;

        while ($order_quantity != 0) {
            $product = $this->get_priority_product($order->specifications_id);
//            $product_specifications = $this->get_product_specification($order->specifications_id);
            if (empty($product)) {
                return 404;
            }
//            list($status, $unaccommodated_quantity) = $this->accommodate_order($transaction_id, $order, $product_specifications->price, $product_specifications->points, $product);
            list($status, $unaccommodated_quantity) = $this->accommodate_order($transaction_id, $order, $product);
            $order_quantity = $unaccommodated_quantity;
            if ($status != 200) {
                return $status;
            }
        }
    }
    function accommodate_order($transaction_id, $order, $product)
    {
        $transaction = $this->get_transaction($transaction_id);
        if($product->current_quantity == 0) {
            return [404, 0];
        }
        $current_quantity = $product->current_quantity - $order->quantity;
        switch (true)
        {
            case $current_quantity > 0:
                $product_quantity = $current_quantity;
                $accommodated_quantity = $order->quantity;
                $unaccommodated_quantity = 0;
                break;
            case $current_quantity == 0:
                $product_quantity = $current_quantity;
                $accommodated_quantity = $product->current_quantity;
                $unaccommodated_quantity = 0;
                break;

            case $current_quantity < 0:
                $product_quantity = 0;
                $accommodated_quantity = $product->current_quantity;
                $unaccommodated_quantity = abs($current_quantity);
                break;
        }
        DB::beginTransaction();
        try {

            $new_order = Order::query()
                ->create([
                    'transaction' => $transaction_id,
                    'product_id' => $product->id,
                    'quantity' => $accommodated_quantity,
                    'total_order_points' => $product->points * $accommodated_quantity,
                    'total_order_amount' => $product->price * $accommodated_quantity,
                    'order_status' => 'final',
                ]);

            $order->update(['finalized' => 1]);
            //update inventory current quantity
            $product->update(['current_quantity' => $product_quantity]);
            //create inventory tracker
            $this->create_product_tracker($product->id, $new_order->id, $product->current_quantity, $product_quantity);
            list($total_amount, $total_points) = $this->update_transaction_total_amount($transaction_id);
            //create update for customer points
            $this->process_points($transaction->customer, $total_points, 'add');
            DB::commit();
            return [200, $unaccommodated_quantity];
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            DB::rollBack();
            $this->log_error($_SESSION['user'], 'ORDER_UPDATE', $exception->getMessage());
            return [501, 0];
        }
    }
    function revert_order($order_id)
    {
//        $product_tracker = ProductTracker::query()->whereReference($order_id)->first();
        $product_tracker = ProductTracker::query()->whereOrder($order_id)->get();

        DB::beginTransaction();
        try {
            if(count($product_tracker) > 1) {
                foreach ($product_tracker as $product_tracker_row) {
                    $product = ProductOld::query()->findOrFail($product_tracker_row->product);
                    $product->update(['quantity' => $product_tracker_row->previous_quantity]);
                    $product_tracker_row->update(['reverted' => 1]);
                }
            }
            else
            {
                $product = ProductOld::query()->findOrFail($product_tracker->product);
                $product->update(['quantity' => $product_tracker->previous_quantity]);
            }

            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }


}

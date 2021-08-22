<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 06/11/2021
 * Time: 6:38 PM
 */

namespace App\Http\Services;


use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\TransactionRepository;
use App\Models\Transaction;
use Illuminate\Support\Carbon;

class TransactionServices
{
    private $error, $transRepository;

    public function __construct()
    {
        $this->error = new ErrorRecordServices();
        $this->transRepository = new TransactionRepository();
    }

    public function create($request)
    {
        try {
            return Transaction::query()
                ->create($request);
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            $this->error->log('TRANSACTION_ADD', session('user'), $exception->getMessage());
            return back()
                ->withInput()
                ->with('error', 'We are having technical issue, Please contact your Administrator to fix this!');
        }
    }

    public function update($transaction, $request)
    {
        try {
            return $transaction
                ->update($request)
                ->fresh();
        } catch (\Exception $exception) {
            $this->error->log('TRANSACTION_UPDATE', session('user'), $exception->getMessage());
            return back()
                ->withInput()
                ->with('error', 'We are having technical issue, Please contact your Administrator to fix this!');
        }
    }

    public function delete($transaction)
    {
        if ($this->transRepository->hasOrders($transaction->id)) {
            return back()->with('error', 'Transaction Record cannot be deleted since it has existing orders!');
        }
        try {
            $transaction->delete();
        } catch (\Exception $exception) {
            $this->error->log('TRANSACTION_DELETE', session('user'), $exception->getMessage());
            return back()
                ->with('error', 'We are having technical issue, Please contact your Administrator to fix this!');
        }
    }

    public function checkout($transaction, $request)
    {
        $orderRepository = new OrderRepository();

        $total_amount = [];
        $total_points = [];
        $orders = $orderRepository->getOrdersByTrans($transaction->id);
        foreach ($orders as $order) {
            array_push($total_amount, $order->price);
            array_push($total_points, $order->points);
        }
        try {

            $request['total_amount'] = array_sum($total_amount);
            $request['total_points'] = array_sum($total_points);
            $transaction->update($request);

            return $transaction->fresh();
        } catch (\Exception $exception) {
            $this->error->log('TRANSACTION_CHECKOUT', session('user'), $exception->getMessage());
            return back()
                ->with('error', 'We are having technical issue, Please contact your Administrator to fix this!');
        }
    }

    public function finalizeOrders($transaction, $product_id, $orderQuantity, $orderID)
    {
        $orders = $this->transRepository->getAllOrdersByTransaction($product_id);
        dd($orders);


        while ($orderQuantity != 0) {
            $productQuantity = $this->productRepository->getPriorityProductQuantity($productID);
            $newOrderQuantity = $productQuantity->quantity - $orderQuantity;
            $orderQuantity = $this->processProductQuantity($productQuantity, $newOrderQuantity, $orderID);
        }
    }

    public function processProductQuantity($productQuantity, $orderQuantity, $orderID)
    {
        $productTracker = new ProductTrackerServices();
        $currentProductQuantity = $productQuantity->quantity - $orderQuantity;
        switch (true) {
            case  $currentProductQuantity > 0:
                $prevProductQuantity = $productQuantity->quantity;
                $afterProductQuantity = $currentProductQuantity;
                $requestQuantity = $orderQuantity;
                $remainder = 0;
                break;
            case $currentProductQuantity == 0:
                $prevProductQuantity = $productQuantity->quantity;
                $afterProductQuantity = 0;
                $requestQuantity = $orderQuantity;
                $remainder = 0;
                break;
            case $currentProductQuantity < 0:
                $prevProductQuantity = $productQuantity->quantity;
                $afterProductQuantity = 0;
                $requestQuantity = $orderQuantity;
                $remainder = abs($currentProductQuantity);
                break;
        }

        $productQuantity->update(['quantity' => $afterProductQuantity]);
        $productTracker->create(
            $orderID,
            $productQuantity->id,
            $productTracker->trackerReason(4),
            'out',
            $prevProductQuantity,
            $afterProductQuantity,
            'no'
        );
        return $remainder;
    }

    public function createTicket()
    {
        $string1 = str_shuffle('E!3R');
        $string2 = str_shuffle('KU@J');
        $ticket = $string1.'-'.$string2.'-'.Carbon::now()->toDateString();
        
        $hasMatch = Transaction::query()
        ->where('ticket_number', $ticket)
        ->count();

        while($hasMatch != 0)
        {
            $ticket = str_shuffle($string1).'-'.str_shuffle($string2).'-'.Carbon::now()->toStringDate();
        }

        return $ticket;
    }
}

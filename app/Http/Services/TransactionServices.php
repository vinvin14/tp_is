<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 06/11/2021
 * Time: 6:38 PM
 */

namespace App\Http\Services;

use App\Http\Repositories\CustomerRepository;
use App\Http\Repositories\TransactionRepository;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
            $this->error->log('TRANSACTION_ADD', session('user'), $exception->getMessage());
            return back()
                ->withInput()
                ->with('error', 'We are having technical issue, Please contact your Administrator to fix this!');
        }
    }

    public function update($transaction, $request)
    {
        try {
             $transaction->update($request);
             $transaction->fresh();

             return $transaction;
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

    public function checkout($transaction)
    {
        $transactionRepository = new TransactionRepository();
        $orderServices = new OrderServices();
        $customerRepository = new CustomerRepository();
        $customer = $customerRepository->find($transaction->customer);
        $total_amount = [];
        $total_points = [];
        DB::beginTransaction();
        try {

            $orders = $transactionRepository->getAllOrdersByTransaction($transaction->id);
            // dd($orders);
            // $testArray2 = [];
            foreach ($orders as $order) {
                array_push($total_amount, $order->total_amount);
                array_push($total_points, $order->total_points);
                $orderServices->finalizeOrder($order);
                // array_push($testArray2, $orderServices->finalizeOrder($order));
            }
            // dd($testArray2);
            $transaction->update([
                'total_points' => array_sum($total_points),
                'total_amount' => array_sum($total_amount),
                'trans_status' => 'completed'
            ]);
            //update customer total points
            $customer->update(['total_points' => ($customer->total_points + array_sum($total_points))]);
            DB::commit();
            return $transaction->fresh();
        } catch (\Exception $exception) {
            // dd($exception->getMessage());
            DB::rollBack();
            $this->error->log('TRANSACTION_CHECKOUT', session('user'), $exception->getMessage());
            return back()
                ->with('error', 'We are having technical issue, Please contact your Administrator to fix this!');
        }
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

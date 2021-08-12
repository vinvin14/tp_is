<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ClaimTypeRepository;
use App\Http\Repositories\CustomerRepository;
use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\PaymentMethodRepository;
use App\Http\Repositories\TransactionRepository;
use App\Http\Requests\TransactionPostRequest;
use App\Http\Services\OrderServicesOld;
use App\Http\Services\TransactionServices;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\PaymentType;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(TransactionRepository $repository)
    {
        $transactions = $repository->all();
        return view('shop.transaction.index')
            ->with('page', 'shop')
            ->with(compact('transactions'));
    }

    public function show(Transaction $transaction, TransactionRepository $repository, OrderRepository $orderRepository)
    {
        $currentTransaction = $repository->getTransWithCus($transaction->id);
        return view('shop.transaction.show')
            ->with('page', 'shop')
            ->with('transaction', $currentTransaction);
    }

    public function create(CustomerRepository $customerRepository, PaymentMethodRepository $paymentMethodRepository, ClaimTypeRepository $claimTypeRepository)
    {
        $customers = $customerRepository->allSortByCreated();
        $claimTypes = $claimTypeRepository->all();
        $paymentMethods = $paymentMethodRepository->all();
        return view('shop.transaction.create')
            ->with(compact('customers'))
            ->with(compact('claimTypes'))
            ->with(compact('paymentMethods'))
            ->with('page', 'shop');
    }

    public function store(TransactionPostRequest $request, TransactionServices $transactionServices)
    {
        $request = $request->only('customer', 'transaction_date', 'claim_type', 'trans_status', 'payment_method_id', 'remarks');
        $request['ticket_number'] = $transactionServices->createTicket();
        $new_transaction = $transactionServices->create($request);
        return redirect(route('transaction.show', $new_transaction->id))
            ->with('response', 'Transaction successfully created!');
    }

    public function update(Transaction $transaction)
    {
        return view('shop.transaction.update')
            ->with(compact('transaction'));
    }

    public function upsave(TransactionPostRequest $request, Transaction $transaction, TransactionServices $transactionServices)
    {
        $transaction = $transactionServices->update($transaction, $request->post());
        return redirect(route('transaction.show', $transaction->id))
            ->with('response', 'Transaction record has been successfully updated!');
    }

    public function destroy(Transaction $transaction, TransactionServices $transactionServices)
    {
        $transactionServices->delete($transaction);
        return redirect(route('transaction.index'))
            ->with('response', 'Transaction Record has been deleted!');
    }

    public function checkout($transaction_id, OrderRepository $orderRepository, TransactionRepository $transactionRepository)
    {
        $transaction = $transactionRepository->getTransWithCus($transaction_id);
        $orders = $orderRepository->getOrdersByTrans($transaction_id);
        $orderTotalAmount = $orderRepository->getTotalOrderAmountByTransaction($transaction_id);
        $paymentTypes = PaymentType::query()->orderBy('type_name', 'ASC')->get()->toArray();
        return view('shop.transaction.checkout')
            ->with(compact('transaction'))
            ->with(compact('orders'))
            ->with(compact('orderTotalAmount'))
            ->with(compact('paymentTypes'))
            ->with('page', 'shop');

    }

    public function finalize(Transaction $transaction, TransactionServices $transactionServices)
    {

    }
}

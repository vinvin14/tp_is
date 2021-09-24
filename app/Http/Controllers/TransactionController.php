<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CategoryRepository;
use App\Http\Repositories\ClaimTypeRepository;
use App\Http\Repositories\CustomerRepository;
use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\PaymentMethodRepository;
use App\Http\Repositories\TransactionRepository;
use App\Http\Requests\TransactionPostRequest;
use App\Http\Services\TransactionServices;
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

    public function show($id, TransactionRepository $transactionRepository, OrderRepository $orderRepository, CategoryRepository $categoryRepository)
    {
        return view('shop.transaction.show')
            ->with('page', 'shop')
            ->with('categories', $categoryRepository->all())
            ->with('transaction', $transactionRepository->getTransWithCus($id))
            ->with('orders', $orderRepository->getOrderByTransaction($id))
            ->with('total_order_amount', $transactionRepository->getTotalOrderAmount($id));
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
            ->with('success', 'Transaction successfully created!');
    }

    public function update($transaction,TransactionRepository $transactionRepository, ClaimTypeRepository $claimTypeRepository, PaymentMethodRepository $paymentMethodRepository)
    {
        return view('shop.transaction.update')
            ->with('claimTypes', $claimTypeRepository->all())
            ->with('paymentMethods', $paymentMethodRepository->all())
            ->with('transaction', $transactionRepository->getTransWithCus($transaction))
            ->with('page', 'shop');
    }

    public function upsave(Transaction $transaction, Request $request, TransactionServices $transactionServices)
    {
        $init = $transactionServices->update($transaction, $request->only('transaction_date', 'payment_method_id', 'claim_type', 'remarks'));
        if (@$init['error']) {
            return back()
            ->with('error', $init['error']);
        }

        return redirect(route('transaction.show', $transaction->id))
            ->with('success', 'Transaction record has been successfully updated!');
    }

    public function destroy(Transaction $transaction, TransactionServices $transactionServices)
    {
        $transactionServices->delete($transaction);
        return redirect(route('transaction.index'))
            ->with('success', 'Transaction Record has been deleted!');
    }

    public function checkout(Transaction $transaction, TransactionServices $transactionServices)
    {
        $init = $transactionServices->checkout($transaction);
        return redirect(route('transaction.show', $transaction->id))
        ->with('success', 'Transaction successfully placed!');

    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CategoryRepository;
use App\Http\Repositories\ClaimTypeRepository;
use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\PaymentMethodRepository;
use App\Http\Repositories\TransactionRepository;
use App\Http\Repositories\WalkinTransactionRepository;
use App\Http\Services\WalkinTransactionServices;
use App\Models\Transaction;
use Illuminate\Http\Request;

class WalkInTransactionController extends Controller
{
    public function index(WalkinTransactionRepository $walkinTransactionRepository)
    {
        return view('shop.walkinTransaction.index')
        ->with('walkinTransactions', $walkinTransactionRepository->all())
        ->with('page', 'shop');
    }

    public function show($id, TransactionRepository $transactionRepository, OrderRepository $orderRepository, CategoryRepository $categoryRepository)
    {
        return view('shop.walkinTransaction.show')
        ->with('transaction', $transactionRepository->getTransactionWithJoin($id))
        ->with('orders', $orderRepository->getOrderByTransaction($id))
        ->with('categories', $categoryRepository->all())
        ->with('total_order_amount', $transactionRepository->getTotalOrderAmount($id))
        ->with('page', 'shop');
    }

    public function create(ClaimTypeRepository $claimTypeRepository, PaymentMethodRepository $paymentMethodRepository)
    {
        return view('shop.walkinTransaction.create')
        ->with('claimTypes', $claimTypeRepository->all())
        ->with('paymentMethods', $paymentMethodRepository->all())
        ->with('page', 'shop');
    }

    public function store(Request $request, WalkinTransactionServices $walkinTransactionServices)
    {
        $request['ticket_number'] = $walkinTransactionServices->createWalkinTicket();
        $init = $walkinTransactionServices->create($request->only('transaction_date', 'trans_status', 'claim_type', 'payment_method_id', 'remarks', 'ticket_number'));

        if (@$init['error']) {
            return back()
            ->with('error', $init['error']);
        }

        return redirect(route('walkinTransaction.show', $init->id))
        ->with('success', 'Walk-in Transaction has been successfully created!');
    }

    public function edit(Transaction $transaction)
    {
        return view('shop.walkinTransaction.update')
        ->with(compact('transaction'))
        ->with('page', 'shop');
    }

    public function update(Transaction $transaction, Request $request, WalkinTransactionServices $walkinTransactionServices)
    {
        $init = $walkinTransactionServices->update($transaction, $request);

        if (@$init['error']) {
            return back()
            ->with('error', $init['error']);
        }

        return redirect(route('walkinTransaction.show', $init->id))
        ->with('success', 'Transaction has been successfully updated!');
    }

    public function destroy(Transaction $transaction, WalkinTransactionServices $walkinTransactionServices)
    {
        $init = $walkinTransactionServices->delete($transaction);

        if (@$init['error']) {
            return back()
            ->with('error', $init['error']);
        }

        return redirect(route('walkinTransaction.list'))
        ->with('success', 'Transaction has been deleted!');
    }

    public function checkout(Transaction $transaction, WalkinTransactionServices $walkinTransactionServices)
    {
        $init = $walkinTransactionServices->checkout($transaction);

        if (@$init['error']) {
            return back()
            ->with('error', $init['error']);
        }

        return redirect(route('walkinTransaction.show', $transaction->id))
        ->with('success', 'Transaction has been completed!');
    }
}

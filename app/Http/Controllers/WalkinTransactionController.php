<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ClaimTypeRepository;
use App\Http\Repositories\PaymentMethodRepository;
use App\Http\Repositories\WalkinTransactionRepository;
use App\Http\Services\WalkinTransactionServices;
use App\Models\Transaction;
use Illuminate\Http\Request;

class WalkInTransactionController extends Controller
{
    public function index(WalkinTransactionRepository $walkinTransactionRepository)
    {
        return view('walkinTransaction.index')
        ->with('walkinTransactions', $walkinTransactionRepository->all())
        ->with('page', 'shop');
    }

    public function show()
    {
        return view('walkin.show')
        ->with('page', 'shop');
    }

    public function create(ClaimTypeRepository $claimTypeRepository, PaymentMethodRepository $paymentMethodRepository)
    {
        return view('walkinTransaction.create')
        ->with('claimTypes', $claimTypeRepository->all())
        ->with('paymentMethods', $paymentMethodRepository->all())
        ->with('page', 'shop');
    }

    public function store(Request $request, WalkinTransactionServices $walkinTransactionServices)
    {
        $init = $walkinTransactionServices->create($request->only('transaction_date', 'trans_status', 'claim_type', 'payment_method_id', 'remarks', 'isWalkin'));

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
}

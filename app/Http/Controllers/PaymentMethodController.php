<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PaymentMethodRepository;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Services\PaymentMethodServices;

class PaymentMethodController extends Controller
{
    public function index(PaymentMethodRepository $paymentMethodRepository)
    {
        $paymentMethods = $paymentMethodRepository->all();
        return $paymentMethods;
        return view('reference.paymentMethod.index')
        ->with('page', 'reference');
    }

    public function create()
    {
        return view('reference.paymentMethod.create')
        ->with('page', 'reference');
    }

    public function store(Request $request, PaymentMethodServices $paymentMethodServices)
    {
        $init = $paymentMethodServices->create($request->only('name'));

        if(@$init['error']) {
            return back()
            ->with('error', $init['error'])
            ->withInput();
        }

        return redirect(route('paymentMethod.list'))
        ->with('success', "$init->name has been successfully added!");
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('reference.paymentmethod.edit')
        ->with(compact('paymentMethod'));
    }

    public function update(PaymentMethod $paymentMethod, Request $request, PaymentMethodServices $paymentMethodServices)
    {
        $init = $paymentMethodServices->update($paymentMethod, $request->only('name'));

        if(@$init['error']) {
            return back()
            ->with('error', $init['error']);
        }

        return redirect(route('paymentMethod.list'))
        ->with('sucess', "$init->name has been successfully updated!");
    }

    public function destroy(PaymentMethod $paymentMethod, PaymentMethodServices $paymentMethodServices)
    {
        $init = $paymentMethodServices->delete($paymentMethod);

        if(@$init['error']) {
            return back()
            ->with('error', $init['error']);
        }

        return redirect(route('paymentMethod.list'))
        ->with('success', 'Payment method has been successfully deleted!');
    }

}

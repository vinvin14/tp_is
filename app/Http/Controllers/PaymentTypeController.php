<?php

namespace App\Http\Controllers;

use App\Http\Services\ErrorRecordServices;
use App\Models\PaymentType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class PaymentTypeController extends Controller
{
    public function index()
    {
        $paymentTypes = PaymentType::query()
            ->orderBy('type_name', 'ASC')
            ->get();
        return view('admin.reference.paymentType.index')
            ->with('page', 'reference')
            ->with(compact('paymentTypes'));
    }

    public function create()
    {
        return view('admin.reference.paymentType.create')
            ->with('page', 'reference');
    }

    public function store(Request $request, ErrorRecordServices $errorRecordServices)
    {
        if ($this->checkDuplicates($request->input('type_name'))) {
            return back()
                ->with('error', 'This Payment Type has been added previously!');
        }

        try {
            $request = $request->except('_token');
            PaymentType::query()
                ->create($request);
            return redirect(route('paymentType.list'))
                ->with('response', 'Payment Type has been successfully added!');
        } catch (\Exception $exception) {
            $errorRecordServices->log('PAYMENT_TYPE_ADD', session('user'), $exception->getMessage());
            return back()
                ->withInput()
                ->with('error', 'We are having Technical Problem, Please contact your administrator!');
        }
    }

    public function update(PaymentType $paymentType)
    {
        return view('admin.reference.paymentType.update')
            ->with('page', 'reference')
            ->with(compact('paymentType'));
    }

    public function upsave(PaymentType $paymentType, Request $request, ErrorRecordServices $errorRecordServices)
    {
        if ($paymentType != $request->input('type_name')) {
            if ($this->checkDuplicates($request->input('type_name'))) {
                return back()
                    ->with('error', 'Request denied, this action will cause duplication!');
            }
        }
        try {
            $paymentType->update($request->except('_token'));
            return redirect(route('paymentType.list'))
                ->with('response', "$paymentType->type_name has been successfully updated!");
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            $errorRecordServices->log('PAYMENT_TYPE_UPDATE', session('user'), $exception->getMessage());
            return back()
                ->withInput()
                ->with('error', 'We are having Technical Problem, Please contact your administrator!');
        }
    }

    public function destroy(PaymentType $paymentType)
    {
        if (Transaction::query()->where('payment_type', $paymentType->id)->first()) {
            return back()
                ->with('error', 'We cannot delete this Payment Type Record since it has been referenced by an existing transaction');
        }

        $paymentType->delete();
        return redirect(route('paymentType.list'))
            ->with('response', 'Payment Type Record has been deleted!');
    }

    public function checkDuplicates($paymentType)
    {
        $paymentType = PaymentType::query()
            ->where('type_name', $paymentType)
            ->first();

        if($paymentType) {
            return true;
        }
        return false;
    }
}

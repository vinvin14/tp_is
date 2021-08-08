<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CustomerRepository;
use App\Http\Repositories\TransactionRepository;
use App\Http\Requests\CustomerPostRequest;
use App\Http\Services\CustomerServices;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(CustomerRepository $customerRepository)
    {
        $customers = $customerRepository->all();
        return view('customer.index')
                    ->with(compact('customers'))
                    ->with('page', 'customer');
    }
    public function show(Customer $customer, TransactionRepository $transactionRepository)
    {
        $transactions = $transactionRepository->getTransByCus($customer->id);
        return view('customer.show')
                    ->with(compact('customer'))
                    ->with(compact('transactions'))
                    ->with('page', 'customer');
    }
    public function create()
    {
        return view('customer.create')
            ->with('page', 'customer');
    }
    public function store(CustomerPostRequest $request, CustomerServices $customerServices)
    {
        $customer = $customerServices->create($request->post());
        return redirect(route('customer.show', $customer->id))
                    ->with('response', "Customer $customer->firstname $customer->lastname has been added in our records!");
    }
    public function update($id, CustomerRepository $customerRepository)
    {
        $customer = $customerRepository->find($id);
        return view('customer.update')
                    ->with(compact('customer'))
                    ->with('page', 'customer');
    }
    public function upsave($id, CustomerPostRequest $request, CustomerServices $customerServices)
    {
        $customer = $customerServices->update($id, $request->except('_token'));
        return redirect(route('customer.show', $id))
                    ->with('response', "$customer->firstname $customer->lastname's record has been updated!");
    }
    public function destroy($id, CustomerServices $customerServices)
    {
        $customerServices->delete($id);
        return redirect(route('customer.index'))
                    ->with('response', 'Customer record has been deleted!');
    }
    public function getPoints($id)
    {
        $customer_points = Customer::query()->findOrFail($id)->points;
        return view('customers.points')
                    ->with(compact('customer_points'));
    }
}

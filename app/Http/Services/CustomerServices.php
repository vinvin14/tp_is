<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 25/05/2021
 * Time: 6:38 PM
 */

namespace App\Http\Services;


use App\Http\Repositories\CustomerRepository;

class CustomerServices
{
    private $customerRepository, $error;
    public function __construct()
    {
        $this->customerRepository = new CustomerRepository();
        $this->error = new ErrorRecordServices();
    }

    public function create($request)
    {
        if ($this->customerRepository->customerExists($request['firstname'], $request['middlename'], $request['lastname'], $request['date_of_birth'])) {
            return back()
                ->withInput()
                ->with('error','Customer is already existing!');
        }
        try {
            return $this->customerRepository->create($request);
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            $this->error->log('CUSTOMER_ADD', session('user'), $exception->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Something went wrong, Please contact your Administrator!');
        }
    }
    public function update($id, $request)
    {
        if ($this->customerRepository->hasDuplicateName($id, $request['firstname'], $request['middlename'], $request['lastname'])) {
            return back()->with('error', 'Request denied, the changes you want to apply will cause duplication!');
        }

        try {
            return $this->customerRepository->update($id, $request);
        } catch (\Exception $exception) {
            $this->error->log('CUSTOMER_UPDATE', session('user'), $exception->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Something went wrong, Please contact your Administrator!');
        }
    }
    public function delete($id)
    {
        if ($this->customerRepository->hasTransactions($id)) {
           return back()->with('error', 'Customer Record cannot be deleted since it has existing transactions!');
        }
        try {
            $customer = $this->customerRepository->find($id);
            $customer->delete();
            return redirect(route('customer.index'))
                ->with('response', 'Customer Record successfully deleted!');
        } catch (\Exception $exception) {
            $this->error->log('CUSTOMER_DELETE', session('user'), $exception->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Something went wrong, Please contact your Administrator!');
        }
    }
}

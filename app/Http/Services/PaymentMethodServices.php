<?php
namespace App\Http\Services;

use App\Http\Services\ErrorRecordServices;
use App\Models\PaymentMethod;
use Exception;
use Illuminate\Support\Facades\Validator;

class PaymentMethodServices 
{
    private $error;
    public function __construct()
    {
        $this->error = new ErrorRecordServices();
    }
    public function create($request)
    {
        try {
            $validator = Validator::make($request, [
                'name' => 'unique:payment_method'
            ]);
            if ($validator->fails()) {
                return ['error' => $request['name'].' is already taken!'];
            }

            return PaymentMethod::query()
            ->create($request);
        } catch (Exception $exception) {
            $this->error->log('PAYMENT_METHOD_ADD', session('user'), $exception->getMessage());
            return ['error' => 'We are encountering technical problem, Please contact your Administrator!'];
        }
    }

    public function update($paymentMethod, $request)
    {
        if ($paymentMethod->name != $request['name']) {
            $validator = Validator::make($request, [
                'name' => 'unique:payment_method'
            ]);
            if ($validator->fails()) {
                return ['error' => $request['name'].' is already taken and will cause duplication!'];
            }
        }
        try {
            return $paymentMethod->update($request);
        } catch (Exception $exception) {
            $this->error->log('PAYMENT_METHOD_UPDATE', session('user'), $exception->getMessage());
            return ['error' => 'We are encountering technical problem, Please contact your Administrator!'];
        }
    }

    public function delete($paymentMethod)
    {
        try {
            $paymentMethod->delete();
        } catch (Exception $exception) {
            $this->error->log('PAYMENT_METHOD_DELETE', session('user'), $exception->getMessage());
            return ['error' => 'We are encountering technical problem, Please contact your Administrator!'];
        }
    }
}
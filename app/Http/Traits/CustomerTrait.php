<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 10/04/2021
 * Time: 9:47 PM
 */

namespace App\Http\Traits;


use App\Models\Customer;
use App\Models\Transaction;

trait CustomerTrait
{
    function if_customer_exist($firstname, $middlename, $lastname, $address)
    {
        $duplicate = Customer::query()
                                ->where(
                                    [
                                        'firstname' => $firstname,
                                        'middlename' => $middlename,
                                        'lastname' => $lastname
                                    ]
                                )->first();
        if($duplicate) {
            return true;
        }
        return false;
    }
    function if_customer_has_transaction($customer_id)
    {
        $transactions = Transaction::query()->whereCustomer($customer_id)->get();
        if($transactions) {
            return true;
        }
        return false;
    }
    function process_points($customer_id, $points, $option)
    {
        $customer = Customer::query()->findOrFail($customer_id);
        $new_points = 0;
        switch ($option) {
            case 'add':
                $new_points = doubleval($points) + doubleval($customer->points);
                break;
            case 'deduct':
                $new_points = doubleval($customer->points) - doubleval($points) ;
                break;
        }
        $customer->update(['points' => $new_points]);
        $customer->refresh();
        return $customer->points;
    }
}

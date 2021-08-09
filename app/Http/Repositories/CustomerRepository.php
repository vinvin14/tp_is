<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 25/05/2021
 * Time: 6:30 PM
 */

namespace App\Http\Repositories;


use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class CustomerRepository
{
    public function all()
    {
        return Customer::query()
            ->orderBy('firstname', 'ASC')
            ->get();
    }

    public function allBy100()
    {
        return Customer::query()
            ->orderBy('firstname', 'ASC')
            ->limit(500)
            ->get();
    }

    public function find($id)
    {
        return Customer::query()
            ->findOrFail($id);
    }

    public function customerWithTrans()
    {
        return DB::table('customers')
            ->leftJoin('transactions', 'customers.id', '=', 'transactions.customer')
            ->get();
    }

    public function create($request)
    {
        return Customer::query()
            ->create($this->format($request));
    }

    public function update($id, $request)
    {
        $customer = $this->find($id);
        $customer->update($this->format($request));
        return $customer->fresh();
    }
    public function hasTransactions($customer_id)
    {
        return Transaction::query()
            ->where('customer', $customer_id)
            ->first();
    }
    public function customerExists($firstname, $middlename, $lastname, $dob)
    {
        return Customer::query()
            ->where(
                [
                    'firstname' => $firstname,
                    'middlename' => $middlename,
                    'lastname' => $lastname,
                    'date_of_birth' => $dob

                ]
            )
            ->first();
    }
    public function hasDuplicateName($id, $firstname, $middlename, $lastname)
    {
        $customer = $this->find($id);
        if ($customer->firstname != $firstname && $customer->middlename != $middlename && $customer->lastname != $lastname) {
            if( Customer::query()
                ->where(
                    [
                        'firstname' => $firstname,
                        'middlename' => $middlename,
                        'lastname' => $lastname,
                    ]
                )
                ->first()) {
                return true;
            } else {
                return false;
            }

        }
        return false;
    }
    public function format($data)
    {
        if ($data['firstname']) {
            $data['firstname'] = ucfirst($data['firstname']);
        }
        if ($data['middlename']) {
            $data['middlename'] = ucfirst($data['middlename']);
        }
        if ($data['lastname']) {
            $data['lastname'] = ucfirst($data['lastname']);
        }
        if ($data['address']) {
            $data['address'] = ucfirst($data['address']);
        }
        return $data;
    }
}

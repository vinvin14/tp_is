<?php

namespace App\Http\Services;

use App\Models\ClaimType;
use App\Models\Transaction;
use Exception;

class ClaimTypeServices
{
    private $error;
    public function __construct()
    {
        $this->error = new ErrorRecordServices();
    }
    public function create($request)
    {
        try {
           return ClaimType::query()
            ->create($request);
        } catch (Exception $exception) {
            $this->error->log('CLAIM_TYPE_ADD', session('user'), $exception->getMessage());
            return ['error' => 'Something went wrong, Please contact your Administrator!'];
        }
    }

    public function update($claimType, $request)
    {
        if ($claimType->name != $request['name']) {
            if(ClaimType::query()->whereName($request['name'])->first()) {
                return ['error' => $request['name']. 'has been added previously!'];
            }
        }
        try {
            $claimType->update($request);
            return $claimType->fresh();
        } catch (Exception $exception) {
            $this->error->log('CLAIM_TYPE_UPDATE', session('user'), $exception->getMessage());
            return ['error' => 'Something went wrong, Please contact your Administrator!'];
        }
    }

    public function delete($claimType)
    {
        if (Transaction::query()->where('claim_type', $claimType->id)->first()) {
            return ['error' => 'Claim Type record cannot be deleted since it has been referenced by other record!'];
        }
        try {
            $claimType->delete();
        } catch (Exception $exception) {
            $this->error->log('CLAIM_TYPE_DELETE', session('user'), $exception->getMessage());
            return ['error' => 'Something went wrong, Please contact your Administrator!'];
        }
    }
}

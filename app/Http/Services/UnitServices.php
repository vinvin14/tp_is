<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 06/16/2021
 * Time: 5:47 PM
 */

namespace App\Http\Services;


use App\Models\Product;
use App\Models\Unit;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class UnitServices
{
    private $error;

    public function __construct()
    {
        $this->error = new ErrorRecordServices();
    }

    public function create($request)
    {
        $validator = Validator::make($request, [
            'unit_name' => 'required|unique:units'
        ]);
        if ($validator->fails()) {
            return ['error' => 'Unit has been added previously!'];
        }

        try {
            $request = $this->format($request);
            return Unit::query()
                ->create($request);
        } catch (\Exception $exception) {
            $this->error->log('UNIT_ADD', session('user'), $exception->getMessage());
            return ['error' => 'We are having technical issue, Please contact your Administrator!'];
        }
    }

    public function update($unit, $request)
    {
        try {
            $request = $this->format($request);

            $unit->unit_name = $request['unit_name'];
            $unit->second_level_unit = $request['second_level_unit'];

            if(! $unit->isDirty()) {
                return ['error' => 'No changes were made!'];
            }
            if ($request['unit_name'] != $unit->unit_name) {
                $validator = Validator::make($request, [
                    'unit_name' => 'required|unique:units'
                ]);
                if ($validator->fails()) {
                    return ['error' => 'Unit has been added previously!'];
                }
            }
            $unit->save();
            return $unit->fresh();
        } catch (\Exception $exception) {
            $this->error->log('UNIT_UPDATE', session('user'), $exception->getMessage());
            return ['error' => 'We are having technical issue, Please contact your Administrator!'];
        }
    }

    public function delete($unit)
    {
        try {
            if (! empty(Product::query()->where('unit', $unit->id)->first())) {
                return ['error', 'Unit record cannot be deleted since it has been referenced!'];
            }
            $unit->delete();
        } catch (\Exception $exception) {
            $this->error->log('UNIT_DELETE', session('user'), $exception->getMessage());
            return ['error' => 'We are having technical issue, Please contact your Administrator!'];
        }
    }

    public function format($data)
    {
        if($data['unit_name']) {
            $data['unit_name'] = strtolower($data['unit_name']);
        }

        return $data;
    }

}

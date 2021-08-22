<?php

namespace App\Http\Services;

use App\Models\PreOrder;
use App\Models\SoldProduct;
use App\Models\Stock;
use Exception;
use Illuminate\Support\Facades\DB;

class StockServices
{
    private $error;
    public function __construct()
    {
        $this->error = new ErrorRecordServices();
    }

    public function create($request)
    {
        $productServices = new ProductServices();
        DB::beginTransaction();
        try {
            $productServices->updateCurrentQty($request['product_id'], $request['qty']);
            $stock = Stock::query()
            ->create($request);
            DB::commit();
            return $stock;
        } catch (Exception $exception) {
            $this->error->log('STOCK_ADD', session('user'), $exception->getMessage());
            return ['error' => 'We are having technical problem, Please contact your Administrator!'];
        }
    }

    public function update($stock, $request)
    {
        try {
            $stock->update($request);
        } catch (Exception $exception) {
            $this->error->log('STOCK_ADD', session('user'), $exception->getMessage());
            return ['error' => 'We are having technical problem, Please contact your Administrator!'];
        }
    }

    public function delete($stock)
    {
        if (SoldProduct::query()->find($stock->id)) {
            return ['error' => 'Cannot delete this record since it has been referenced by existing transaction'];
        }

        try {
            $stock->delete();
        } catch (Exception $exception) {
             $this->error->log('STOCK_DELETE', session('user'), $exception->getMessage());
            return ['error' => 'We are having technical problem, Please contact your Administrator!'];
        }
    }
}

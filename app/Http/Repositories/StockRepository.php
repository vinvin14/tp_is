<?php

namespace App\Http\Repositories;

use App\Models\SoldProduct;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class StockRepository
{
    public function getStockById($id)
    {
        return Stock::query()
        ->find($id);
    }

    public function getCurrentQty($product_id)
    {
        return DB::table('stocks')
        ->leftJoin('products', 'stocks.product_id', '=', 'products.id')
        ->where('stocks.product_id', $product_id)
        ->selectRaw('sum(qty) as current_qty')
        ->groupBy('stocks.product_id')
        ->get();
    }

    public function getStocksByProduct($product_id)
    {
        return Stock::query()
        ->where('product_id', $product_id)
        ->orderBy('expiration_date', 'ASC')
        ->get();
    }

    public function getAvailableStock($product_id)
    {
        return DB::table('stocks')
        ->where('product_id', $product_id)
        ->orderBy('expiration_date', 'ASC')
        ->first();
    }

    public function isStockReferenced($stock_id)
    {
        $isReferenced = SoldProduct::query()
        ->where('stock_id', $stock_id)
        ->first();

        if (! empty($isReferenced))
        {
            return true;
        }
        return false;
    }


}

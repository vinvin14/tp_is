<?php

namespace App\Http\Repositories;

use App\Models\Stock;
use Illuminate\Support\Facades\DB;

class StockRepository
{   
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
}

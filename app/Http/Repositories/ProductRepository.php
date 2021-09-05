<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 05/31/2021
 * Time: 7:28 PM
 */

namespace App\Http\Repositories;


use App\Models\ProductQuantity;
use App\Models\Order;
use App\Models\ProductOld;
use App\Models\Product;
use App\Models\ProductTracker;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;

class ProductRepository
{
    public function all()
    {
        return DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('units', 'products.unit_id', '=', 'units.id')
            ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
            ->select(
                'products.id',
                'products.title',
                'products.uploaded_img',
                'products.alert_level',
                'products.points',
                'products.price',
                'categories.name as category',
                'units.name as unit',
                DB::raw('sum(CASE WHEN stocks.expiration_date > '.Carbon::now()->toDateString().' THEN stocks.qty END) as qty')
            )
            // ->whereDate('stocks.expiration_date', '>', Carbon::now())
            ->groupBy(
                'products.id',
                'products.title',
                'products.uploaded_img',
                'products.alert_level',
                'products.points',
                'products.price',
                'stocks.product_id',
                'categories.name',
                'units.name'
                )
            ->get();
    }

    public function allWithPaginate($page)
    {
        return DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('units', 'products.unit_id', '=', 'units.id')
            ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
            ->select(
                'products.id',
                'products.title',
                'products.uploaded_img',
                'products.alert_level',
                'products.points',
                'products.price',
                'categories.name as category',
                'units.name as unit',
                DB::raw('sum(CASE WHEN stocks.expiration_date > '.Carbon::now()->toDateString().' THEN stocks.qty END) as qty')
            )
            // ->whereDate('stocks.expiration_date', '>', Carbon::now())
            ->groupBy(
                'products.id',
                'products.title',
                'products.uploaded_img',
                'products.alert_level',
                'products.points',
                'products.price',
                'stocks.product_id',
                'categories.name',
                'units.name'
                )
            ->paginate($page);
    }

    public function allByCategoryWithPaginate($category, $page)
    {
        return DB::table('products')
            ->leftJoin('units', 'products.unit_id', '=', 'units.id')
            ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
            ->select(
                'products.id',
                'products.title',
                'products.uploaded_img',
                'products.price',
                'units.name as unit',
                DB::raw('sum(CASE WHEN stocks.expiration_date > '.Carbon::now()->toDateString().' THEN stocks.qty END) as qty')
            )
            ->where('products.category_id', $category)
            ->groupBy(
                'products.id',
                'products.title',
                'products.uploaded_img',
                'products.price',
                'stocks.product_id',
                'units.name'
                )
            ->paginate($page);
    }

    public function getProductRemainingQty($product_id)
    {
        $orders = DB::table('orders')
        ->leftJoin('transactions', 'orders.transaction_id', '=', 'transactions.id')
        ->select(
            'orders.id',
            'orders.product_id',
            'orders.qty'
        )
        ->where('transactions.trans_status', '!=', 'completed');

        return DB::table('products')
            ->leftJoin('units', 'products.unit_id', '=', 'units.id')
            ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
            ->leftJoinSub($orders, 'orders', function ($join) {
                $join->on('products.id', '=', 'orders.product_id');
            })
            ->select(
                DB::raw('(sum(CASE WHEN stocks.expiration_date > '.Carbon::now()->toDateString().' THEN stocks.qty END) - IF(orders.qty IS NULL, 0, orders.qty)) as remainingQty'),
            )
            ->where('products.id', $product_id)
            ->groupBy(
                'products.id',
                )
            ->having('remainingQty', '!=', 0)
            ->first();
    }

    public function getProductRemainingForUpdate($product_id, $transaction_id)
    {
        $orders = DB::table('orders')
        ->leftJoin('transactions', 'orders.transaction_id', '=', 'transactions.id')
        ->select(
            'orders.id',
            'orders.product_id',
            'orders.qty'
        )
        ->where('transactions.trans_status', '!=', 'completed')
        ->where('transactions.id', '!=', $transaction_id);

        return DB::table('products')
            ->leftJoin('units', 'products.unit_id', '=', 'units.id')
            ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
            ->leftJoinSub($orders, 'orders', function ($join) {
                $join->on('products.id', '=', 'orders.product_id');
            })
            ->select(
                DB::raw('(sum(CASE WHEN stocks.expiration_date > '.Carbon::now()->toDateString().' THEN stocks.qty END) - IF(orders.qty IS NULL, 0, orders.qty)) as remainingQty'),
            )
            ->where('products.id', $product_id)
            ->groupBy(
                'products.id',
                )
            ->having('remainingQty', '!=', 0)
            ->first();
    }

    public function allByCategory($category)
    {
        $orders = DB::table('orders')
        ->leftJoin('transactions', 'orders.transaction_id', '=', 'transactions.id')
        ->select(
            'orders.id',
            'orders.product_id',
            'orders.qty'
        )
        ->where('transactions.trans_status', '!=', 'completed');

        return DB::table('products')
            ->leftJoin('units', 'products.unit_id', '=', 'units.id')
            ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
            ->leftJoinSub($orders, 'orders', function ($join) {
                $join->on('products.id', '=', 'orders.product_id');
            })
            ->select(
                'stocks.qty',
                'products.id',
                'stocks.id as stock_id',
                'products.title',
                'products.uploaded_img',
                'products.price',
                'products.points',
                'units.name as unit',
                DB::raw('(sum(CASE WHEN stocks.expiration_date > '.Carbon::now()->toDateString().' THEN stocks.qty END) - IF(orders.qty IS NULL, 0, orders.qty)) as remainingQty'),
            )
            ->where('products.category_id', $category)
            // ->where('stocks.qty', '!=', 0)
            ->groupBy(
                'products.id',
                // // 'products.title',
                // // 'products.uploaded_img',
                // // 'products.price',
                // // 'products.points',
                // // 'stocks.id',
                // // 'stocks.product_id',
                // // 'units.name',
                // 'orders.qty'
                )
            ->having('remainingQty', '!=', 0)
            ->get();
    }

    public function getProductWithStocks($product_id)
    {
        return DB::table('products')
        ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
        ->leftJoin('units', 'products.unit_id', '=', 'units.id')
        ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
        ->select(
            'products.id',
            'products.title',
            'products.description',
            'products.uploaded_img',
            'products.points',
            'products.price',
            'products.alert_level',
            // 'products.initial_qty',
            'units.name as unit',
            'categories.name as category',
            DB::raw('sum(CASE WHEN stocks.expiration_date > '.Carbon::now()->toDateString().' THEN stocks.qty END) as qty'),
        )
        ->where('products.id', $product_id)
        ->groupBy(
            'products.id',
            'products.title',
            'products.description',
            'products.uploaded_img',
            'products.points',
            'products.price',
            'products.alert_level',
            // 'products.initial_qty',
            'units.name',
            'categories.name'
            )
        ->first();
    }

    public function getProductStocksQty($product_id)
    {
        return Stock::query()
        ->where('product_id', $product_id)
        ->sum('qty');
    }


    public function getPrice($product_id)
    {
        return DB::table('products')
        ->find($product_id)
        ->price;
    }

    public function getPoints($product_id)
    {
        return DB::table('products')
        ->find($product_id)
        ->points;
    }

    public function getProductWithQuantityById($id)
    {
        return DB::table('products')
            ->leftJoin('product_quantity', 'products.id', '=', 'product_quantity.product_id')
//            ->selectRaw('SUM(product_quantity.quantity) as totalQuantity')
            ->select(
                'products.*',
                'product_quantity.*'
            )
            ->whereDate('product_quantity.expiration_date','>=', date('Y-m-d') )
            ->where('products.id', $id)
            ->first();
    }

    public function getProduct($id)
    {
        return DB::table('products')
        ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
        ->leftJoin('units', 'products.unit_id', '=', 'units.id')
        ->select(
            'products.*',
            'categories.name as category',
            'units.name as unit'
        )
        ->where('products.id', $id)
        ->first();

        return Product::query()
            ->find($id);
    }

    public function isReferenced($id)
    {
        $isReferenced = Stock::query()
            ->where('product_id', $id)
            ->get()
            ->toArray();

        if ($isReferenced) {
            return true;
        }
        return false;
    }

    public function getProductQuantity($productID)
    {
        return ProductQuantity::query()
            ->where('product_id', $productID)
            ->sum('qty');
    }

    public function getPriorityProductQuantity($productID)
    {
        return ProductQuantity::query()
            ->where('product_id', $productID)
            ->where('quantity', '!=', 0)
            ->whereDate('expiration_date','<=', date('y-m-d') )
            ->orderBy('expiration_date', 'ASC')
            ->limit(1);
    }

    public function isExpired($date)
    {
        $expiry_date = strtotime($date);
        $date_now = strtotime(date('Y-m-d'));
        if ($expiry_date < $date_now) {
            return true;
        }
        return false;
    }

    public function getTopSelling()
    {
       return DB::table('products')
        ->leftJoin('orders', 'products.id', '=', 'orders.product_id')
        ->leftJoin('sold_products', 'orders.id', '=', 'sold_products.order_id')
        ->leftJoin('units', 'products.unit_id', '=', 'units.id')
        ->select(
            'orders.id',
            'products.title',
            'units.name as unit',
            'units.plural_name as unit_plural_name',
            DB::raw('SUM(sold_products.qty) as totalSale')
        )
        ->groupBy('products.id')
        ->where('sold_products.qty', '!=', null)
        ->orderBy('totalSale', 'DESC')
        ->get();
    }

    public function getTotalSale()
    {
        $month = Carbon::now()->month;

        return DB::table('products')
        ->leftJoin('orders', 'products.id', '=', 'orders.product_id')
        ->leftJoin('sold_products', 'orders.id', '=', 'sold_products.order_id')
        ->select(
            DB::raw('SUM(sold_products.qty) as totalItems'),
            DB::raw('SUM(sold_products.final_amount) as totalAmount')
        )
        ->where('sold_products.qty', '!=', 0)
        ->whereMonth('sold_products.created_at', $month)
        ->first();
    }

    public function getNearExpiryProduct()
    {
        return DB::table('products')
        ->leftJoin('stocks', 'products.id', '=', 'stocks.id')
        ->select(
            'products.id as product_id',
            'products.title as product_title',
            'stocks.id as stocks_id',
            'stocks.qty as stocks_qty'
        )
        ->whereDate('stocks.expiration_date', '<=', Carbon::now()->addDays(3))
        ->get();
    }
}

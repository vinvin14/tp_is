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
            ->where('products.category_id', $category)
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
            'units.name as unit',
            'categories.name as category',
            DB::raw('sum(CASE WHEN stocks.expiration_date > '.Carbon::now()->toDateString().' THEN stocks.qty END) as qty'),
        )
        ->where('products.id', $product_id)
        ->groupBy('products.id', 'products.title', 'products.description', 'products.uploaded_img', 'products.points', 'products.price', 'products.alert_level', 'units.name', 'categories.name')
        ->first();
    }

    public function getPrice($product_id)
    {
        return DB::table('products')
        ->find($product_id)
        ->price;
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

    public function find($id)
    {
        return DB::table('products')
            ->leftJoin('product_category', 'products.product_category', '=', 'product_category.id')
            ->leftJoin('units', 'products.unit', '=', 'units.id')
            ->select(
                'products.*',
                'product_category.id as product_category_id',
                'product_category.category_name as category_name',
                'units.id as unit_id',
                'units.unit_name as unit'
            )
            ->where('products.id', $id)
            ->first();
    }

    public function getProduct($id)
    {
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
}

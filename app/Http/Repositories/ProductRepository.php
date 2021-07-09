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
use Illuminate\Support\Facades\DB;

class ProductRepository
{
    public function all()
    {
        return DB::table('products')
            ->leftJoin('product_category', 'products.product_category', '=', 'product_category.id')
            ->leftJoin('units', 'products.unit', '=', 'units.id')
            ->select(
                'products.*',
                'product_category.category_name as category_name',
                'units.unit_name as unit'
            )
            ->get();
    }

    public function getProductsWQuantity()
    {
        return DB::table('products')
            ->leftJoin('product_category', 'products.product_category', '=', 'product_category.id')
            ->leftJoin('units', 'products.unit', '=', 'units.id')
            ->leftJoin('product_quantity', 'products.id', '=', 'product_quantity.id')
            ->selectRaw('SUM(product_quantity.quantity as total_quantity')
            ->select(
                'products.*',
                'product_category.category_name as category_name',
                'units.unit_name as unit',
                'product_quantity.expiration_date'
            )
            ->where('current_quantity', '!=', 0)
            ->whereDate('product_quantity.expiration_date','>=', date('Y-m-d') )
            ->get();
    }

    public function find($id)
    {
        $product = DB::table('products')
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
        if (empty($product)) {
            return ['error' => 'Product Record does not exist!'];
        }
        return $product;
    }

    public function getProduct($id)
    {
        return Product::query()
            ->find($id);
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

    public function create($request)
    {
        return Product::query()
            ->create($this->format($request));
    }

    public function update($product, $request)
    {
        $product->update($this->format($request));
        return $product->fresh();
    }

    public function isReferenced($id)
    {
        $isReferenced = Order::query()
            ->where('product_id', $id)
            ->get()
            ->toArray();
        $hasQuantity = ProductQuantity::query()
            ->where('product_id', $id)
            ->get()
            ->toArray();
        if ($isReferenced || $hasQuantity) {
            return true;
        }
        return false;
    }

    public function productQuantity($productID)
    {
        return ProductQuantity::query()
            ->where('product_id', $productID)
            ->get();
    }

    public function getProdTrackerByProdQuantity($prodQuantityID)
    {
        return ProductTracker::query()
            ->where('product_quantity_id', $prodQuantityID)
            ->first();
    }

    public function isProdQuantityReferenced($id)
    {
        $isReferenced = Order::query()
            ->where('product_quantity_id', $id)
            ->get()
            ->toArray();
        if ($isReferenced) {
            return true;
        }
        return false;
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

    public function format($data)
    {
        if ($data->item_title) {
            $data->item_title = ucfirst($data->item_title);
        }
        if ($data->description) {
            $data->description = ucfirst($data->description);
        }
        return $data;
    }
}

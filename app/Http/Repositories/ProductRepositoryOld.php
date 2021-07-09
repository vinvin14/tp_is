<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 07/05/2021
 * Time: 9:57 PM
 */

namespace App\Http\Repositories;


use App\Models\Order;
use App\Models\ProductOld;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductRepositoryOld
{
    public function all()
    {
        return DB::table('products')
            ->leftJoin('product_specifications', 'products.specifications_id', '=', 'product_specifications.id')
            ->leftJoin('product_category', 'product_specifications.product_category', '=', 'product_category.id')
            ->leftJoin('units', 'product_specifications.unit', '=', 'units.id')
            ->select(
                'specifications_id',
                'product_specifications.item_title',
                'product_specifications.uploaded_img',
                'product_specifications.price',
                'product_category.category_name',
                'units.unit_name as unit'

            )
            ->selectRaw('SUM(products.current_quantity) as total_quantity')
            ->groupBy('products.specifications_id', 'product_specifications.uploaded_img', 'units.unit_name', 'product_specifications.item_title', 'product_specifications.price', 'product_category.category_name')
            ->get();
    }

    public function find($id)
    {
        return ProductOld::query()->findOrFail($id);
    }

    public function findWithJoin($id)
    {
        return DB::table('products')
            ->leftJoin('product_specifications', 'products.specifications_id', '=', 'product_specifications.id')->leftJoin('units', 'product_specifications.unit', '=', 'units.id')
            ->select(
                'products.*',
                'product_specifications.item_title'
            )
            ->where('products.id', $id)
            ->first();
    }

    public function getProductBySpecsWithJoin($specification)
    {
        return DB::table('products')
            ->leftJoin('product_specifications', 'products.specifications_id', '=', 'product_specifications.id')
            ->leftJoin('product_category', 'products.product_category', '=', 'product_category.id')
            ->leftJoin('units', 'products.unit', '=', 'units.id')
            ->select(
                'products.*',
                'product_specifications.item_title as product_title',
                'product_specifications.description as desription',
                'product_specifications.upload_img as image',
                'product_category.category_name as category_name',
                'product_specifications.price as price',
                'units.unit_name as unit'
            )
            ->where('products.specifications_id', $specification)
            ->get();
    }

    public function getProductBySpecs($specification)
    {
        return ProductOld::query()
            ->where('specifications_id', $specification)
            ->get();
    }

    public function getTotalProductBySpecs($specification)
    {
        return ProductOld::query()
            ->where('specifications_id', $specification)
            ->sum('current_quantity');
    }

    public function create($product_data)
    {
        return ProductOld::query()->create($product_data);
    }

    public function update($id, $product_data)
    {
        $product = $this->find($id);
        $product->update($product_data);
        return $product->fresh();
    }

    public function checkProductWithIdExp($id, $exp_date)
    {
        return ProductOld::query()
            ->where(
                [
                    'specifications_id' => $id,
                    'expiry_date' => $exp_date
                ]
            )
            ->first();
    }

    public function hasOrder($product_id)
    {
        $checking = Order::query()
            ->where('product_id', $product_id)
            ->get()
            ->toArray();

        if ($checking) {
            return true;
        }
        return false;
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

    public function productPriority($specification_id)
    {
        return DB::table('product')
            ->leftJoin('product_specifications', 'products.specifications_id', '=', 'product_specifications.id')
            ->where('current_quantity', '!=', 0)
            ->where('specifications_id', $specification_id)
            ->select(
                'product.*',
                'product_specifications.price',
                'product_specifications.points'
            )
            ->orderBy('expiry_date', 'ASC')
            ->first();
    }

    public function getPrice($id)
    {
        return Product::query()
            ->findOrFail($id)
            ->price;
    }

    public function organize($data)
    {
        return [
            'product_id' => $data->id,
            'item_title' => $data->item_title
        ];
    }

}

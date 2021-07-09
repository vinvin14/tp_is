<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 05/31/2021
 * Time: 6:14 PM
 */

namespace App\Http\Repositories;


use App\Models\ProductCategory;
use App\Models\Product;

class ProductCategoryRepository
{
    public function all()
    {
        return ProductCategory::query()
            ->orderBy('category_name', 'ASC')
            ->get();
    }
    public function find($id)
    {
        return ProductCategory::query()
            ->findOrFail($id);
    }
    public function create($request)
    {
        return ProductCategory::query()
            ->create($request);
    }
    public function update($productCategory, $request)
    {
        $productCategory->update($request);
        return $productCategory->fresh();
    }
    //check if category has been referenced
    public function referenced($id)
    {
        $result = Product::query()
            ->where('product_category', $id)
            ->first();
        if($result) {
            return true;
        }
        return false;
    }
}

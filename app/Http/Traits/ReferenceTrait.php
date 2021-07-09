<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 02/04/2021
 * Time: 2:38 PM
 */

namespace App\Http\Traits;
use App\Models\ProductCategory;
use App\Models\Product;

trait ReferenceTrait
{
    function item_exist($item, $category)
    {
        if(Product::query()
            ->where(
                [
                    'item_title' => $item,
                    'product_category' => $category
                ]
            )->first())
        {
            return true;
        }
        return false;
    }
    function category_exist($category)
    {
        if(ProductCategory::query()->where('category_name', $category)->first()) {
            return true;
        }
        return false;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 06/04/2021
 * Time: 3:02 PM
 */

namespace App\Http\Traits;
use App\Models\ProductOld;
use App\Models\Product;
use App\Models\ProductTracker;
use Illuminate\Support\Facades\DB;

trait ProductTrait
{
    function get_product_specification($specification_id)
    {
        return Product::query()->findOrFail($specification_id);
    }
    function if_product_exist($specification_id, $expiry_date)
    {
        $duplicate = ProductOld::query()
                                ->where(
                                    [
                                        'specifications_id' => $specification_id,
                                        'expiry_date' => $expiry_date
                                    ]
                                )
                                ->first();
        if($duplicate) {
            return true;
        }
        return false;
    }
    function get_priority_product($specification_id)
    {
        return DB::table('product')
                                ->leftJoin('product_specifications', 'product.specifications_id', '=', 'product_specifications.id')
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
    function get_total_products($specification_id)
    {
        $product = DB::table('product')
                        ->where('specifications_id', $specification_id)
                        ->selectRaw('SUM(current_quantity) as total_quantity')
                        ->groupBy('specifications_id')
                        ->first();
        return intval($product->total_quantity);
    }
    function create_product_tracker($product, $order_id, $prev_quantity, $after_quantity)
    {
        ProductTracker::query()->create(
            [
                'product' => $product,
                'order' => $order_id,
                'previous_quantity' => $prev_quantity,
                'after_quantity' => $after_quantity,
            ]
        );
    }
    function inventory_create()
    {

    }
    function get_price($specifications_id)
    {
        return Product::query()->findOrFail($specifications_id)->price;
    }
}

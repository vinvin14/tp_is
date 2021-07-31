<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 05/31/2021
 * Time: 6:14 PM
 */

namespace App\Http\Repositories;


use App\Models\Category;
use App\Models\Product;

class CategoryRepository
{
    public function all()
    {
        return Category::query()
            ->orderBy('name', 'ASC')
            ->get();
    }
    public function find($id)
    {
        return Category::query()
            ->findOrFail($id);
    }

    //check if category has been referenced
    public function referenced($id)
    {
        $result = Product::query()
            ->where('category', $id)
            ->first();
        if($result) {
            return true;
        }
        return false;
    }
}

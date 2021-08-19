<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AjaxController extends Controller
{
    public function getProductByCategory($category, ProductRepository $productRepository)
    {
        $products = $productRepository->allByCategory($category);

        if (empty($products->toArray())) {
            return response()->json('No Record(s) Found!', 404);
        }

        return response()->json($products, 200);;
    }
}

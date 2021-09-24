<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ProductRepository;
use App\Models\SoldProduct;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {  
        return view('shop.sales.index')
        ->with('page', 'shop');
    }

    public function generate(Request $request, ProductRepository $productRepository)
    {   
        // dd($request->post());
        $soldProducts = $productRepository->getSoldProducts($request->input('date_from'), $request->input('date_to'));
        $totalSale = $productRepository->getTotalSaleByDate($request->input('date_from'), $request->input('date_to'));

        return view('shop.sales.generate')
        ->with('sold_products', $soldProducts)
        ->with('total_sale', $totalSale)
        ->with('date_range', [$request->input('date_from'), $request->input('date_to')])
        ->with('page', 'shop');
    }
}

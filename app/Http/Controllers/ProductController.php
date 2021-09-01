<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CategoryRepository;
use App\Http\Repositories\ProductRepository;
use App\Http\Repositories\StockRepository;
use App\Http\Repositories\UnitRepository;
use App\Http\Requests\ProductPostRequest;
use App\Http\Services\ProductServices;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $request;

    public function index(ProductRepository $productRepository)
    {
        $products = $productRepository->all();
        return view('shop.product.index')
            ->with('page', 'shop')
            ->with(compact('products'));
    }

    public function show($id, ProductRepository $productRepository, StockRepository $stockRepository)
    {
        // $product = $productRepository->getProductWithStocks($id);
        $product = $productRepository->getProduct($id);
        @$product_remaining_qty = $productRepository->getProductRemainingQty($id)->remainingQty;
        $stocks = $stockRepository->getStocksByProduct($id);
        return view('shop.product.show')
            ->with(compact('product'))
            ->with(compact('product_remaining_qty'))
            ->with(compact('stocks'))
            ->with('page', 'shop');
    }

    public function create(CategoryRepository $categoryRepository, UnitRepository $unitRepository)
    {
        $product_categories = $categoryRepository->all();
        $units = $unitRepository->all();
        return view('shop.product.create')
            ->with(compact('product_categories'))
            ->with(compact('units'))
            ->with('page', 'shop');
    }

    public function store(ProductPostRequest $request, ProductServices $productServices)
    {
        $product = $productServices->create($request->all());
        return redirect(route('product.show', $product->id))
            ->with('response', 'New Product has been successfully added!');
    }

    public function edit(Product $product, CategoryRepository $productCategoryRepository, UnitRepository $unitRepository)
    {
        $categories = $productCategoryRepository->all();
        $units = $unitRepository->all();
        return view('shop.product.edit')
            ->with(compact('product'))
            ->with(compact('categories'))
            ->with(compact('units'))
            ->with('page', 'shop');
    }

    public function update(Product $product, Request $request, ProductServices $productServices)
    {
        $productServices->update($product, $request->all());
        return redirect(route('product.show', $product->id))
            ->with('response', "$product->item_title record has been updated!");
    }

    public function destroy(Product $product, ProductServices $productServices)
    {
        $init = $productServices->delete($product);
        //check if error occurred
        if(@$init['error']) {
            return back()
                ->with('error', $init['error']);
        }
        return redirect(route('product.list'))
            ->with('response', 'Product Record has been deleted!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CategoryRepository;
use App\Http\Repositories\ProductRepository;
use App\Http\Repositories\UnitRepository;
use App\Http\Requests\ProductPostRequest;
use App\Http\Services\ProductServices;
use App\Models\ProductQuantity;
use App\Models\ProductOld;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request->except('_token');
    }

    public function index(ProductRepository $productRepository)
    {
        $products = $productRepository->all();

        return view('admin.shop.product.index')
            ->with('page', 'shop')
            ->with(compact('products'));
    }

    public function show($id, ProductRepository $productRepository)
    {
        $product = $productRepository->find($id);
        $productQuantity = $productRepository->productQuantity($id);
        return view('admin.shop.product.show')
            ->with(compact('product'))
            ->with(compact('productQuantity'))
            ->with('page', 'shop');
    }

    public function create(CategoryRepository $productCategoryRepository, UnitRepository $unitRepository)
    {
        $product_categories = $productCategoryRepository->all();
        $units = $unitRepository->all();
        return view('admin.shop.product.create')
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

    public function update(Product $product, CategoryRepository $productCategoryRepository, UnitRepository $unitRepository)
    {
        $product_categories = $productCategoryRepository->all();
        $units = $unitRepository->all();
        return view('admin.reference.specification.update')
            ->with(compact('product'))
            ->with(compact('product_categories'))
            ->with(compact('units'))
            ->with('page', 'shop');
    }

    public function upsave(Product $product, Request $request, ProductServices $productServices)
    {
        $productServices->update($product, $request->all());
        return redirect(route('specification.show', $product->id))
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

<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ProductRepositoryOld;
use App\Http\Repositories\ProductRepository;
use App\Http\Services\ProductServicesOld;
use App\Http\Traits\ErrorTrait;
use App\Http\Traits\ProductTrait;
use App\Models\ProductOld;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductControllerOld extends Controller
{
    use ErrorTrait, ProductTrait;
    private $request, $productRepository;

    public function __construct(Request $request)
    {
        $this->request = $request->except('_token');
    }

    public function index(ProductRepositoryOld $productRepository)
    {
        $products = $productRepository->all();
        return view('admin.shop.product.index')
            ->with('page', 'shop')
            ->with(compact('products'));
    }

    public function showCollection($id, ProductRepositoryOld $productRepository, ProductRepository $productSpecificationRepository)
    {
        $specification = $productSpecificationRepository->find($id);
        $products = $productRepository->getProductBySpecs($id);
        $productsCount = $productRepository->getTotalProductBySpecs($id);
        return view('admin.shop.product.collection')
            ->with('page', 'shop')
            ->with(compact('products'))
            ->with(compact('productsCount'))
            ->with(compact('specification'));
    }

    public function create(ProductRepository $productSpecificationRepository)
    {
        $specifications = $productSpecificationRepository->all();
        return view('admin.shop.product.create')
            ->with(compact('specifications'))
            ->with('page', 'shop');
    }

    public function store(Request $request, ProductServicesOld $productServices)
    {
        $product = $productServices->create($request->post());

        if($product['error']) {
            return back()
                ->with('error', $product['error'])
                ->withInput();
        }

        return redirect(route('product.show.collection', $product->specifications_id))
            ->with('response', "ProductOld has been added!");
    }

    public function update($id, ProductRepositoryOld $productRepository)
    {
        $product = $productRepository->findWithJoin($id);
        return view('admin.shop.product.update')
            ->with('page', 'shop')
            ->with(compact('product'));
    }

    public function upsave($id, ProductServicesOld $productServices)
    {
        $product = $productServices->update($id, $this->request);
        if($product['error']) {
            return back()
                ->withInput()
                ->with('error', $product['error']);
        }

        return redirect(route('product.show.collection', $product->specifications_id))
            ->with('response', "Record successfully updated!");

    }

    public function destroy(ProductOld $product, ProductServicesOld $productServices)
    {
        $specifications_id = $product->specifications_id;
        $result = $productServices->delete($product);

        if(@$result['error']) {
            return back()
                ->with('error', $result['error']);
        }
        return redirect(route('product.show.collection', $specifications_id))
            ->with('response', 'Record has been deleted!');

    }

}

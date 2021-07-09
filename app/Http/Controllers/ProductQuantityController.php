<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ProductRepository;
use App\Http\Services\ProductServices;
use App\Models\Product;
use App\Models\ProductQuantity;
use Illuminate\Http\Request;

class ProductQuantityController extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request->except('_token');
    }

    public function add(Product $product)
    {
        return view('admin.shop.product.quantity.add')
            ->with(compact('product'))
            ->with('page', 'shop');
    }

    public function store(Product $product, ProductServices $productServices)
    {
        $init = $productServices->addQuantity($product, $this->request);
        //check if error occurred
        if(@$init['error']) {
            return back()
                ->with('error', $init['error']);
        }
        return redirect(route('product.show', $init))
            ->with('response', 'Quantity successfully added!');
    }

    public function update(ProductQuantity $productQuantity, ProductRepository $productRepository)
    {
        $product = $productRepository->getProduct($productQuantity->product_id);
        return view('admin.shop.product.quantity.update')
            ->with('page', 'shop')
            ->with(compact('product'))
            ->with(compact('productQuantity'));
    }

    public function upsave(ProductQuantity $productQuantity, ProductServices $productServices)
    {
        $init = $productServices->updateQuantity($productQuantity, $this->request);
        //check if error occurred
        if(@$init['error']) {
            return back()
                ->with('error', $init['error']);
        }
        return redirect(route('product.show', $init->product_id))
            ->with('response', 'Product Quantity successfully updated!');
    }

    public function delete(ProductQuantity $productQuantity, ProductServices $productServices)
    {

        $productID = $productQuantity->product_id;
        $init = $productServices->deleteQuantity($productQuantity);
        //check if error occurred then redirect
        if(@$init['error']) {
            return back()
                ->with('error', $init['error']);
        }
        return redirect(route('product.show', $productID))
            ->with('response', 'Product Quantity successfully deleted!');
    }
}

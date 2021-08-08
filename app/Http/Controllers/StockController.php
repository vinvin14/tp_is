<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ProductRepository;
use App\Http\Services\StockServices;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function create(Product $product)
    {
        return view('shop.product.stocks.create')
        ->with(compact('product'))
        ->with('page', 'shop');
    }

    public function store($product_id, Request $request, StockServices $stockServices)
    {
        $request['product_id'] = $product_id;
        $init = $stockServices->create($request->only('product_id', 'qty', 'expiration_date', 'received_date'));

        if (@$init['error']) {
            return back()
            ->with('error', $init['error'])
            ->withInput();
        }

        return redirect(route('product.show', $product_id))
        ->with('success', 'New Stocks successfully added!');
    }

    public function edit(Stock $stock, ProductRepository $productRepository)
    {
        $product = $productRepository->getProduct($stock->product_id);
        return view('shop.product.stocks.edit')
        ->with(compact('stock'))
        ->with(compact('product'))
        ->with('page', 'shop');
    }

    public function update(Stock $stock, Request $request, StockServices $stockServices)
    {
        $init= $stockServices->update($stock, $request->only('qty', 'expiration_date', 'received_date'));

        if (@$init['error']) {
            return back()
            ->with('error', $init['error'])
            ->withInput();
        }

        return redirect(route('product.show', $stock->product_id))
        ->with('success', 'Stock record has been successfully updated!');
    }

    public function destroy(Stock $stock, $product_id, StockServices $stockServices)
    {
        $init = $stockServices->delete($stock);

        if (@$init['error']) {
            return back()
            ->with('error', $init['error']);
        }

        return redirect(route('product.show', $product_id))
        ->with('success', 'stock entry has been successfully deleted!');
    }
}

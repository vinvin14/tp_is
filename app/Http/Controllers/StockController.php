<?php

namespace App\Http\Controllers;

use App\Http\Services\StockServices;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function create()
    {
        return view('shop.product.stocks.create')
        ->with('page', 'shop');
    }

    public function store($product_id, Request $request, StockServices $stockServices)
    {
        $request['product_id'] = $product_id;
        $init = $stockServices->create($request->only('product_id', 'qty', 'expiration_date', 'date_received'));

        if (@$init['error']) {
            return back()
            ->with('error', $init['error'])
            ->withInput();
        }

        return redirect(route('product.show', $product_id))
        ->with('success', 'New Stocks successfully added!');
    }

    public function edit(Stock $stock)
    {
        return view('shop.product.stocks.edit')
        ->with(compact('stock'));
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

    public function destroy(Stock $stock, StockServices $stockServices)
    {
        $init = $stockServices->delete($stock);
    }
}

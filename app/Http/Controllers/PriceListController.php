<?php

namespace App\Http\Controllers;

use App\Models\PriceList;
use Illuminate\Http\Request;

class PriceListController extends Controller
{
    public function index()
    {
        $priceLists = PriceList::query()
            ->orderBy('price', 'ASC')
            ->paginate(20);
        return view('reference.pricelist.index')
            ->with(compact('priceLists'));
    }

    public function create(Request $request)
    {
        PriceList::query()
            ->create($request);
    }

}

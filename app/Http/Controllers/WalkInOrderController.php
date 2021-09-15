<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalkInOrderController extends Controller
{
    public function index()
    {
        return view('walkin.index')
        ->with('page', 'shop');
    }

    public function show()
    {
        return view('walkin.show')
        ->with('page', 'shop');
    }

    public function create()
    {
        return view('walkin.create')
        ->with('page', 'shop');
    }

    public function store(Request $request)
    {
        
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 06/16/2021
 * Time: 5:33 PM
 */

namespace App\Http\Controllers;


use App\Http\Services\UnitServices;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class UnitController
{
    public function index()
    {
        $units = Unit::query()
            ->orderBy('name', 'ASC')
            ->get();

        return view('reference.unit.index')
            ->with('page', 'reference')
            ->with(compact('units'));
    }

    public function create()
    {
        return view('reference.unit.create')
            ->with('page', 'reference');
    }

    public function store(Request $request, UnitServices $unitServices)
    {
       $result = $unitServices->create($request->post());

       if(@$result['error']) {
           return back()
               ->withInput()
               ->with('error', $result['error']);
       }

        return redirect(route('unit.list'))
            ->with('success', "$result->name has been added to our record!");
    }

    public function edit(Unit $unit)
    {
        return view('reference.unit.update')
            ->with('page', 'reference')
            ->with(compact('unit'));
    }

    public function update(Unit $unit, Request $request, UnitServices $unitServices)
    {
        $result = $unitServices->update($unit, $request->except('_token'));
        if(@$result['error']) {
            return back()
                ->withInput()
                ->with('error', $result['error']);
        }
        return redirect(route('unit.list'))
            ->with('success', "$result->name has been updated!");
    }

    public function destroy(Unit $unit, UnitServices $unitServices)
    {
        $result = $unitServices->delete($unit);

        if(@$result['error']) {
            return back()
                ->withInput()
                ->with('error', $result['error']);
        }

        return redirect(route('unit.list'))
            ->with('success', 'Unit record has been successfully deleted!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ClaimTypeRepository;
use App\Http\Services\ClaimTypeServices;
use App\Models\ClaimType;
use Exception;
use Illuminate\Http\Request;

class ClaimTypeController extends Controller
{
    public function index(ClaimTypeRepository $claimTypeRepository)
    {
        $claimTypes = $claimTypeRepository->all();
        return view('reference.claimtype.index')
        ->with(compact('claimTypes'))
        ->with('page', 'reference');
    }

    public function create()
    {
        return view('reference.claimtype.create')
        ->with('page', 'reference');
    }

    public function store(Request $request, ClaimTypeServices $claimTypeServices)
    {
        $init = $claimTypeServices->create($request->only('name'));

        if (@$init['error']) {
            return back()
            ->with('error', $init['error'])
            ->withInput();
        }

        return redirect(route('claimType.list'))
        ->with('success', "$init->name has been successfully added!");
    }

    public function edit(ClaimType $claimType)
    {  
        return view('reference.claimtype.edit')
        ->with(compact('claimType'))
        ->with('page', 'reference');
    }

    public function update(ClaimType $claimType, Request $request, ClaimTypeServices $claimTypeServices)
    {
        $init = $claimTypeServices->update($claimType, $request->only('name'));

        if (@$init['error']) {
            return back()
            ->with('error', $init['error'])
            ->withInput();
        }

        return redirect(route('claimType.list'))
        ->with('success', "$init->name has been successfully updated!");
    }

    public function destroy(ClaimType $claimType, ClaimTypeServices $claimTypeServices)
    {
        $init = $claimTypeServices->delete($claimType);

        if (@$init['error']) {
            return back()
            ->with('error', $init['error']);
        }

        return redirect(route('claimType.list'))
        ->with('success', 'Claim Type has been successfully deleted!');
    }
}

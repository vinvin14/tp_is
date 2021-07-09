<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ProductCategoryRepository;
use App\Http\Services\ProductCategoryServices;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request->except('_token');
    }

    public function index(ProductCategoryRepository $repository)
    {
        $categories = $repository->all();
        return view('admin.reference.category.index')
            ->with('page', 'reference')
            ->with(compact('categories'));
    }

    public function show(ProductCategory $category)
    {
        return view('admin.reference.category.show')
            ->with(compact('category'));
    }

    public function create()
    {
        return view('admin.reference.category.create')
            ->with('page', 'reference');
    }

    public function store(ProductCategoryServices $productCategoryServices)
    {
        $result = $productCategoryServices->create($this->request);
        if($result['error']) {
            return back()
                ->withInput()
                ->with('error', $result['error']);
        }
        return redirect(route('category.list'))
            ->with('response', "$result->category_name successfully added!");
    }

    public function update(ProductCategory $category)
    {
        return view('admin.reference.category.update')
            ->with('page', 'reference')
            ->with(compact('category'));
    }

    public function upsave(ProductCategory $category, ProductCategoryServices $productCategoryServices)
    {
        $result = $productCategoryServices->update($category, $this->request);

        if(@$result['error']) {
            return back()
                ->withInput()
                ->with('error', $result['error']);
        }

        return redirect(route('category.list'))
            ->with('response', 'ProductOld Category successfully created!');
    }

    public function destroy(ProductCategory $category, ProductCategoryServices $productCategoryServices)
    {
        $result = $productCategoryServices->delete($category);
        if(@$result['error']) {

            return back()
                ->with('error', $result['error']);
        }

        return redirect(route('category.list'))
            ->with('response', 'ProductOld Category Record has been deleted!');
    }
}


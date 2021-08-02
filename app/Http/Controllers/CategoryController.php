<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CategoryRepository;
use App\Http\Services\CategoryServices;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request->except('_token');
    }

    public function index(CategoryRepository $repository)
    {
        $categories = $repository->all();
        return view('reference.category.index')
            ->with('page', 'reference')
            ->with(compact('categories'));
    }

    public function show(Category $category)
    {
        return view('reference.category.show')
            ->with(compact('category'));
    }

    public function create()
    {
        return view('reference.category.create')
            ->with('page', 'reference');
    }

    public function store(CategoryServices $productCategoryServices)
    {
        $result = $productCategoryServices->create($this->request);
        if($result['error']) {
            return back()
                ->withInput()
                ->with('error', $result['error']);
        }
        return redirect(route('category.list'))
            ->with('response', "$result->name successfully added!");
    }

    public function edit(Category $category)
    {
        return view('reference.category.update')
            ->with('page', 'reference')
            ->with(compact('category'));
    }

    public function update(Category $category, CategoryServices $productCategoryServices)
    {
        $result = $productCategoryServices->update($category, $this->request);

        if(@$result['error']) {
            return back()
                ->withInput()
                ->with('error', $result['error']);
        }

        return redirect(route('category.list'))
            ->with('response', "$result->name successfully updated!");
    }

    public function destroy(Category $category, CategoryServices $productCategoryServices)
    {
        $result = $productCategoryServices->delete($category);
        if(@$result['error']) {

            return back()
                ->with('error', $result['error']);
        }

        return redirect(route('category.list'))
            ->with('response', 'Category record has been deleted!');
    }
}


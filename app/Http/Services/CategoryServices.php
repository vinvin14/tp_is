<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 05/31/2021
 * Time: 7:39 PM
 */

namespace App\Http\Services;


use App\Http\Repositories\CategoryRepository;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryServices
{
    public function __construct()
    {
        $this->error = new ErrorRecordServices();
    }

    public function create($request)
    {
        $validator = Validator::make($request, [
            'name' => 'unique:categories|string'
        ]);
        if ($validator->fails()) {
            return ['error' => 'Request denied, Category has been added previously!'];
        }
        try {
            return Category::query()
            ->create($request);
        } catch (\Exception $exception) {
            $this->error->log('PRODUCT_CATEGORY_ADD', session('user'), $exception->getMessage());
            return ['error' => 'Something went wrong, Please contact your Administrator!'];
        }
    }

    public function update($category, $request)
    {
        try {
            $category->name = $request['name'];

            if (!$category->isDirty()) {
                return ['error' => 'No changes were made!'];
            }

            $validator = Validator::make($request, [
                'name' => 'unique:categories|string'
            ]);

            if ($validator->fails()) {
                return ['error' => 'Request denied, Category has been added previously!'];
            }

            $category->save();
            return $category;
        } catch (\Exception $exception) {
            $this->error->log('PRODUCT_CATEGORY_UPDATE', session('user'), $exception->getMessage());
            return back()->with('error', 'Something went wrong, Please contact your Administrator!');
        }
    }

    public function delete($category)
    {
        if ($this->categoryRepository->referenced($category->id)) {
            return ['error' => 'This category cannot be deleted since it has been referenced by other records!'];
        }
        try {
            $category->delete();
        } catch (\Exception $exception) {
            $this->error->log('PRODUCT_CATEGORY_DELETE', session('user'), $exception->getMessage());
            return ['error' => 'Something went wrong, Please contact your Administrator!'];
        }
    }
}

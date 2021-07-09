<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 05/31/2021
 * Time: 7:39 PM
 */

namespace App\Http\Services;


use App\Http\Repositories\ProductCategoryRepository;
use Illuminate\Support\Facades\Validator;

class ProductCategoryServices
{
    private $pcr, $error;

    public function __construct()
    {
        $this->pcr = new ProductCategoryRepository();
        $this->error = new ErrorRecordServices();
    }

    public function create($request)
    {
        $validator = Validator::make($request, [
            'category_name' => 'unique:product_category|string'
        ]);
        if ($validator->fails()) {
            return ['error' => 'Request denied, ProductOld Category has been added previously!'];
        }
        try {
            return $this->pcr->create($request);
        } catch (\Exception $exception) {
            $this->error->log('PRODUCT_CATEGORY_ADD', session('user'), $exception->getMessage());
            return ['error' => 'Something went wrong, Please contact your Administrator!'];
        }
    }

    public function update($category, $request)
    {
        try {
            $category->category_name = $request['category_name'];
            $category->description = $request['description'];

            if (!$category->isDirty()) {
                return ['error', 'No changes were made!'];
            }

            $validator = Validator::make($request, [
                'category_name' => 'unique:product_category|string'
            ]);

            if ($validator->fails()) {
                return ['error' => 'Request denied, ProductOld Category has been added previously!'];
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
        if ($this->pcr->referenced($category->id)) {
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

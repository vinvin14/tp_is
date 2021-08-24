<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 06/15/2021
 * Time: 6:58 PM
 */

namespace App\Http\Services;


use App\Http\Repositories\ProductRepository;
use App\Models\ProductQuantity;
use App\Models\Product;
use App\Models\ProductTracker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Config\trackerReason;
use Exception;

class ProductServices
{
    private $error, $productRepository, $storage_path;

    public function __construct()
    {
        $this->error = new ErrorRecordServices();
        $this->productRepository = new ProductRepository();
        $this->storage_path = 'storage/product_images';
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            if (array_key_exists("image", $request)) {
                list($path_name, $original_file_name) = $this->uploadImage($request);
                $request['uploaded_img'] = $path_name;
                $request['original_img_file_name'] = $original_file_name;
                unset($request['image']);
            }

            //creating product
            $product = Product::query()
                ->create($request);

            DB::commit();
            return $product;
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            DB::rollBack();
            $this->error->log('PROD_ADD', session('user'), $exception->getMessage());
            return ['error' => 'We are having technical problems, Please contact your Administrator!'];
        }
    }

    public function update($product, $request)
    {
        try {
            if (!empty($request['image'])) {
                if (File::exists($product->uploaded_img)) {
                    File::delete($product->uploaded_img);
                }
                list($path_name, $original_file_name) = $this->uploadImage($request);
                $product->uploaded_img = $path_name;
                $product->original_img_file_name = $original_file_name;
                unset($request['image']);
            }

            $product->title = $request['title'];
            $product->description = $request['description'];
            $product->category_id = $request['category_id'];
            $product->unit_id = $request['unit_id'];
            $product->price = $request['price'];
            $product->points = $request['points'];
            $product->alert_level = $request['alert_level'];

            if (!$product->isDirty()) {
                return ['error' => 'No changes were made!'];
            }
            $product->save();
            return $product;
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            $this->error->log('PROD_UPDATE', session('user'), $exception->getMessage());
            return ['error' => 'We are having technical problems, Please contact your Administrator!'];
        }
    }

    public function updateCurrentQty($product_id, $qty)
    {
        try {
            $product = Product::query()->findOrFail($product_id);
            $product->update([
                'initial_qty' => ($product->current_qty + $qty),
                'current_qty' => ($product->current_qty + $qty)
            ]);
            return $product->fresh();
        } catch (Exception $exception) {
            $this->error->log('PROD_QTY_UPDATE', session('user'), $exception->getMessage());
            return ['error' => 'We are having technical problems, Please contact your Administrator!'];
        }
    }

    public function delete($product)
    {
        if ($this->productRepository->isReferenced($product->id)) {
            return ['error' => 'Product Record cannot be deleted since it has been referenced by other record!'];
        }
        DB::beginTransaction();
        try {
            if (File::exists($product->uploaded_img)) {
                File::delete($product->uploaded_img);
            }
            $product->delete();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->error->log('PROD_DELETE', session('user'), $exception->getMessage());
            return ['error' => 'We are having technical problems, Please contact your Administrator!'];
        }
    }

    public function uploadImage($request)
    {
//        dd($request);
        $validator = Validator::make($request, [
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000'
        ]);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $image = $request['image'];
        $image_original_file_name = $image->getClientOriginalName();
        $image_name = Str::slug($request['title'], '_') . '_' . date('Y-m-d') . '.' . $image->extension();
        $path_name = $this->storage_path . '/' . $image_name;

        $resized_image = Image::make($image->getRealPath());
        $resized_image->resize(350, 350, function ($constraint) {
            $constraint->aspectRatio();
        });
        $resized_image->save($path_name);
        return ['/' . $path_name, $image_original_file_name];
    }
}

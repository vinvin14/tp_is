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

            $received_date = $request['received_date'];
            $expiry = $request['expiration_date'];
            unset($request['expiration_date'], $request['received_date']);
            //creating product
            $product = Product::query()
                ->create($request);
            //creating expiration record
            $productQuantity = ProductQuantity::query()
                ->create([
                    'product_id' => $product->id,
                    'received_date' => $received_date,
                    'expiration_date' => $expiry,
                    'quantity' => $product->current_quantity
                ]);
            //creating product tracker
            $productTracker = new ProductTrackerServices();
            $productTracker->create(
                $product->id,
                $productQuantity->id,
                $productTracker->trackerReason(3),
                'in',
                0,
                $product->current_quantity,
                'no'
            );
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

            $product->item_title = ucfirst($request['item_title']);
            $product->description = $request['description'];
            $product->product_category = $request['product_category'];
            $product->unit = $request['unit'];
            $product->price = $request['price'];
            $product->current_quantity = $request['current_quantity'];
            $product->initial_quantity = $request['initial_quantity'];
            $product->date_received = $request['date_received'];
            $product->points = $request['points'];

            if (!$product->isDirty()) {
                return ['error' => 'No changes were made!'];
            }
            $product->save();
            return $product;
        } catch (\Exception $exception) {
            $this->error->log('PROD_ADD', session('user'), $exception->getMessage());
            return ['error' => 'We are having technical problems, Please contact your Administrator!'];
        }
    }

    public function delete($product)
    {
//        dd($product);
        if ($this->productRepository->isReferenced($product->id)) {
            return ['error' => 'Product Record cannot be deleted since it has been referenced by other record!'];
        }
        try {
            $product->delete();
        } catch (\Exception $exception) {
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
        $image_name = Str::slug($request['item_title'], '_') . '_' . date('Y-m-d') . '.' . $image->extension();
        $path_name = $this->storage_path . '/' . $image_name;

        $resized_image = Image::make($image->getRealPath());
        $resized_image->resize(350, 350, function ($constraint) {
            $constraint->aspectRatio();
        });
        $resized_image->save($path_name);
        return ['/' . $path_name, $image_original_file_name];
    }

    public function addQuantity($product, $request)
    {
        $newQuantity = intval($product->current_quantity + $request['quantity']);
        DB::beginTransaction();
        try {
            $prevQuantity = $product->current_quantity;
            //update current quantity of product
            $product->update([
                'current_quantity' => $newQuantity
            ]);
            //create expiration record
            $productQuantity = ProductQuantity::query()
                ->create([
                    'product_id' => $product->id,
                    'expiration_date' => $request['expiration_date'],
                    'received_date' => $request['received_date'],
                    'quantity' => $request['quantity']
                ]);
            //create product tracker
            $productTracker = new ProductTrackerServices();

            $productTracker->create(
                $product->id,
                $productQuantity->id,
                $productTracker->trackerReason(1),
                'in',
                $prevQuantity,
                $newQuantity,
                'no'
            );
            DB::commit();
            return $product->id;
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            DB::rollBack();
            $this->error->log('PRODUCT_ADD_QUANTITY', session('user'), $exception->getMessage());
            return ['error' => 'We are having technical problems, Please contact your Administrator!'];
        }
    }

    public function updateQuantity($productQuantity, $request)
    {
        //check if product quantity has been referenced
        $productRepository = new ProductRepository();
        $product = $productRepository->getProduct($productQuantity->product_id);
        $quantityBeforeAdd = $product->current_quantity - $productQuantity->quantity;
        if ($productRepository->isProdQuantityReferenced($productQuantity->id)) {
            return ['error' => 'Record cannot be updated since it has been referenced by existing orders!'];
        }

        DB::beginTransaction();
        try {
            $productQuantity->expiration_date = $request['expiration_date'];
            $productQuantity->quantity = $request['quantity'];

            if (!$productQuantity->isDirty()) {
                return ['error' => 'No changes were made!'];
            }
            //save changes
            $productQuantity->save();
            $productQuantity->fresh();

            //update the total quantity of product
            $product->update(['current_quantity' => $quantityBeforeAdd + $request['quantity']]);

            //update tracker
            $productQuantityTracker = ProductTracker::query()
                ->where('product_quantity_id', $productQuantity->id)
                ->first();
            $productQuantityTracker->update([
                'after_quantity' => $productQuantityTracker->previous_quantity + $productQuantity->quantity
            ]);
            DB::commit();
            return $productQuantity;
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            DB::rollBack();
            $this->error->log('PRODUCT_UPDATE_QUANTITY', session('user'), $exception->getMessage());
            return ['error' => 'We are having technical problems, Please contact your Administrator!'];
        }
    }

    public function deleteQuantity($productQuantity)
    {
        $productRepository = new ProductRepository();
        $quantityToBeDeducted = $productQuantity->quantity;
        $product = $productRepository->getProduct($productQuantity->product_id);

        if ($productRepository->isProdQuantityReferenced($productQuantity->id)) {
            return ['error' => 'Record cannot be deleted since it has been referenced by existing orders!'];
        }
        DB::beginTransaction();
        try {
            $productQuantity->delete();
            $product->update(['current_quantity' => ($product->current_quantity - $quantityToBeDeducted)]);
            DB::commit();
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            DB::rollBack();
            $this->error->log('PRODUCT_DELETE_QUANTITY', session('user'), $exception->getMessage());
            return ['error' => 'We are having technical problems, Please contact your Administrator!'];
        }
    }
}

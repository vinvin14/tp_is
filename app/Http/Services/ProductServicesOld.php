<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 20/05/2021
 * Time: 8:29 PM
 */

namespace App\Http\Services;


use App\Http\Repositories\ProductRepositoryOld;
use App\Models\Product;
use function GuzzleHttp\Psr7\str;
use Illuminate\Support\Facades\DB;

class ProductServicesOld
{
    private $error, $productRepository;
    public function __construct()
    {
        $this->error = new ErrorRecordServices();
        $this->productRepository = new ProductRepositoryOld();
    }

    public function create($request)
    {
        if($this->productRepository->checkProductWithIdExp($request['specifications_id'], $request['expiry_date'])) {
            return ['error' => 'ProductOld Record has been created previously, if you are adding same product you can just update its quantity!'];
        }

        try {
            $request['initial_quantity'] = $request['current_quantity'];
            return $this->productRepository->create($request);
        } catch (\Exception $exception) {
            $this->error->log('PRODUCT_ADD', session('user'), $exception->getMessage());
            return ['error' => 'Something went wrong, Please contact your Administrator!'];
        }
    }
    public function update($id, $request)
    {
        if($this->productRepository->hasOrder($id)) {
            return ['error' => 'ProductOld cannot be updated since it has an existing order!'];
        }
        try {
            return $this->productRepository->update($id, $request);
        } catch (\Exception $exception) {
            $this->error->log('PRODUCT_UPDATE', session('user'), $exception->getMessage());
            return['error' => 'Something went wrong, Please contact your Administrator!'];
        }
    }
    public function delete($product)
    {
        if($this->productRepository->hasOrder($product->id)) {
            return ['error' => 'ProductOld cannot be deleted since it has been included to one of the orders!'];
        }
        try {
            $product->delete();
        } catch (\Exception $exception) {
            $this->error->log('PRODUCT_DELETE', session('user'), $exception->getMessage());
            return ['error' => 'Something went wrong, Please contact your Administrator!'];
        }
    }

}

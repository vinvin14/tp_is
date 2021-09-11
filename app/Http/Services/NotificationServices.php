<?php

namespace App\Http\Services;

use App\Http\Repositories\ProductRepository;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Stock;
use Exception;

class NotificationServices
{
    public function createProductDepletion($product_id)
    {
        $product = Product::query()
        ->find($product_id);


        $stocks = Stock::query()
        ->where('product_id', $product_id)
        ->sum('qty');

        if ($product->alert_level >= $stocks)
        {
            $notification = Notification::query()
            ->where([
                'reference_id' => $product->id,
                'status' => 'active',
                'type' => 'depletion'
            ])
            ->first();
            if (! $notification)
            {
                Notification::query()
                ->create([
                    'details' => 'Product '.$product->title.' has quantity of '.$stocks.' and it reached its alert level quantity!',
                    'type' => 'depletion',
                    'reference_title' => 'products',
                    'reference_id' => $product->id,
                    'link' => route('product.show', $product->id)
                ]);
            }
            else {
                $notification->update([
                    'details' => 'Product '.$product->title.' has quantity of '.$stocks.' and it reached its alert level quantity!'
                ]);
            }
            
        }


    }

    public function createProductExpiration()
    {
        $productRepository = new ProductRepository();
        $productsNearExpiry = $productRepository->getNearExpiryProduct();
        // dd($productsNearExpiry);

        if (! empty($productsNearExpiry->toArray())) {
            foreach ($productsNearExpiry as $expiry) {
    
                if ( ! Notification::query()
                ->where([
                    'reference_id' => $expiry->product_id,
                    'status' => 'active',
                    'type' => 'expiration'
                ])
                ->first()) 
                {
                    Notification::query()
                    ->create([
                        'details' => $expiry->product_title.' has quantity of '.$expiry->stocks_qty.' that will expire within 3 days',
                        'type' => 'expiration',
                        'reference_title' => 'products',
                        'reference_id' => $expiry->product_id,
                        'link' => route('product.show', $expiry->product_id)
                    ]);
                }                        
            }
        }
    }

    public function createProductExpired()
    {
        $productRepository = new ProductRepository();
        $productExpired = $productRepository->getExpiredProduct();
        
        // dd($productsNearExpiry);

        if (! empty($productExpired->toArray())) {
            foreach ($productExpired as $expiry) {
                // dd($productExpired);
                if ( ! Notification::query()
                ->where([
                    'reference_id' => $expiry->product_id,
                    'status' => 'active',
                    'type' => 'expired'
                ])
                ->first()) 
                {
                    Notification::query()
                    ->create([
                        'details' => $expiry->product_title.' has quantity of '.$expiry->stocks_qty.' and has expired!',
                        'type' => 'expired',
                        'reference_title' => 'products',
                        'reference_id' => $expiry->product_id,
                        'link' => route('product.show', $expiry->product_id)
                    ]);
                }                        
            }
        }
    }

    public function tagAsDone($notification)
    {
        try {
            $notification->update(['status' => 'done']);
        } catch (Exception $exception) {
            $this->error->log('NOTIFICATION_TAG_AS_DONE', session('user'), $exception->getMessage());
            return ['error' => 'We are encountering technical problem, Please contact your Administrator!'];
        }
    }

    public function delete($notification)
    {
        try {
            $notification->delete();
        } catch (Exception $exception) {
            $this->error->log('NOTIFICATION_DELETE', session('user'), $exception->getMessage());
            return ['error' => 'We are encountering technical problem, Please contact your Administrator!'];
        }
    }
}

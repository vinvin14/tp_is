<?php

namespace App\Http\Services;

use App\Models\Notification;
use App\Models\Product;
use App\Models\Stock;
use Exception;
use Illuminate\Support\Facades\DB;

class NotificationServices
{
    public function createProductExpiry($product_id)
    {
        $product = Product::query()
        ->find($product_id);


        $stocks = Stock::query()
        ->where('product_id', $product_id)
        ->sum('qty');

        if ($product->alert_level >= $stocks)
        {
            Notification::query()
                ->create([
                    'details' => 'Product '.$product->title.' has quantity of '.$stocks.' and it reached its alert level quantity!',
                    'type' => 'expiry',
                    'reference_title' => 'products',
                    'reference_id' => $product->id,
                    'link' => route('product.show', $product->id)
                ]);
        }


    }

    public function getNotifications()
    {
        return Notification::query()
        ->orderBy('created_at', 'DESC')
        ->get();
    }
}

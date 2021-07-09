<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 06/16/2021
 * Time: 7:41 PM
 */

namespace App\Http\Services;


use App\Models\ProductTracker;

class ProductTrackerServices
{
    public function create($reference, $productQuantity, $reason, $transaction, $prvQuantity, $aftrQuantity, $reverted )
    {
        ProductTracker::query()
            ->create([
                'reference' => $reference,
                'product_quantity_id' => $productQuantity,
                'reason' => $reason,
                'transaction' => $transaction,
                'previous_quantity' => $prvQuantity,
                'after_quantity' => $aftrQuantity,
                'reverted' => $reverted
            ]);
    }
    public function trackerReason($reason)
    {
        $reasons = [
            '',
            'ADD QUANTITY',
            'DEFECTIVE',
            'NEW PRODUCT',
            'ORDER',
            'REVERTED'
        ];
        return $reasons[$reason];
    }
}

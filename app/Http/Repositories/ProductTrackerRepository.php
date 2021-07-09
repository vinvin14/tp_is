<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 07/06/2021
 * Time: 10:49 AM
 */

namespace App\Http\Repositories;


use App\Models\ProductTracker;

class ProductTrackerRepository
{
    public function getTracker($reference, $reason)
    {
        return ProductTracker::query()
            ->where([
                'reference' => $reference,
                'reason' => $reason
            ])
            ->first();
    }
}

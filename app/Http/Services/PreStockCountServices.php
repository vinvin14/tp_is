<?php

namespace App\Http\Services;

use App\Models\PreStockCount;


class PreStockCountServices
{
    public function createPreStock($request)
    {
        $preStock = PreStockCount::query()->find($request['product_id']);
        if (empty($preStock))
        {
            PreStockCount::query()
            ->create($request);
        }

        $preStock->update($request);
    }

    public function updatePresStock($product_id, $request)
    {
        $preStock = PreStockCount::query()->find($product_id);

        $preStock->update($request);
    }
}

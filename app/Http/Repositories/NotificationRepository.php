<?php

namespace App\Http\Repositories;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NotificationRepository
{
    public function all()
    {
        return Notification::query()
        ->orderBy('created_at', 'ASC')
        ->get();
    }

    public function allWithJoin()
    {
        return DB::table('notifications')
        ->leftJoin('products', 'notifications.reference_id', '=', 'products.id')
        ->select(
            'products.title',
            'products.uploaded_img',
            'notifications.*'
        )
        ->where('status', 'active')
        ->paginate(5);

    }

    public function withinTheMonth()
    {
        return Notification::query()
        ->whereDate('created_at', '<=', Carbon::now()->firstOfMonth())
        ->get();
    }

    public function getNotifications()
    {
        return Notification::query()
        // ->where('status', 'active')
        ->orderBy('created_at', 'DESC')
        ->where('status', 'active')
        ->get();
    }


}

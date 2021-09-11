<?php

namespace App\Http\Controllers;

use App\Http\Repositories\NotificationRepository;
use App\Http\Services\NotificationServices;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationCenterController extends Controller
{
    public function index(NotificationRepository $notificationRepository)
    {
        return view('notification.index')
        ->with('notifications', $notificationRepository->allWithJoin())
        ->with('page', 'notifications');
    }

    public function done(Notification $notification, NotificationServices $notificationServices)
    {
        $init = $notificationServices->tagAsDone($notification);

        if (@$init['error']) {
            return back()
            ->with('error', $init['error']);
        }

        return redirect(route('notifications'))
        ->with('response', 'Notification has been closed');
    }

    public function delete(Notification $notification, NotificationServices $notificationServices)
    {
        $init = $notificationServices->delete($notification);

        if (@$init['error']) {
            return back()
            ->with('error', $init['error']);
        }

        return redirect(route('notifications'))
        ->with('response', 'Notification has been deleted!');
    }
}

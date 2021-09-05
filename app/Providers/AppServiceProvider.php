<?php

namespace App\Providers;

use App\Http\Services\NotificationServices;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $notification = new NotificationServices();
        Paginator::useBootstrap();

        if (Schema::hasTable('notifications')) {
            view()->share('notifications', $notification->getNotifications());
        }
    }
}

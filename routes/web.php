<?php

use App\Http\Repositories\ProductRepository;
use App\Http\Repositories\StockRepository;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});
Route::middleware('verifyToken')->group(function () {
    Route::prefix('notification')->group(function () {
        Route::get('list', 'NotificationCenterController@index')->name('notifications');
        Route::get('tagdone/{notification}', 'NotificationCenterController@done')->name('notification.tagAsDone');
        Route::get('destroy/{notification}', 'NotificationCenterController@delete')->name('notification.destroy');
    });


    Route::prefix('transaction')->group(function () {
        Route::get('list', 'TransactionController@index')->name('transaction.list');
        Route::get('show/{id}', 'TransactionController@show')->name('transaction.show');
        Route::get('create', 'TransactionController@create')->name('transaction.create');
        Route::get('update/{transaction}', 'TransactionController@update')->name('transaction.update');
        Route::get('delete/{transaction}', 'TransactionController@destroy')->name('transaction.destroy');
        Route::get('finalize/{transaction}', 'TransactionController@finalize')->name('transaction.finalize');
        Route::get('checkout/{transaction}', 'TransactionController@checkout')->name('transaction.checkout');

        Route::post('store', 'TransactionController@store')->name('transaction.store');
        Route::post('upsave/{transaction}', 'TransactionController@upsave')->name('transaction.upsave');
    });

    Route::prefix('order')->group(function () {
        Route::get('edit/{order}', 'OrderController@addOrder')->name('order.edit');
        Route::get('destroy/{order}', 'OrderController@delete')->name('order.destroy');

        Route::post('store/{transactiond_id}', 'OrderController@store')->name('order.store');
        Route::post('update/{order}', 'OrderController@update')->name('order.update');
    });

    Route::prefix('product')->group(function () {
        Route::get('list', 'ProductController@index')->name('product.list');
        Route::get('show/{id}', 'ProductController@show')->name('product.show');
        // Route::get('show-collection/{id}', 'ProductController@showCollection')->name('product.show.collection');
        Route::get('edit/{product}', 'ProductController@edit')->name('product.edit');
        Route::get('create', 'ProductController@create')->name('product.create');
        Route::get('delete/{product}', 'ProductController@destroy')->name('product.destroy');

        Route::post('store', 'ProductController@store')->name('product.store');
        Route::post('update/{product}', 'ProductController@update')->name('product.update');
    });

    Route::prefix('stock')->group(function () {
        Route::get('create/{product}', 'StockController@create')->name('stock.create');
        Route::get('edit/{stock}', 'StockController@edit')->name('stock.edit');
        Route::get('delete/{stock}/{product_id}', 'StockController@destroy')->name('stock.destroy');

        Route::post('update/{stock}', 'StockController@update')->name('stock.update');
        Route::post('store/{product_id}', 'StockController@store')->name('stock.store');
    });

    Route::prefix('customer')->group(function () {
        Route::get('list', 'CustomerController@index')->name('customer.list');
        Route::get('show/{customer}', 'CustomerController@show')->name('customer.show');
        Route::get('create', 'CustomerController@create')->name('customer.create');
        Route::get('create-transaction/{customer}', 'CustomerController@create_transaction')->name('customer.transaction.create');
        Route::get('update/{id}', 'CustomerController@update')->name('customer.update');
        Route::get('delete/{id}', 'CustomerController@destroy')->name('customer.destroy');
        Route::get('points/{id}', 'CustomerController@getPoints')->name('customer.points');

        Route::post('store', 'CustomerController@store')->name('customer.store');
        Route::post('upsave/{id}', 'CustomerController@upsave')->name('customer.upsave');
    });

    Route::prefix('reference')->group(function () {
        //product-categories
        Route::prefix('category')->group( function () {
            Route::get('list', 'CategoryController@index')->name('category.list');
            Route::get('create', 'CategoryController@create')->name('category.create');
            Route::get('edit/{category}', 'CategoryController@edit')->name('category.edit');
            Route::get('delete/{category}', 'CategoryController@destroy')->name('category.destroy');

            Route::post('store', 'CategoryController@store')->name('category.store');
            Route::post('update/{category}', 'CategoryController@update')->name('category.update');
        });

        Route::prefix('unit')->group(function () {
            Route::get('list', 'UnitController@index')->name('unit.list');
            Route::get('create', 'UnitController@create')->name('unit.create');
            Route::get('edit/{unit}', 'UnitController@edit')->name('unit.edit');
            Route::get('delete/{unit}', 'UnitController@destroy')->name('unit.destroy');

            Route::post('store', 'UnitController@store')->name('unit.store');
            Route::post('update/{unit}', 'UnitController@update')->name('unit.update');
        });

        Route::prefix('paymentMethod')->group(function () {
            Route::get('list', 'PaymentMethodController@index')->name('paymentMethod.list');
            Route::get('create', 'PaymentMethodController@create')->name('paymentMethod.create');
            Route::get('edit/{paymentMethod}', 'PaymentMethodController@edit')->name('paymentMethod.edit');
            Route::get('delete/{paymentMethod}', 'PaymentMethodController@destroy')->name('paymentMethod.destroy');

            Route::post('store', 'PaymentMethodController@store')->name('paymentMethod.store');
            Route::post('update/{paymentMethod}', 'PaymentMethodController@update')->name('paymentMethod.update');
        });

        Route::prefix('claimtype')->group(function () {
            Route::get('list', 'ClaimTypeController@index')->name('claimType.list');
            Route::get('create', 'ClaimTypeController@create')->name('claimType.create');
            Route::get('edit/{claimType}', 'ClaimTypeController@edit')->name('claimType.edit');
            Route::get('delete/{claimType}', 'ClaimTypeController@destroy')->name('claimType.destroy');

            Route::post('store', 'ClaimTypeController@store')->name('claimType.store');
            Route::post('update/{claimType}', 'ClaimTypeController@update')->name('claimType.update');
        });

    });

    Route::prefix('ajax')->group(function () {
        Route::get('get/products/{category}', 'AjaxController@getProductByCategory');
        Route::get('get/order/{id}', 'AjaxController@getOrder');
        Route::get('get/test', 'AjaxController@getProductRemaining');
        Route::post('update/order/{order}', 'AjaxController@updateOrder');
    });
});

Route::get('/', function () {
   return redirect(route('login'));
});
Route::view('login', 'login')->name('login');
Route::get ('logout', 'MainController@logout')->name('logout');
Route::post('login', 'MainController@login')->name('login_attempt');

Route::get('dashboard', 'MainController@dashboard')->name('dashboard');

//Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('test', function () {
    $expiry_date = strtotime('2021-06-22');
    $date_now = strtotime(date('Y-m-d'));

    if ($expiry_date < $date_now) {
        return 'expired';
    } else {
        return 'not expired';
    }
});

Route::get('test/products', function () {
    $product = new ProductRepository();
    return $product->getNearExpiryProduct();
});
Route::get('test2/{qty}', function ($qty) {

    $stockRepository = new StockRepository();
    return $stockRepository->getAvailableStock2($qty);
});
Route::get('hash/{string}', function ($string) {
    return \Illuminate\Support\Facades\Hash::make($string);
//    if (\Illuminate\Support\Facades\Hash::check('1234', '$2y$10$uGNDIbKMSfIbrQVff5t0SuWnD6.pkZrPoeRnRlLpFvXQSBsBdF4qO')) {
//        return 'haha';
//    }
//    return 'hehe';
});

<?php

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

Route::group(['prefix' => 'transaction'], function () {
    Route::get('list', 'TransactionController@index')->name('transaction.list');
    Route::get('show/{transaction}', 'TransactionController@show')->name('transaction.show');
    Route::get('create', 'TransactionController@create')->name('transaction.create');
    Route::get('update/{transaction}', 'TransactionController@update')->name('transaction.update');
    Route::get('delete/{transaction}', 'TransactionController@destroy')->name('transaction.destroy');
    Route::get('finalize/{transaction}', 'TransactionController@finalize')->name('transaction.finalize');
    Route::get('checkout/{transaction}', 'TransactionController@checkout')->name('transaction.checkout');

    Route::post('store', 'TransactionController@store')->name('transaction.store');
    Route::post('upsave', 'TransactionController@upsave')->name('transaction.upsave');
});

Route::group(['prefix' => 'order'], function () {
    Route::get('show/{id}', 'OrderController@show');
//    Route::get('show-temp/{id}', 'OrderController@show_temp');
    Route::get('create/{transaction_id}', 'OrderController@create')->name('order.create');
    Route::get('update/{order}', 'OrderController@update')->name('order.update');
    Route::get('delete/{order}', 'OrderController@delete')->name('order.destroy');

    Route::post('store/{transaction_id}', 'OrderController@store')->name('order.store');
    Route::post('upsave/{order}', 'OrderController@upsave')->name('order.upsave');
});

//Route::group(['prefix' => 'expiration'], function () {
//    Route::get('list', 'ProductController@index')->name('product.list');
//    Route::get('show/{id}', 'ProductController@show')->name('product.show');
//});

Route::group(['prefix' => 'product'], function () {
    Route::get('list', 'ProductController@index')->name('product.list');
    Route::get('show/{id}', 'ProductController@show')->name('product.show');
    Route::get('show-collection/{id}', 'ProductController@showCollection')->name('product.show.collection');
    Route::get('update/{id}', 'ProductController@update')->name('product.update');
    Route::get('create', 'ProductController@create')->name('product.create');
    Route::get('delete/{product}', 'ProductController@destroy')->name('product.destroy');
    Route::group(['prefix' => 'quantity'], function () {
        Route::get('add/{product}', 'ProductQuantityController@add')->name('product.quantity.add');
        Route::post('store/{product}', 'ProductQuantityController@store')->name('product.quantity.store');
        Route::get('update/{productQuantity}', 'ProductQuantityController@update')->name('product.quantity.update');
        Route::post('upsave/{productQuantity}', 'ProductQuantityController@upsave')->name('product.quantity.upsave');
        Route::get('delete/{productQuantity}', 'ProductQuantityController@delete')->name('product.quantity.destroy');
    });
    Route::post('store', 'ProductController@store')->name('product.store');
    Route::post('upsave/{id}', 'ProductControllerOld@upsave')->name('product.upsave');
});

Route::get('customers', 'CustomerController@index');
Route::group(['prefix' => 'customer'], function () {
    Route::get('list', 'CustomerController@index')->name('customer.list');
    Route::get('show/{customer}', 'CustomerController@show')->name('customer.show');
    Route::get('create', 'CustomerController@create')->name('customer.create');
    Route::get('update/{id}', 'CustomerController@update')->name('customer.update');
    Route::get('delete/{id}', 'CustomerController@destroy')->name('customer.destroy');
    Route::get('points/{id}', 'CustomerController@getPoints')->name('customer.points');

    Route::post('store', 'CustomerController@store')->name('customer.store');
    Route::post('upsave/{id}', 'CustomerController@upsave')->name('customer.upsave');
});

Route::group(['prefix' => 'reference'], function () {
    //product-categories
    Route::group(['prefix' => 'category'], function () {
        Route::get('list', 'CategoryController@index')->name('category.list');
        Route::get('create', 'CategoryController@create')->name('category.create');
        Route::get('edit/{category}', 'CategoryController@edit')->name('category.edit');
        Route::get('delete/{category}', 'CategoryController@destroy')->name('category.destroy');

        Route::post('store', 'CategoryController@store')->name('category.store');
        Route::post('update/{category}', 'CategoryController@update')->name('category.update');
    });

    Route::group(['prefix' => 'unit'], function () {
        Route::get('list', 'UnitController@index')->name('unit.list');
        Route::get('create', 'UnitController@create')->name('unit.create');
        Route::get('edit/{unit}', 'UnitController@edit')->name('unit.edit');
        Route::get('delete/{unit}', 'UnitController@destroy')->name('unit.destroy');

        Route::post('store', 'UnitController@store')->name('unit.store');
        Route::post('update/{unit}', 'UnitController@update')->name('unit.update');
    });

    Route::group(['prefix' => 'payment-type'], function () {
        Route::get('list', 'PaymentTypeController@index')->name('paymentType.list');
        Route::get('create', 'PaymentTypeController@create')->name('paymentType.create');
        Route::get('update/{paymentType}', 'PaymentTypeController@update')->name('paymentType.update');
        Route::get('delete/{paymentType}', 'PaymentTypeController@destroy')->name('paymentType.destroy');

        Route::post('store', 'PaymentTypeController@store')->name('paymentType.store');
        Route::post('upsave/{paymentType}', 'PaymentTypeController@upsave')->name('paymentType.upsave');
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

Route::get('product', function () {
    return view('admin.shop.product');
});
Route::get('hash/{string}', function ($string) {
    return \Illuminate\Support\Facades\Hash::make($string);
//    if (\Illuminate\Support\Facades\Hash::check('1234', '$2y$10$uGNDIbKMSfIbrQVff5t0SuWnD6.pkZrPoeRnRlLpFvXQSBsBdF4qO')) {
//        return 'haha';
//    }
//    return 'hehe';
});

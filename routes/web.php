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

Route::view('/', 'welcome');

Route::view('admin/login', 'auth.admin-login')->name('login');

Route::post('admin/login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->middleware('auth');

Route::post('my-kits', 'MealKitsController@store');
Route::delete('my-kits/{kit_id}', 'MealKitsController@destroy');

Route::post('my-kits/{kit_id}/meals', 'MealKitsMealsController@store');
Route::delete('my-kits/{kit_id}/meals/{meal_id}', 'MealKitsMealsController@destroy');

Route::group(['middleware' => 'auth'], function() {
    Route::get('test-home', 'HomePageController@show');
    Route::get('my-kits/{kit_id}', 'MealKitsController@show');

    Route::get('basket', 'BasketController@show');
    Route::get('basket-summary', 'BasketSummaryController@show');


});

Route::get('checkout', 'CheckoutController@show');
Route::post('checkout', 'OrdersController@store');

Route::get('payfast/return/{order:order_key}', 'PayfastController@success');
Route::get('payfast/cancel/{order:order_key}', 'PayfastController@cancelled');
Route::post('payfast/notify/{order:order_key}', 'PaymentsController@store');

Route::get('thank-you/{order:order_key}', 'ThankYouController@show');

Route::group(['middleware' => 'auth'], function () {

    Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
        Route::redirect('/', '/admin/dashboard');
        Route::view('dashboard', 'admin.dashboard');
    });

    Route::group(['prefix' => 'admin/api', 'namespace' => 'Admin'], function () {

        Route::get('upcoming-menus', 'UpcomingMenusController@index');
        Route::post('menus/{menu}/meals', 'MenuMealsController@store');

        Route::post('orderable-menus', 'OrderableMenusController@create');
        Route::delete('orderable-menus/{menu}', 'OrderableMenusController@destroy');

        Route::get('meals', 'MealsController@index');
        Route::get('meals/{meal}', 'MealsController@show');
        Route::post('meals', 'MealsController@store');
        Route::post('meals/{meal}', 'MealsController@update');
        Route::delete('meals/{meal}', 'MealsController@delete');

        Route::post('meals/{meal}/images', 'MealImagesController@store');
        Route::post('meals/{meal}/images/positions', 'MealImagePositionsController@update');
        Route::delete('meals/{meal}/images/{media}', 'MealImagesController@destroy');

        Route::post('published-meals', 'PublishedMealsController@store');
        Route::delete('published-meals/{meal}', 'PublishedMealsController@destroy');

        Route::get('ingredients', 'IngredientsController@index');
        Route::post('ingredients', 'IngredientsController@store');

        Route::get('classifications', 'ClassificationsController@index');

        Route::post('completed-ordered-kits', 'CompletedOrderedKitsController@store');

        Route::get('recent-orders', 'OrdersController@index');
        Route::get('recent-orders/{order}', 'OrdersController@show');

        Route::get('current-batch', 'CurrentBatchController@show');
    });
});

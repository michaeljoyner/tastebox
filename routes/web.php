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
Route::post('logout', 'Auth\LoginController@logout');

Route::group(['middleware' => 'auth'], function() {

    Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function() {
       Route::view('dashboard', 'admin.dashboard');
    });

    Route::group(['prefix' => 'admin/api', 'namespace' => 'Admin'], function() {

        Route::get('meals', 'MealsController@index');
        Route::get('meals/{meal}', 'MealsController@show');
        Route::post('meals', 'MealsController@store');
        Route::post('meals/{meal}', 'MealsController@update');

        Route::post('meals/{meal}/images', 'MealImagesController@store');
        Route::post('meals/{meal}/images/positions', 'MealImagePositionsController@update');
        Route::delete('meals/{meal}/images/{media}', 'MealImagesController@destroy');

        Route::post('published-meals', 'PublishedMealsController@store');
        Route::delete('published-meals/{meal}', 'PublishedMealsController@destroy');

        Route::get('ingredients', 'IngredientsController@index');
        Route::post('ingredients', 'IngredientsController@store');

        Route::get('classifications', 'ClassificationsController@index');
    });
});

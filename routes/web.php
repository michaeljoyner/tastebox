<?php

use App\Http\Controllers\Admin\PostBodyImagesController;
use App\Http\Controllers\Admin\PostPreviewController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\PostTitleImageController;
use App\Http\Controllers\Admin\PublishedPostsController;
use App\Http\Controllers\Admin\WeeklyBatchReportsController;
use App\Http\Controllers\BlogController;
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


Route::view('admin/login', 'auth.admin-login')->name('login');

Route::post('admin/login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->middleware('auth');

Route::post('my-kits', 'MealKitsController@store');
Route::delete('my-kits/{kit_id}', 'MealKitsController@destroy');

Route::post('my-kits/{kit_id}/meals', 'MealKitsMealsController@store');
Route::delete('my-kits/{kit_id}/meals/{meal_id}', 'MealKitsMealsController@destroy');

Route::post('discount-code-status', 'DiscountCodeStatusController@show');

Route::get('/', 'HomePageController@show');
Route::get('build-a-box', 'KitBuilderController@show');
Route::get('my-kits/{kit_id}', 'MealKitsController@show');

Route::get('basket', 'BasketController@show');
Route::get('basket-summary', 'BasketSummaryController@show');

Route::get('faqs', 'FaqsController@show');
Route::view('team', 'front.story.page');
Route::view('our-meals', 'front.our-meals.page');

Route::get('blog', [BlogController::class, 'index']);
Route::get('blog/{post:slug}', [BlogController::class, 'show']);

Route::view('contact', 'front.contact.page');


Route::post('contact', 'ContactMessageController@store');

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

        Route::get('/blog/posts/{post}/preview', [PostPreviewController::class, 'show']);
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

        Route::post('meals/{meal}/nutritional-info', 'MealNutritionalInfoController@update');
        Route::post('meals/{meal}/ingredients', 'MealIngredientsController@update');
        Route::post('meals/{meal}/instructions', 'MealInstructionsController@update');

        Route::post('meals/{meal}/organise-ingredients', 'OrganisedMealIngredientsController@update');

        Route::post('meals/{meal}/copies', 'MealCopiesController@store');

        Route::post('meals/{meal}/images', 'MealImagesController@store');
        Route::post('meals/{meal}/images/positions', 'MealImagePositionsController@update');
        Route::delete('meals/{meal}/images/{media}', 'MealImagesController@destroy');

        Route::post('meals/{meal}/recipe-card', 'MealRecipeCardController@show');

        Route::post('published-meals', 'PublishedMealsController@store');
        Route::delete('published-meals/{meal}', 'PublishedMealsController@destroy');

        Route::get('ingredients', 'IngredientsController@index');
        Route::post('ingredients', 'IngredientsController@store');

        Route::get('classifications', 'ClassificationsController@index');

        Route::post('completed-ordered-kits', 'CompletedOrderedKitsController@store');

        Route::get('recent-orders', 'OrdersController@index');
        Route::get('recent-orders/{order}', 'OrdersController@show');

        Route::get('current-batch', 'CurrentBatchController@show');

        Route::post('current-batch/manual-orders', 'ManualOrdersController@store');

        Route::get('menus/{menu}/batch/shopping-list', 'ShoppingListController@download');

        Route::get('instagram-feed', 'InstagramController@show');

        Route::get('discount-codes', 'DiscountCodesController@index');
        Route::post('discount-codes', 'DiscountCodesController@store');
        Route::post('discount-codes/{code}', 'DiscountCodesController@update');
        Route::delete('discount-codes/{code}', 'DiscountCodesController@delete');

        Route::get('mailing-list', 'MailingListMembersController@index');

        Route::post('fetch-images', 'MenuImagesDownloadController@show');

        Route::get('blog', [PostsController::class, 'index']);
        Route::post('blog', [PostsController::class, 'store']);
        Route::post('blog/{post}', [PostsController::class, 'update']);
        Route::delete('blog/{post}', [PostsController::class, 'delete']);

        Route::post('blog/{post}/images', [PostBodyImagesController::class, 'store']);
        Route::post('blog/{post}/title-image', [PostTitleImageController::class, 'store']);

        Route::post('published-posts', [PublishedPostsController::class, 'store']);
        Route::delete('published-posts/{post}', [PublishedPostsController::class, 'destroy']);

        Route::get('reports/weekly-batches', [WeeklyBatchReportsController::class, 'index']);
    });
});

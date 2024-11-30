<?php

use App\Http\Controllers\Admin\ActivityLogsController;
use App\Http\Controllers\Admin\AddOnCategoriesController;
use App\Http\Controllers\Admin\AddOnCategoryImageController;
use App\Http\Controllers\Admin\AddOnImageController;
use App\Http\Controllers\Admin\AddOnsController;
use App\Http\Controllers\Admin\AdjustmentsController;
use App\Http\Controllers\Admin\CancelledKitsController;
use App\Http\Controllers\Admin\DeliveryAreasController;
use App\Http\Controllers\Admin\FreeRecipesController;
use App\Http\Controllers\Admin\GeneralMemberDiscountsController;
use App\Http\Controllers\Admin\MealNotesController;
use App\Http\Controllers\Admin\MealRecipeNotesController;
use App\Http\Controllers\Admin\MemberDiscountsController;
use App\Http\Controllers\Admin\MembersController;
use App\Http\Controllers\Admin\OrderedKitsController;
use App\Http\Controllers\Admin\ResolvedAdjustmentsController;
use App\Http\Controllers\Admin\UnresolvedAdjustmentsController;
use App\Http\Controllers\Admin\UpcomingKitsController;
use App\Http\Controllers\Admin\PostBodyImagesController;
use App\Http\Controllers\Admin\PostPreviewController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\PostTitleImageController;
use App\Http\Controllers\Admin\PublishedPostsController;
use App\Http\Controllers\Admin\UsedMealsController;
use App\Http\Controllers\Admin\WeeklyBatchReportsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogArchivesController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactDietitianController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\EmailVerificationLinkRequestController;
use App\Http\Controllers\FreeRecipeApiController;
use App\Http\Controllers\KitDeliveryAddressController;
use App\Http\Controllers\MealCostingsController;
use App\Http\Controllers\MealShoppingListPdfController;
use App\Http\Controllers\MealShoppingListsController;
use App\Http\Controllers\Members\HomePageController;
use App\Http\Controllers\Members\MemberPasswordController;
use App\Http\Controllers\Members\MemberProfileController;
use App\Http\Controllers\Members\OrdersController;
use App\Http\Controllers\Members\RecipesController;
use App\Http\Controllers\Members\RevivedMemberOrdersController;
use App\Http\Controllers\RegistrationsController;
use App\Http\Controllers\RevivedGuestOrdersController;
use App\Http\Controllers\ViewRecipeCardController;
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


Route::view('admin/login', 'auth.admin-login');

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
Route::get('blog-archives', [BlogArchivesController::class, 'show']);

Route::view('contact', 'front.contact.page');
Route::view('dietician', 'front.contact-dietitian.page');


Route::post('contact', 'ContactMessageController@store');
Route::post('contact-dietitian', [ContactDietitianController::class, 'store']);

Route::get('api/free-recipes', [FreeRecipeApiController::class, 'index']);
Route::get('api/free-recipes/{meal:unique_id}', [FreeRecipeApiController::class, 'show']);

Route::post('api/kits/{kit_id}/delivery-address', [KitDeliveryAddressController::class, 'update']);

Route::get('revived-orders/{order:order_key}', [RevivedGuestOrdersController::class, 'store']);

Route::get('checkout', 'CheckoutController@show');
Route::post('checkout', 'OrdersController@store');

Route::get('payfast/return/{order:order_key}', 'PayfastController@success');
Route::get('payfast/cancel/{order:order_key}', 'PayfastController@cancelled');
Route::post('payfast/notify/{order:order_key}', 'PaymentsController@store');

Route::get('thank-you/{order:order_key}', 'ThankYouController@show');

Route::view('register', 'front.register.page');
Route::post('register', [RegistrationsController::class, 'store']);
Route::view('me/email/verify', 'members.auth.verify-email')
     ->middleware('auth')
     ->name('verification.notice');

Route::get('me/email/verify/{id}/{hash}', [EmailVerificationController::class, 'store'])
     ->middleware(['auth', 'signed'])
     ->name('verification.verify');

Route::post(
    '/email/verification-notification', [EmailVerificationLinkRequestController::class, 'store']
)
     ->middleware(['auth', 'throttle:6,1'])
     ->name('verification.send');
Route::post('login', [LoginController::class, 'login']);


Route::group(['prefix' => 'me', 'middleware' => 'auth', 'namespace' => 'Members'], function () {
    Route::view('reset-password', 'members.password.reset');
    Route::post('reset-password', [MemberPasswordController::class, 'update']);
    Route::get('home', [HomePageController::class, 'show'])->middleware('verified');

    Route::get('edit-profile', [MemberProfileController::class, 'edit']);
    Route::post('profile', [MemberProfileController::class, 'update']);

    Route::get('orders', [OrdersController::class, 'index']);

    Route::get('recipes', [RecipesController::class, 'index']);
    Route::get('recipes/{meal:unique_id}', [RecipesController::class, 'show']);

    Route::get('revived-orders/{order:order_key}', [RevivedMemberOrdersController::class, 'store']);
});

Route::group(['middleware' => 'auth'], function () {

    Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
        Route::redirect('/', '/admin/dashboard');
        Route::view('dashboard', 'admin.dashboard');

        Route::get('/blog/posts/{post}/preview', [PostPreviewController::class, 'show']);

        Route::get('/meals/{meal}/recipe-card', [ViewRecipeCardController::class, 'show']);
    });

    Route::group(['prefix' => 'admin/api', 'namespace' => 'Admin'], function () {

        Route::get('upcoming-menus', 'UpcomingMenusController@index');
        Route::post('menus/{menu}/meals', 'MenuMealsController@store');

        Route::post('menus/{menu}/free-recipes', [FreeRecipesController::class, 'store']);

        Route::post('orderable-menus', 'OrderableMenusController@create');
        Route::delete('orderable-menus/{menu}', 'OrderableMenusController@destroy');

        Route::get('meals', 'MealsController@index');
        Route::get('used-meals', [UsedMealsController::class, 'index']);
        Route::get('meals/{meal}', 'MealsController@show');
        Route::post('meals', 'MealsController@store');
        Route::post('meals/{meal}', 'MealsController@update');
        Route::delete('meals/{meal}', 'MealsController@delete');

        Route::post('meals/{meal}/public-recipe-notes', [MealRecipeNotesController::class, 'store']);

        Route::post('meals/{meal}/nutritional-info', 'MealNutritionalInfoController@update');
        Route::post('meals/{meal}/ingredients', 'MealIngredientsController@update');
        Route::post('meals/{meal}/instructions', 'MealInstructionsController@update');

        Route::post('meals/{meal}/notes', [MealNotesController::class, 'store']);

        Route::post('meals/{meal}/organise-ingredients', 'OrganisedMealIngredientsController@update');

        Route::post('meals/{meal}/copies', 'MealCopiesController@store');

        Route::post('meals/{meal}/images', 'MealImagesController@store');
        Route::post('meals/{meal}/images/positions', 'MealImagePositionsController@update');
        Route::delete('meals/{meal}/images/{media}', 'MealImagesController@destroy');

        Route::post('meals/{meal}/recipe-card', 'MealRecipeCardController@show');

        Route::post('meals/{meal}/costings', [MealCostingsController::class, 'store']);
        Route::post('/costings/{costing}', [MealCostingsController::class, 'update']);
        Route::delete('/costings/{costing}', [MealCostingsController::class, 'delete']);

        Route::post('published-meals', 'PublishedMealsController@store');
        Route::delete('published-meals/{meal}', 'PublishedMealsController@destroy');

        Route::get('ingredients', 'IngredientsController@index');
        Route::post('ingredients', 'IngredientsController@store');

        Route::get('classifications', 'ClassificationsController@index');

        Route::post('completed-ordered-kits', 'CompletedOrderedKitsController@store');

        Route::get('orders', 'OrdersController@index');
        Route::get('recent-orders/{order}', 'OrdersController@show');
        Route::get('upcoming-ordered-kits', [UpcomingKitsController::class, 'index']);

        Route::get('ordered-kits', [OrderedKitsController::class, 'index']);
        Route::get('ordered-kits/{kit}', [OrderedKitsController::class, 'show']);
        Route::post('ordered-kits/{kit}', [OrderedKitsController::class, 'update']);
        Route::post('cancelled-kits', [CancelledKitsController::class, 'store']);

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

        Route::get('members', [MembersController::class, 'index']);
        Route::get('members/{member}', [MembersController::class, 'show']);

        Route::post('general-member-discounts', [GeneralMemberDiscountsController::class, 'store']);
        Route::post('general-member-discounts/{tag}', [GeneralMemberDiscountsController::class, 'update']);
        Route::delete('general-member-discounts/{tag}', [GeneralMemberDiscountsController::class, 'delete']);

        Route::post('/members/{member}/discounts', [MemberDiscountsController::class, 'store']);
        Route::post('/member-discounts/{discount}', [MemberDiscountsController::class, 'update']);
        Route::delete('/member-discounts/{discount}', [MemberDiscountsController::class, 'delete']);

        Route::get('adjustments', [AdjustmentsController::class, 'index']);
        Route::get('adjustments/{adjustment}', [AdjustmentsController::class, 'show']);

        Route::post('resolved-adjustments', [ResolvedAdjustmentsController::class, 'store']);
        Route::get('unresolved-adjustments', [UnresolvedAdjustmentsController::class, 'index']);

        Route::get('activity-logs', [ActivityLogsController::class, 'index']);

        Route::get('delivery-areas', [DeliveryAreasController::class, 'index']);

        Route::post('meal-shopping-lists', [MealShoppingListsController::class, 'store']);
        Route::get('meal-shopping-lists/{list:uuid}', [MealShoppingListsController::class, 'show']);
        Route::get('meal-shopping-lists/{list:uuid}/pdf', [MealShoppingListPdfController::class, 'show']);

        Route::get('add-on-categories', [AddonCategoriesController::class, 'index']);
        Route::get('add-on-categories/{category:uuid}', [AddonCategoriesController::class, 'show']);
        Route::post('add-on-categories', [AddOnCategoriesController::class, 'store']);
        Route::post('add-on-categories/{category:uuid}', [AddOnCategoriesController::class, 'update']);
        Route::delete('add-on-categories/{category:uuid}', [AddOnCategoriesController::class, 'delete']);

        Route::post('add-on-categories/{category:uuid}/add-ons', [AddOnsController::class, 'store']);
        Route::get('add-ons/{addOn:uuid}', [AddOnsController::class, 'show']);
        Route::post('add-ons/{addOn:uuid}', [AddOnsController::class, 'update']);
        Route::delete('add-ons/{addOn:uuid}', [AddOnsController::class, 'delete']);

        Route::post('add-on-categories/{category:uuid}/image', [AddOnCategoryImageController::class, 'store']);
        Route::delete('add-on-categories/{category:uuid}/image', [AddOnCategoryImageController::class, 'delete']);

        Route::post('add-ons/{addOn:uuid}/image', [AddOnImageController::class, 'store']);
        Route::delete('add-ons/{addOn:uuid}/image', [AddOnImageController::class, 'delete']);
    });
});

<?php

use Illuminate\Support\Facades\Route;

//use \App\Http\Controllers\UserController;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;


// admin uses
use \App\Http\Controllers\AdminController\RegisterQuestionController;
use \App\Http\Controllers\AdminController\AdminController;
use \App\Http\Controllers\AdminController\CountryController;
use \App\Http\Controllers\AdminController\CategoryController;
use \App\Http\Controllers\AdminController\CityController;
use \App\Http\Controllers\AdminController\RestaurantController;
use \App\Http\Controllers\AdminController\PackageController;
use \App\Http\Controllers\AdminController\SettingController;
use \App\Http\Controllers\AdminController\BankController;
use \App\Http\Controllers\AdminController\MarketerController;
use \App\Http\Controllers\AdminController\SellerCodeController;
use \App\Http\Controllers\AdminController\Admin\LoginController;
use \App\Http\Controllers\AdminController\Admin\ForgotPasswordController;
use \App\Http\Controllers\AdminController\Admin\ResetPasswordController;
use App\Http\Controllers\AdminController\AdminDetailController;
use \App\Http\Controllers\AdminController\HomeController;
use \App\Http\Controllers\AdminController\ClientController;
use \App\Http\Controllers\AdminController\CountryPackageController;
use \App\Http\Controllers\AdminController\AdminNoteController;
use \App\Http\Controllers\AdminController\AdminRestaurantNoteController;

use App\Http\Controllers\AdminController\AdsController as AdminControllerAdsController;
use App\Http\Controllers\AdminController\ArchiveCategoryController;
use App\Http\Controllers\AdminController\CategoryServiceController;
use App\Http\Controllers\AdminController\ClientRequestController;
use App\Http\Controllers\AdminController\ClientRequestNoteController;
use App\Http\Controllers\AdminController\ServiceController;
use \App\Http\Controllers\AdminController\PublicQuestionController;
use \App\Http\Controllers\AdminController\ReportController;
use App\Http\Controllers\AdminController\TaskCategoryController;
use App\Http\Controllers\AdminController\TaskController;
use App\Http\Controllers\AdminController\TaskEmployeeController;
use App\Http\Controllers\AdminController\ThemeController;
// restaurant uses
use \App\Http\Controllers\RestaurantController\BranchController;
use \App\Http\Controllers\RestaurantController\RestaurantController as UserRestaurant;
use \App\Http\Controllers\RestaurantController\Restaurant\LoginController as ResLogin;
use \App\Http\Controllers\RestaurantController\Restaurant\ForgotPasswordController as ResForgetPassword;
use \App\Http\Controllers\RestaurantController\Restaurant\ResetPasswordController as ResResetPassword;
use \App\Http\Controllers\RestaurantController\HomeController as ResHome;
use \App\Http\Controllers\RestaurantController\TableController;
use \App\Http\Controllers\RestaurantController\MenuCategoryController;
use \App\Http\Controllers\RestaurantController\ModifierController;
use \App\Http\Controllers\RestaurantController\OptionController;
use \App\Http\Controllers\RestaurantController\EmployeeController;
use \App\Http\Controllers\RestaurantController\ProductController;
use \App\Http\Controllers\RestaurantController\ProductOptionController;
use \App\Http\Controllers\RestaurantController\SocialController;
use \App\Http\Controllers\RestaurantController\DeliveryController;
use \App\Http\Controllers\RestaurantController\SensitivityController;
use \App\Http\Controllers\RestaurantController\OfferController;
use \App\Http\Controllers\RestaurantController\SliderController;
use \App\Http\Controllers\RestaurantController\SubCategoryController;
use \App\Http\Controllers\RestaurantController\PosterController;
use \App\Http\Controllers\RestaurantController\ResBranchesController;
use \App\Http\Controllers\RestaurantController\ProductSizeController;
use \App\Http\Controllers\RestaurantController\ProductPhotoController;
use \App\Http\Controllers\RestaurantController\RestaurantSettingController;
use \App\Http\Controllers\RestaurantController\OrderSettingDaysController;
use \App\Http\Controllers\RestaurantController\IntegrationController;
use \App\Http\Controllers\RestaurantController\RestaurantOrderSellerCodeController;
use \App\Http\Controllers\RestaurantController\OrderFoodicsDaysController;
use App\Http\Controllers\RestaurantController\PeriodController;
use App\Http\Controllers\RestaurantController\RestaurantEmployeeController;



// Marketer
use \App\Http\Controllers\MarketerController\Marketer\LoginController as MarketerLogin;
use \App\Http\Controllers\MarketerController\HomeController as MarketerHome;
use \App\Http\Controllers\MarketerController\MarketerController as UserMarketer;

// Employees EmployeeHome
use \App\Http\Controllers\EmployeeController\Employee\LoginController as EmployeeLogin;
use \App\Http\Controllers\EmployeeController\HomeController as EmployeeHome;
use \App\Http\Controllers\EmployeeController\UserController as UserEmployee;
use \App\Http\Controllers\EmployeeController\Order\OrderController as EmployeeOrder;
use App\Http\Controllers\RestaurantController\AdsController;
use App\Http\Controllers\RestaurantController\BankController as RestaurantControllerBankController;
use App\Http\Controllers\RestaurantController\FeedbackBranchController;
use App\Http\Controllers\RestaurantController\FeedbackController;
use App\Http\Controllers\RestaurantController\LayoltyPointController;
use App\Http\Controllers\RestaurantController\Reservation\ReservationBranchController;
use App\Http\Controllers\RestaurantController\Reservation\ReservationController as ReservationReservationController;
use App\Http\Controllers\RestaurantController\Reservation\ReservationPlaceController;
use App\Http\Controllers\RestaurantController\Reservation\ReservationTableController;
use App\Http\Controllers\RestaurantController\RestaurantContactUsController;
use App\Http\Controllers\RestaurantController\RestaurantContactUsLinkController;
use App\Http\Controllers\RestaurantController\ServiceStoreController;
use App\Http\Controllers\RestaurantController\WhatsappBranchController;
use App\Http\Controllers\ServicePriceController;
use App\Http\Controllers\TestController;

// website silver routes
use \App\Http\Controllers\websiteController\Silver\HomeController as SilverHome;
use \App\Http\Controllers\websiteController\Silver\UserController;
use \App\Http\Controllers\websiteController\Silver\OrderController;
use \App\Http\Controllers\websiteController\Gold\OrderController as GoldOrder;
use \App\Http\Controllers\websiteController\Gold\TableOrderController;
use App\Http\Controllers\websiteController\Silver\ReservationController;
use App\Models\Reservation\ReservationPlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use \App\Http\Middleware\AdminRole;
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


Route::get('redirect_back', [IntegrationController::class, 'redirect_code']);

Route::get('test', [TestController::class, 'index']);
Route::get('/', function () {
    return redirect()->to('https://web.easymenu.site');
});
Route::get('/login', function () {
    return redirect()->to('https://easymenu.site/restaurant/login');
});

Route::get('/first_phase_register', function () {
    return redirect()->to('https://easymenu.site/restaurant/register/step1');
});

Route::get('/pull_menu/{id}', [IntegrationController::class, 'pull_menu'])->name('pull_menu');
Route::get('/remove_foodics_integration/{id}', [IntegrationController::class, 'remove_foodics_integration'])->name('remove_foodics_integration');

Route::get('/paymob', function () {
    paymob();
});


Route::get('/redirect_tap_back_gold_order/{order_id}/{token?}' , [GoldOrder::class , 'gold_order_tap'])->name('tapRedirectBackGoldOrder');
Route::get('/redirect_tap_back_table_order/{order_id}/{token?}' , [TableOrderController::class , 'table_order_tap'])->name('tapRedirectBackTableOrder');
Route::get('/redirect_express_success_gold/{order_id}/{token?}' , [GoldOrder::class , 'express_success'])->name('express_success');
Route::get('/table_express_success/{order_id}/{token?}' , [TableOrderController::class , 'express_success'])->name('table_express_success');
Route::get('/checkTableStatus/{order_id}/{setting_id?}/{id1?}/{id2?}', [TableOrderController::class , 'check_status'])->name('checkTableStatus');
Route::get('/redirect_express_error_gold' , [GoldOrder::class , 'express_error'])->name('express_error');

Route::get('/get_branch_service/{id}', [ RestaurantSettingController::class,'get_branch_service'])->name('get_branch_service');


Route::get('locale/{locale}', function ( $locale) {


        session(['locale' => $locale]);
        App::setLocale($locale);
        // return session()->all();
    $path = \Illuminate\Support\Facades\URL::previous();
    return redirect()->back();
})->name('language');
Route::get('restaurant/locale/{locale}', function (Request $request , $locale) {
    session()->put('lang_restaurant' , $locale);
    App::setLocale($locale);
    $path = \Illuminate\Support\Facades\URL::previous();
    return redirect()->back();
})->name('restaurant.language');
Route::get('get/cities/{id}', [CountryController::class, 'get_cities']);
Route::get('get/sub_categories/{id}', [CountryController::class, 'sub_categories']);
Route::get('get/days/{id}', [CountryController::class, 'get_days']);
Route::get('get/categories/{id}', [CountryController::class, 'categories']);
Route::get('/error', function (Request $request) {
    return $request->all();
    print trans('messages.errorOccur');
});
Route::match( ['get'], 'restaurants/{restaurant}/reservation/package-details/{id}/{date}', [ReservationController::class, 'loadPackageDetails'])->name('reservation.packageDetails');
Route::match( ['get' , 'post'], 'restaurants/{restaurant}/reservations', [ReservationController::class, 'reservationPage1'])->name('reservation.page1');
Route::match(['get' , 'post'] ,'restaurants/{branch}/reservations/{order}/page2', [ReservationController::class, 'reservationPage2'])->name('reservation.page2');
Route::get('restaurants/{branch}/reservations/{order}/payment', [ReservationController::class, 'reservationPage3'])->name('reservation.page3');
Route::match(['get','post'] , 'restaurants/{branch}/reservations/{order}/page4', [ReservationController::class, 'reservationPage4'])->name('reservation.page4');
Route::match(['get' , 'post'] , 'restaurants/{branch}/reservations/{order}/summery', [ReservationController::class, 'summery'])->name('reservation.summery');
Route::get('restaurants/reservation-data/{branch}', [ReservationController::class, 'getReservationData'])->name('reservation.data');

Route::get('/restaurants/{name}/contact_us/{item?}', [SilverHome::class, 'contactUsPage'])->name('contactUs');

Route::get('restaurants/{name}/product/{product}/{table_id?}' ,[ SilverHome::class , 'productDetails'])->name('product.show');
Route::get('/restaurants/{name}/{cat?}/{branch_name?}/{sub?}', [SilverHome::class, 'index'])->name('sliverHome');
Route::get('/table/restaurants/{name}/{name_barcode}/{cat?}/{branch_name?}/{sub?}', [SilverHome::class, 'index_table'])->name('sliverHomeTable');
Route::get('/restaurnt/{name}/{branch_name?}/{cat?}/{sub?}', [SilverHome::class, 'branch_index'])->name('sliverHomeBranch');
Route::get('/table/restaurnt/{name}/{name_barcode}/{branch_name?}/{cat?}/{sub?}', [SilverHome::class, 'index_table_branch'])->name('sliverHomeTableBranch');
Route::get('/menu_product/{id?}/{table_id?}', [SilverHome::class, 'loadMenuProduct'])->name('loadMenuProduct');
Route::post('/storeProductWeb', [SilverHome::class, 'storeProductWeb'])->name('storeProductWeb');
Route::get('/mustLogin', [SilverHome::class, 'mustLogin'])->name('mustLogin');
Route::get('/check-reservation-status/{res_id}/{id1?}/{id2?}', [ReservationController::class,'check_status'])->name('checkReservationStatus');
Route::get('/check-reservation-tap-status/{order_id}', [ReservationController::class,'check_tap_status'])->name('checkReservationTapStatus');
Route::get('/check-reservation-express-status/{order_id}', [ReservationController::class,'check_express_status'])->name('checkReservationExpressStatus');



//Route::get('/restaurants/{res}/{branch}/{cat}/{sub?}', [SilverHome::class , 'products'])->name('categoryProducts');

//Route::get('/set_product/{id}', function ($id){
//
////    $_SESSION["pid"] = $id;
//    \Illuminate\Support\Facades\Session::put('pid' ,$id);
//    return response()->json($id);
//});

/** table routes*/
Route::controller(TableOrderController::class)->group(function () {
    Route::post('/table/complete_order', 'complete_order')->name('TableCompleteOrder');
    Route::get('/table/received_order/{id?}', 'received_order')->name('TableReceivedOrder');
    Route::get('/table/empty_cart/{id}', 'empty_cart')->name('TableEmptyCart');
    Route::post('/table/add_to_cart', 'add_to_table_cart')->name('silverAddToTableCart');
    Route::get('/table/GetCart/{branch_id}/{table_id?}', 'tableGetCart')->name('tableGetCart');
    Route::post('/table_order/{id}/seller_code', 'apply_table_order_seller_code')->name('applyTableOrderSellerCode');
    Route::get('/removeTableOrderItem/{item_id?}', 'removeTableOrderItem')->name('removeTableOrderItem');

});
/** table routes*/

/**
 * start user routes
 */
Route::controller(UserController::class)->group(function () {
    Route::get('/user/login/{res_id?}/{branch_id?}', 'show_register')->name('showUserLogin');
    Route::post('/user/login/{res_id}/post/{branch_id?}', 'login')->name('UserLogin');
    Route::get('/user/register/{id}', 'show_register')->name('show_user_register');
    Route::post('/user/register/{id}', 'register')->name('user_register');
    Route::post('/user/code/verify/{id}/{res}', 'verify')->name('user_verify_code');
    Route::get('/user/forget_password/{res}', 'show_forget_password')->name('show_user_forget_password');
    Route::post('/user/forget_password/{res}', 'forget_password')->name('user_forget_password');
    Route::post('/user/forget_verify/{user}/{res}', 'forget_verify')->name('user_forget_verify');
    Route::post('/user/reset_password/{user}/{res}', 'reset_password')->name('user_reset_password');
    Route::match(['get' , 'post'] , '/user/logout', 'logout')->name('user_logout');
    Route::get('/user/profile', 'userProfile')->name('userProfile');
});


Route::post('/silver/add_to_cart', [OrderController::class , 'add_to_cart'])->name('silverAddToCart');

Route::get('/silver/get_cart/{id}',[OrderController::class , 'get_cart'])->name('silverGetCart');
Route::get('/gold/get_cart/{id}',[OrderController::class ,  'get_gold_cart'])->name('goldGetCart');
Route::get('/family/get_cart/{id}',[OrderController::class ,  'get_family_cart'])->name('familyGetCart');

Route::group(['middleware' => ['web', 'auth:web']], function () {
    Route::controller(OrderController::class)->group(function () {

        Route::get('/silver/remove_order/{id}', 'removeSilverCartOrder')->name('removeSilverCartOrder');
        Route::get('/silver/empty_cart', 'emptySilverCart')->name('emptySilverCart');
        Route::post('/order/{id}/seller_code', 'apply_order_seller_code')->name('applyOrderSellerCode');
        Route::post('/FoodicsOrder/{id}', 'FoodicsOrder')->name('FoodicsOrder');
        Route::get('/check-order-foodics-status/{order_id}/{id1?}/{id2?}', 'check_order_foodics_status')->name('checkOrderFoodicsStatus');
        Route::get('/foodics_tap_payment_status/{order_id}', 'foodics_tap_payment_status')->name('foodics_tap_payment_status');
        Route::get('/foodics_express_payment_status/{order_id}', 'foodics_express_payment_status')->name('foodics_express_payment_status');

        Route::get('/cart/details/{branch_id}', 'cart_details')->name('cart_details');
        Route::get('/user/{order_id}/show_position/{lat}/{lon}/{type}', 'show_position')->name('show_position');

        Route::get('/user/{branch_id}/foodics_show_position/{lat}/{lon}/{type}', 'foodics_show_position')->name('foodics_show_position');
        Route::get('/get/{order_id}/payment_types/{type}', 'get_order_payment_types')->name('getOrderPaymentType');


    });
    Route::controller(GoldOrder::class)->group(function () {
        Route::post('/gold/complete_order', 'complete_order')->name('GoldCompleteOrder');
        Route::get('/gold/received_order/{id}', 'received_order')->name('GoldReceivedOrder');
        Route::get('/gold/empty_cart/{id}', 'empty_cart')->name('GoldEmptyCart');
        Route::get('/check_user_order_status/{setting_id}/{id1?}/{id2?}', 'check_status')->name('checkUserOrderStatus');
        Route::get('/gold/deleteOrderItem/{id}', 'deleteOrderItem')->name('deleteOrderItem');
    });


    Route::get('/{id}/{branch}/loyalty_points', [UserController::class ,  'loyalty_points'])->name('loyalty_points');
    Route::get('/{id}/loyalty_points/convert', [UserController::class ,  'convertLoyaltyPoint'])->name('convertLoyaltyPoint');


});
/**
 * End user routes
 */


/**
 * Start @admin Routes
 */
Route::get('/admin/home', [HomeController::class, 'index'])->name('admin.home');
Route::prefix('admin')->group(function () {

    Route::get('login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [LoginController::class, 'login'])->name('admin.login.submit');
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('admin.password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('admin.password.update');
    Route::post('logout', [LoginController::class, 'logout'])->name('admin.logout');


    Route::group(['middleware' => ['web', 'auth:admin']], function () {
        Route::controller(RestaurantController::class)->group(function () {
            Route::get('/restaurants/{status}', 'index')->name('restaurants');
            Route::get('/restaurant_gold/{status}', 'index_gold')->name('restaurants_gold');
            Route::get('/restaurant_family/{status}', 'index_family')->name('restaurants_family');
            Route::get('/branches/{status}', 'branches')->name('branches');
            Route::get('/branches/{id}/edit', 'edit_branch')->name('editRestaurantBranch');
            Route::post('/branches/{id}/update', 'update_branch')->name('updateRestaurantBranch');
            Route::get('/branches/delete/{id}', 'delete_branches')->name('delete_branches')->middleware('admin');
            Route::get('/create/restaurants', 'create')->name('createRestaurant');
            Route::post('/restaurants/store', 'store')->name('storeRestaurant');
            Route::get('/restaurants/{id}/show', 'show')->name('showRestaurant');
            Route::get('/restaurants/{id}/edit', 'edit')->name('editRestaurant');
            Route::post('/restaurants/{id}/update', 'update')->name('updateRestaurant');


            Route::get('/restaurants/{id}/inComplete', 'editInComplete')->name('inCompleteRestaurant');
            Route::post('/restaurants/{id}/inComplete', 'updateInComplete')->name('inCompleteRestaurant');


            Route::get('/restaurants/delete/{id}', 'destroy')->name('deleteRestaurant')->middleware('admin');
            Route::get('/restaurants/subscription/{id}/control', 'control_subscription')->name('ControlRestaurantSubscription');
            Route::post('/restaurants/subscription/{id}/control', 'controlChanges')->name('controlChanges');
            Route::get('/restaurants/service/{id}/control', 'control_service_subscription')->name('ControlServiceSubscription');
            Route::post('/restaurants/service/{id}/control', 'controlServiceChanges')->name('controlServiceChanges');
            Route::post('/restaurants/controlPackage/{id}/control', 'controlPackage')->name('controlPackage');
            Route::get('/restaurants/archive/{id}/{state}', 'ArchiveRestaurant')->name('ArchiveRestaurant')->middleware('admin');
            Route::get('/branches/archive/{id}/{state}', 'ArchiveBranch')->name('ArchiveBranch')->middleware('admin');
            Route::get('/restaurants/ActiveRestaurant/{id}', 'ActiveRestaurant')->name('ActiveRestaurant');
            Route::get('/branches/subscription/{id}/control', 'control_branch_subscription')->name('ControlBranchSubscription');
            Route::post('/branches/subscription/{id}/control', 'controlBranchChanges')->name('controlBranchChanges');
        });
        // ads
        Route::post('/restaurant-ads/update-image', [AdminControllerAdsController::class, 'uploadImage'])->name('adminAds.update_image');
        Route::controller(AdminControllerAdsController::class)->group(function () {
            Route::get('restaurant-ads', 'mainIndex')->name('adminAds.index');
            Route::get('restaurant-ads/create', 'create')->name('adminAds.create');
            Route::post('restaurant-ads/store', 'store')->name('adminAds.store');
            Route::get('restaurant-ads/edit/{ad}', 'edit')->name('adminAds.edit');
            Route::put('restaurant-ads/update/{ad}', 'update')->name('adminAds.update');
            Route::get('restaurant-ads/delete/{ad}', 'delete');
        });
        Route::controller(AdminRestaurantNoteController::class)->group(function () {
            Route::get('notes/{restaurant_id}', 'index')->name('adminNote.index');
            Route::get('notes/{restaurant_id}/create', 'create')->name('adminNote.create');
            Route::post('notes/{restaurant_id}/store', 'store')->name('adminNote.store');
            Route::get('notes/edit/{id}', 'edit')->name('adminNote.edit');
            Route::post('notes/update/{id}', 'update')->name('adminNote.update');
            Route::get('notes/delete/{id}', 'destroy');
        });
        Route::resource('client_request' , ClientRequestController::class , ['as' => 'admin'])->except(['destroy']);
        Route::get('client_request/{clientRequest}/archived' , [ClientRequestController::class , 'changeArchived'])->name('admin.client_request.archived');
        Route::get('client_request/delete/{id}' , [ClientRequestController::class , 'destroy'])->name('admin.client_request.destroy');
        Route::resource('client_request.note' , ClientRequestNoteController::class , ['as' => 'admin'])->except(['destroy']);
        Route::get('client_request/{clientRequest}/note/delete/{id}' , [ClientRequestNoteController::class , 'destroy'])->name('admin.client_request.destroy');

        // mynotes

        Route::resource('/my-notes', AdminNoteController::class, []);
        Route::get('/my-notes/delete/{id}', [AdminNoteController::class, 'destroy']);

        // my-profile
        Route::get('/profile/my-tasks', [AdminDetailController::class, 'index'])->name('admin_details.my');
        Route::get('/profile/my-tasks/{id}', [AdminDetailController::class, 'show'])->name('admin_details.my.show');
        // my-tasks
        Route::get('/my-tasks', [TaskController::class, 'index'])->name('tasks.my');
        Route::get('/my-tasks/{id}', [TaskController::class, 'show'])->name('tasks.my.show');
        Route::match(['get' , 'post'] , '/my-tasks/{id}/change-status', [TaskController::class, 'changeStatus'])->name('tasks.my.changeStatus');
        Route::controller(AdminController::class)->group(function () {
            Route::get('/a_subscription/{id}/{admin?}', 'renew_subscription')->name('renewSubscriptionAdmin');
            Route::get('/a_subscription/{id}/renew/{admin?}', 'store_subscription')->name('renewSubscriptionPostAdmin');
            Route::post('/a_subscription/{id}/bank/{admin?}', 'renewSubscriptionBank')->name('renewSubscriptionBankAdmin');
            Route::get('/check-status/{id1?}/{id2?}/{admin?}', 'check_status')->name('checkRestaurantStatusAdmin');
            Route::get('/branches/branch_payment/{id}/{admin?}', 'get_branch_payment')->name('getBranchPayment');
            Route::post('/branches/branch_payment/{id}/{admin?}', 'store_branch_payment')->name('storeBranchPayment');
            Route::get('/branches/subscription/{id}/{country}/{subscription}/{admin?}', 'renewSubscriptionBankGet')->name('renewSubscriptionBranchBank');
            Route::post('/branches/subscription/{id}/{admin?}', 'renewBranchByBank')->name('renewBranchSubscriptionByBank');
        });

        Route::middleware([AdminRole::class])->group(function () {
            // Admins Route
            Route::resource('admins', '\App\Http\Controllers\AdminController\AdminController', []);
            Route::controller(AdminController::class)->group(function () {
                Route::get('/profile', 'my_profile');
                Route::post('/profileEdit', 'my_profile_edit');
                Route::get('/profileChangePass', 'change_pass');
                Route::post('/profileChangePass', 'change_pass_update');
                Route::get('/admin_delete/{id}', 'admin_delete');
            });
            // service_store
            Route::resource('service_category' , CategoryServiceController::class  , ['as' => 'admin'])->except('destroy');
            Route::get('service_category/delete/{id}' , [CategoryServiceController::class , 'destroy'])->name('admin.service_category.destroy');
            Route::resource('our_services', ServiceController::class, ['prefix' => 'admin', 'names' => [
                'index' => 'admin.service.index',
                'edit' => 'admin.service.edit',
                'update' => 'admin.service.update',
            ]])->except(['destroy', 'show', 'create', 'store']);
            Route::resource('our_services/{service}/country', ServicePriceController::class, [
                'names' => [
                    'index' => 'admin.service.country.index',
                    'create' => 'admin.service.country.create',
                    'store' => 'admin.service.country.store',
                    'update' => 'admin.service.country.update',
                    'edit' => 'admin.service.country.edit',
                ],
            ])->only(['index', 'create', 'edit', 'store', 'update']);
            Route::get('our_services/{service}/country/delete/{id}', [ServicePriceController::class, 'destroy'])->name('admin.service.country.delete');
            Route::get('subscription/our_services/confirm', [ServiceController::class, 'getSubscriptions'])->name('admin.service.subscription_confirm')->withoutMiddleware('admin');
            Route::get('subscription/our_services/{subscription}/{status}', [ServiceController::class, 'subscriptionConfirm'])->name('admin.service.subscription.confirm_status');
            Route::get('/service_restaurants/{service}/{status?}', [ServiceController::class, 'service_restaurants'])->name('admin.service.service_restaurants');
            Route::get('services_store/{service}/pay', [ServiceController::class, 'getNewSubscription'])->name('admin.services_store.subscription');
            Route::post('services_store/{service}/pay', [ServiceController::class, 'storeNewSubscription'])->name('admin.services_store.subscription');
            Route::post('services_store/{service}/pay/bank', [ServiceController::class, 'storeNewSubscriptionBank'])->name('admin.services_store.subscription_bank');


            Route::get('/delete/service_restaurants/{subscription}', [ServiceController::class, 'delete_restaurant_service'])->name('admin.service.delete_restaurant_service');
            Route::resource('/countries', CountryController::class, []);
            Route::get('/countries/delete/{id}', [CountryController::class, 'destroy']);
            Route::get('/active/countries/{id}/{active}', [CountryController::class, 'active'])->name('activeCountry');
            Route::get('/country_restaurants/{id}', [CountryController::class, 'country_restaurants'])->name('country_restaurants');



            Route::get('/cities/{country_id}', [CityController::class, 'index'])->name('cities.index');
            Route::get('/cities/{country_id}/create', [CityController::class, 'create'])->name('cities.create');
            Route::post('/cities/{country_id}/store', [CityController::class, 'store'])->name('cities.store');
            Route::get('/cities/{id}/edit', [CityController::class, 'edit'])->name('cities.edit');
            Route::post('/cities/{id}/update', [CityController::class, 'update'])->name('cities.update');
            Route::get('/cities/delete/{id}', [CityController::class, 'destroy'])->name('cities.destroy');

            // archive category
            Route::resource('/archive-categories', ArchiveCategoryController::class, []);
            Route::get('/archive-categories/delete/{id}', [ArchiveCategoryController::class, 'destroy']);

            // categories
            Route::resource('/categories', CategoryController::class, []);
            Route::get('/categories/delete/{id}', [CategoryController::class, 'destroy']);

            Route::resource('/admin_details', AdminDetailController::class, []);
            Route::get('/admin_details/delete/{id}', [AdminDetailController::class, 'destroy']);

            // tasks categories
            Route::resource('/task_categories', TaskCategoryController::class, []);
            Route::get('/task_categories/delete/{id}', [TaskCategoryController::class, 'destroy']);
            // tasks
            Route::resource('/tasks', TaskController::class, [])->except('destroy');
            Route::get('/tasks/delete/{id}', [TaskController::class, 'destroy']);


            Route::resource('/banks', BankController::class, []);
            Route::get('/banks/delete/{id}', [BankController::class, 'destroy']);

            // Public Questions Routes
            Route::resource('/public_questions', PublicQuestionController::class, []);
            Route::get('/public_questions/delete/{id}', [PublicQuestionController::class, 'destroy']);

            // marketers
            Route::resource('/marketers', MarketerController::class, []);
            Route::get('/marketers/delete/{id}', [MarketerController::class, 'destroy']);
            Route::get('/balance/marketers/transfer', [MarketerController::class, 'balance_transfer'])->name('BalanceTransfer');
            Route::get('/marketers/{id}/transfers', [MarketerController::class, 'transfers'])->name('MarketerTransfers');
            Route::post('/balance/marketers/store_transfer', [MarketerController::class, 'store_balance_transfer'])->name('storeBalanceTransfer');
            // Seller Codes
            Route::controller(SellerCodeController::class)->group(function () {
                Route::get('seller_codes/{marketer_id}', 'index')->name('seller_codes.index');
                Route::get('seller_code/create', 'create')->name('seller_codes.create');
                Route::post('seller_code/store', 'store')->name('seller_codes.store');
                Route::get('seller_code/edit/{id}', 'edit')->name('seller_codes.edit');
                Route::post('seller_code/update/{id}', 'update')->name('seller_codes.update');
                Route::get('/seller_code/delete/{id}', 'destroy');
                Route::get('/seller_code/activate/{id}/{status}', 'activate')->name('activateSellerCode');
                Route::get('/seller_code/show/{id}', 'show')->name('showSellerCodeOps');
            });


            // packages
            Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
            Route::get('/packages/{id}/edit', [PackageController::class, 'edit'])->name('packages.edit');
            Route::post('/packages/{id}/update', [PackageController::class, 'update'])->name('packages.update');
            Route::get('/subscription/confirm', [PackageController::class, 'confirm'])->name('subscription.confirm')->withoutMiddleware('admin');
            Route::get('/subscription/{id}/confirm/{status}', [PackageController::class, 'confirm_status'])->name('subscription.confirm_status')->withoutMiddleware('admin');
            Route::get('/subscription/branches', [PackageController::class, 'confirm_branch'])->name('subscription.confirm_branch')->withoutMiddleware('admin');
            Route::get('/subscription/foodics', [PackageController::class, 'confirm_foodics'])->name('foodics.confirm_subscription')->withoutMiddleware('admin');
            Route::get('/foodics/confirm/{id}/{status}', [PackageController::class, 'foodics_confirm'])->name('foodics.confirm')->withoutMiddleware('admin');
            // country packages
            Route::get('/country_packages/{id}', [CountryPackageController::class, 'index'])->name('country_packages.index');
            Route::get('/country_packages/create/{id}', [CountryPackageController::class, 'create'])->name('country_packages.create');
            Route::post('/country_packages/store/{id}', [CountryPackageController::class, 'store'])->name('country_packages.store');
            Route::get('/country_packages/{id}/edit', [CountryPackageController::class, 'edit'])->name('country_packages.edit');
            Route::post('/country_packages/{id}/update', [CountryPackageController::class, 'update'])->name('country_packages.update');
            Route::get('/country_packages/delete/{id}', [CountryPackageController::class, 'destroy'])->name('country_packages.delete');

            // clients
            Route::resource('/clients', ClientController::class, []);
            Route::get('/clients/delete/{id}', [ClientController::class, 'destroy']);
            Route::get('/clients/clientActivation/{id}/{active}', [ClientController::class, 'clientActivation'])->name('clientActivation');

            // settings
            Route::get('/settings', [SettingController::class, 'setting'])->name('settings.index');
            Route::post('/settings', [SettingController::class, 'store_setting'])->name('store_setting');
            Route::get('/histories', [SettingController::class, 'histories'])->name('admin.histories');
            Route::get('/month_histories', [SettingController::class, 'report_histories'])->name('admin.month_histories');

            Route::get('/histories/delete/{id}', [SettingController::class, 'delete_histories'])->name('admin.delete_histories');

            Route::get('/restaurant/histories/{id}', [SettingController::class, 'restaurant_history'])->name('admin.restaurant_history');
            // register answers routes
            Route::resource('/answers', RegisterQuestionController::class, []);
            Route::get('/answers/delete/{id}', [RegisterQuestionController::class, 'destroy']);
            Route::post('/question/update', [RegisterQuestionController::class, 'update_question'])->name('updateQuestion');
            Route::get('/answer/restaurants/{id}', [RegisterQuestionController::class, 'answer_restaurants'])->name('answer_restaurants');

            // reports routes
            Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
            Route::get('/cities_reports', [ReportController::class, 'city_reports'])->name('CityReports');
            Route::get('/countries_cities/{id}', [ReportController::class, 'countries_cities'])->name('countries_cities');
            Route::get('/city_restaurants/{id}/{status}', [ReportController::class, 'CityRestaurants'])->name('CityRestaurants');
            Route::get('/reports/restaurants/{year}/{month}/{type}', [ReportController::class, 'restaurants'])->name('reports.restaurants');
            Route::get('/reports/services/{year}/{month}/{type}', [ReportController::class, 'services'])->name('reports.services');
            Route::get('/reports/branches/{year}/{month}/{type}', [ReportController::class, 'branches'])->name('reports.branches');
            Route::get('/category_reports', [ReportController::class, 'category_reports'])->name('category_reports');
            Route::get('/category_restaurants/{id}', [ReportController::class, 'category_restaurants'])->name('category_restaurants');
        });
    });
});
/**
 * End @admin Routes
 */


/**
 * Start @restaurant Routes
 */


Route::match( ['get' , 'post'], 'restaurants-registration/{code}', [ResHome::class, 'sellerRegisters'])->name('restaurant.seller.register');
Route::match( [ 'post'], 'restaurants-registration/{code}/verification-code/{id}', [ResHome::class, 'sellerVerificationPhone'])->name('restaurant.seller.register.verification');
Route::match( ['get' ,'post'], 'restaurants-registration/{code}/payment/{id}', [ResHome::class, 'sellerRestaurantPayment'])->name('restaurant.seller.register.payment');

Route::get('/restaurants-registration/{id1?}/{id2?}',[ResHome::class , 'sellerCodeRestaurantMyFatoora'])->name('restaurant.seller.register.myfatoora');

Route::prefix('restaurant')->group(function () {

    Route::get('check-email-or-phone', [ResHome::class, 'checkEmailAndPhone'])->name('restaurant.check');
    Route::get('register/step1', [ResHome::class, 'show_register'])->name('restaurant.step1Register');
    Route::get('register-gold/step1', [ResHome::class, 'show_register'])->name('restaurant.step1Registergold');
    Route::post('store/step1', [ResHome::class, 'submit_step1'])->name('restaurant.submit_step1');
    Route::get('resend_code/{id}', [ResHome::class, 'resend_code'])->name('restaurant.resend_code');
    Route::get('phone_verification/{id}', [ResHome::class, 'phone_verification'])->name('restaurant.phone_verification');
    Route::post('phone_verification/{id}', [ResHome::class, 'code_verification'])->name('restaurant.code_verification');
    Route::get('register/step2/{id}', [ResHome::class, 'storeStep2'])->name('restaurant.step2Register');
    Route::post('store/step2/{id}', [ResHome::class, 'submitStep2'])->name('restaurant.submitStep2');
    Route::get('password/forget', [ResHome::class, 'forget_password'])->name('restaurant.password.phone');
    Route::post('password/forget/submit', [ResHome::class, 'forget_password_submit'])->name('forget_password_submit');
    Route::get('password/verification/{res}', [ResHome::class, 'password_verification'])->name('forget_password_verification');
    Route::post('password/verification/{res}/submit', [ResHome::class, 'password_verification_post'])->name('password_verification_post');
    Route::get('password/reset/{res}', [ResHome::class, 'reset_password'])->name('password_reset_restaurant');
    Route::post('password/reset/{res}', [ResHome::class, 'reset_password_post'])->name('password_reset_restaurant_post');
    Route::get('login', [ResLogin::class, 'showLoginForm'])->name('restaurant.login');
    Route::post('login', [ResLogin::class, 'login'])->name('restaurant.login.submit');
    Route::get('password/reset', [ResForgetPassword::class, 'showLinkRequestForm'])->name('restaurant.password.request');
    Route::post('password/email', [ResForgetPassword::class, 'sendResetLinkEmail'])->name('restaurant.password.email');
    Route::get('password/reset/{token}', [ResResetPassword::class, 'showResetForm'])->name('restaurant.password.reset');
    Route::post('password/reset', [ResResetPassword::class, 'reset'])->name('restaurant.password.update');
    Route::post('logout', [ResLogin::class, 'logout'])->name('restaurant.logout');

    Route::post('{id}/rate_us', [FeedbackController::class, 'rateUs'])->name('restaurant.rateUs');
    Route::post('not_allowed_ads', [AdsController::class, 'notWatchAgain'])->name('ads.not_allowed');
    Route::group(['middleware' => 'auth:restaurant'], function () {
        Route::get('/home', [ResHome::class, 'index'])->name('restaurant.home');
        // feedback
        Route::resource('feedback', FeedbackController::class, ['as' => 'restaurant'])->only(['index']);

        Route::resource('feedback/branch', FeedbackBranchController::class, ['as' => 'restaurant.feedback'])->except(['show', 'destroy']);
        Route::get('feedback/branch/delete/{id}', [FeedbackBranchController::class, 'delete'])->name('restaurant.feedback.branch.delete');
        Route::match(['get', 'post'], 'feedback/branch_setting', [FeedbackBranchController::class, 'enableFeedback'])->name('restaurant.feedback.branch.setting');

        // loyalty_point
        Route::resource('loyalty_point_price', LayoltyPointController::class, ['as' => 'restaurant'])->except(['show', 'destroy']);
        Route::get('loyalty_point_price/delete/{id}', [LayoltyPointController::class, 'delete'])->name('restaurant.loyalty_point.delete');
        Route::match(['get', 'post'], 'loyalty_point/settings', [LayoltyPointController::class, 'settings'])->name('restaurant.loyalty_point.setting');

         // whatsapp_branches
         Route::resource('/whatsapp_branches', WhatsappBranchController::class, []);
         Route::get('/whatsapp_branches/delete/{id}', [WhatsappBranchController::class, 'destroy']);

        Route::match(['get' , 'post'] , '/banks-settings', [RestaurantControllerBankController::class, 'settings'])->name('restaurant.banks.setting');
        Route::resource('/banks', RestaurantControllerBankController::class, ['as' => 'restaurant']);
        Route::get('/banks/delete/{id}', [RestaurantControllerBankController::class, 'destroy']);

        Route::resource('ads', AdsController::class, ['as' => 'restaurant'])->only(['create', 'store', 'edit', 'update']);
        Route::get('ads/delete/{id}', [AdsController::class, 'delete'])->name('restaurant.ads.delete');
        Route::get('ads', [AdsController::class, 'mainIndex'])->name('restaurant.ads.index');

        // link_contact_us
        Route::resource('link_contact_us', RestaurantContactUsLinkController::class, ['as' => 'restaurant'])->only(['create', 'store', 'edit', 'update' , 'index' ]);
        Route::get('link_contact_us/delete/{id}', [RestaurantContactUsLinkController::class, 'delete'])->name('restaurant.link_contact_us.delete');
        Route::get('link_contact_us/change_status/{id}', [RestaurantContactUsLinkController::class, 'changeStatus'])->name('restaurant.link_contact_us.changeStatus');
        Route::get('link_contact_us/{id}', [RestaurantContactUsLinkController::class, 'show'])->name('restaurant.link_contact_us.show');


        // contact_us
        Route::resource('contact_us', RestaurantContactUsController::class, ['as' => 'restaurant'])->only(['create', 'store', 'edit', 'update' , 'index']);
        Route::get('contact_us/delete/{id}', [RestaurantContactUsController::class, 'delete'])->name('restaurant.contact_us.delete');
        Route::match( ['get' , 'post'], 'contact_us/settings', [RestaurantContactUsController::class, 'setting'])->name('restaurant.contact_us.setting');

        Route::resource('reservation/branch', ReservationBranchController::class, ['names' => [
            'index' => 'restaurant.reservation.branch.index' ,
            'create' => 'restaurant.reservation.branch.create' ,
            'store' => 'restaurant.reservation.branch.store' ,
            'edit' => 'restaurant.reservation.branch.edit' ,
            'update' => 'restaurant.reservation.branch.update' ,
        ]])->except(['destroy' , 'show']);
        Route::get('reservation/branch/delete/{id}', [ReservationBranchController::class, 'delete'])->name('restaurant.reservation.branch.delete');


        Route::resource('reservation/place', ReservationPlaceController::class , ['as' => 'restaurant.reservation'])->except(['destroy' , 'show']);
        Route::get('reservation/place/delete/{id}' , [ReservationPlaceController::class , 'delete']);

        Route::resource('reservation/tables', ReservationTableController::class, ['as' => 'restaurant.reservation'])->only(['index' , 'create'  , 'store' , 'show']);

        Route::get('reservation/orders/confirm/{id}/{code}', [ReservationTableController::class, 'confirmReservation']);

        Route::get('reservation/tables/delete/{id}', [ReservationTableController::class, 'destroy']);

        Route::get('reservation/tables/{table}/delete-image/{id}', [ReservationTableController::class, 'deleteImage']);

        Route::get('reservation/tables/{table}/change-status', [ReservationTableController::class, 'changeStatus'])->name('restaurant.reservation.tables.changeStatus');


        Route::get('reservation/tables-expire', [ReservationTableController::class, 'expireIndex']);
        Route::resource('reservation/tables', ReservationTableController::class, ['as' => 'restaurant.reservation']);
        Route::get('reservation/tables/delete/{id}', [ReservationTableController::class, 'destroy']);

        Route::match(['get' , 'post'] ,'reservation/settings', [ReservationReservationController::class, 'getSettings'])->name('reservation.settings');


        Route::resource('reservation/order', ReservationReservationController::class, ['names' => [
            'index' => 'restaurant.reservation.index' ,
            'create' => 'restaurant.reservation.create' ,
            'store' => 'restaurant.reservation.store' ,
            'edit' => 'restaurant.reservation.edit' ,
            'update' => 'restaurant.reservation.update' ,
            'show' => 'restaurant.reservation.show' ,
        ]])->except(['destroy']);
        Route::get('reservation/services' , [ReservationReservationController::class, 'servicesIndex'])->name('resetaurant.reservation.services');
        Route::match( ['get' , 'post'],'reservation/cash-settings' , [ReservationReservationController::class, 'cashSettings'])->name('resetaurant.reservation.cash-settings');
        Route::match(['get' , 'post'] , 'reservation/order/{order}/confirm' , [ReservationReservationController::class, 'confirmBankOrder'])->name('resetaurant.reservation.confirm');
        Route::get('reservation/orders/finished' , [ReservationReservationController::class, 'finished'])->name('resetaurant.reservation.finished');
        Route::get('reservation/orders/canceled' , [ReservationReservationController::class, 'canceled'])->name('resetaurant.reservation.canceled');
        Route::get('reservation/orders/confirmed' , [ReservationReservationController::class, 'completed'])->name('resetaurant.reservation.confirmed');
        Route::match(['get', 'post'], 'reservation/service_setting', [ReservationReservationController::class, 'service_setting'])->name('restaurant.reservation.service.setting');

        Route::match(['get' , 'post'] , 'reservation/description' , [ReservationReservationController::class , 'reservationDescription'])->name('restaurant.reservation.description.edit');


        Route::resource('services_store', ServiceStoreController::class, ['as' => 'restaurant'])->only(['index']);
        Route::get('services_store/{service}/pay', [ServiceStoreController::class, 'getNewSubscription'])->name('restaurant.services_store.subscription');
        Route::post('services_store/{service}/pay', [ServiceStoreController::class, 'storeNewSubscription'])->name('restaurant.services_store.subscription');
        Route::post('services_store/{service}/pay/bank', [ServiceStoreController::class, 'storeNewSubscriptionBank'])->name('restaurant.services_store.subscription_bank');

    });

    Route::group(['middleware' => ['web']], function () {
        Route::controller(UserRestaurant::class)->group(function () {
            Route::get('/profile', 'my_profile')->name('RestaurantProfile');
            Route::get('/barcode', 'barcode')->name('RestaurantBarcode');
            Route::post('/profileEdit/{id?}', 'my_profile_edit')->name('RestaurantUpdateProfile');
            Route::post('/updateBarcode/{id?}', 'updateBarcode')->name('RestaurantUpdateBarcode');
            Route::match( ['get' ,'post'], '/my-information/{id?}', 'updateMyInformation')->name('RestaurantUpdateInformation');
            Route::get('/subscription/{id}/{admin?}', 'renew_subscription')->name('renewSubscription');
            Route::get('/subscription/{id}/renew/{admin?}', 'store_subscription')->name('renewSubscriptionPost');
            Route::post('/subscription/{id}/bank/{admin?}', 'renewSubscriptionBank')->name('renewSubscriptionBank');
            Route::get('/check-status/{id1?}/{id2?}/{admin?}', 'check_status')->name('checkRestaurantStatus');
            Route::get('/check-service-status/{id1?}/{id2?}', 'check_service_status')->name('checkServiceStatus');
            Route::post('/profileChangePass/{id?}', 'change_pass_update')->name('RestaurantChangePassword');
            Route::post('/RestaurantChangeExternal/{id?}', 'RestaurantChangeExternal')->name('RestaurantChangeExternal');
            Route::get('/information', 'information')->name('information');
            Route::post('/information', 'store_information')->name('store_information');
            Route::post('/RestaurantChangeColors/{id}', 'RestaurantChangeColors')->name('RestaurantChangeColors');
            Route::get('/reset_to_main/{id}', 'Reset_to_main')->name('Reset_to_main');
            Route::get('/myfatoora_token', 'myfatoora_token')->name('myfatoora_token');
            Route::post('/myfatoora_token', 'update_myfatoora_token')->name('myfatoora_token.update');
            Route::get('/my_restaurant_users', 'my_restaurant_users')->name('my_restaurant_users');



            // restaurant colors
        });
        //branches routes
        Route::resource('/branches', BranchController::class, []);
        Route::get('/branches/delete/{id}', [BranchController::class, 'destroy']);
        Route::get('/branches/get_branch_payment/{id}', [BranchController::class, 'get_branch_payment'])->name('get_branch_payment');
        Route::post('/branches/get_branch_payment/{id}', [BranchController::class, 'store_branch_payment'])->name('store_branch_payment');
        Route::get('/branches/subscription/{id}/{country}/{subscription}', [BranchController::class, 'renewSubscriptionBankGet'])->name('renewSubscriptionBankGet');
        Route::post('/branches/subscription/{id}', [BranchController::class, 'renewSubscriptionBank'])->name('renewBranchSubscriptionBank');
        Route::get('/branches/{id}/barcode', [BranchController::class, 'barcode'])->name('branchBarcode');
        Route::get('/foodics/branches', [BranchController::class, 'foodics_branches'])->name('foodics_branches');
        Route::get('/foodics/branch/{id}/{active}', [BranchController::class, 'active_foodics_branch'])->name('active_foodics_branch');
        Route::get('/foodics/discounts/{id}', [BranchController::class, 'discounts'])->name('foodics_discounts');
        Route::get('/branches/showBranchCart/{branch_id}/{state}', [BranchController::class, 'showBranchCart'])->name('showBranchCart');
         Route::get('/branches/stopBranchMenu/{branch_id}/{state}', [BranchController::class, 'stopBranchMenu'])->name('stopBranchMenu');

        Route::get('/copy_menu/branch', [BranchController::class, 'copy_menu'])->name('copyBranchMenu');
        Route::post('/copy_menu/branch', [BranchController::class, 'copy_menu_post'])->name('copyBranchMenuPost');
        Route::get('/print_invoice/{id}', [BranchController::class, 'print_invoice'])->name('print_invoice');

        Route::group(['middleware' => 'auth:restaurant'], function(){
                    // Table Routes
            Route::resource('/tables', TableController::class, []);
            Route::get('/service_tables/create/{id}', [TableController::class, 'create_service_table'])->name('createServiceTable');
            Route::get('/foodics/tables/{id}',  [TableController::class, 'foodics_tables'])->name('FoodicsOrderTable');
            Route::get('/foodics/orders',  [TableController::class, 'tableOrder'])->name('FoodicsTableOrder');
            Route::get('/foodics/foodics-info',  [TableController::class, 'getFoodicsDetails'])->name('FoodicsTableInfo');
            Route::get('/foodics/create-foodics-order',  [TableController::class, 'createFoodicsOrder'])->name('CreateFoodicsOrder');
            Route::get('/order/details',  [TableController::class, 'orderDetails'])->name('orderDetails');
            Route::get('/tables/delete/{id}', [TableController::class, 'destroy']);
            Route::get('/tables/barcode/{id}/show', [TableController::class, 'show_barcode'])->name('showTableBarcode');
            Route::get('/whatsApp/tables/{id}',  [TableController::class, 'service_tables'])->name('WhatsAppTable');
            Route::get('/easymenu/tables/{id}',  [TableController::class, 'service_tables'])->name('EasyMenuTable');

            // Restaurant Employee Routes
            Route::resource('/restaurant_employees', RestaurantEmployeeController::class, []);
            Route::get('/restaurant_employees/delete/{id}', [RestaurantEmployeeController::class, 'destroy']);


            // MenuCategory Routes
            Route::resource('/menu_categories', MenuCategoryController::class, []);
            Route::get('/branch/menu_categories/{id}', [MenuCategoryController::class, 'branch_categories'])->name('BranchMenuCategory');

            Route::get('/menu_categories/delete/{id}', [MenuCategoryController::class, 'destroy']);
            Route::get('/menu_categories/deleteCategoryPhoto/{id}', [MenuCategoryController::class, 'deleteCategoryPhoto'])->name('deleteCategoryPhoto');

            Route::get('/menu_categories/active/{id}/{active}', [MenuCategoryController::class, 'activate'])->name('activeMenuCategory');
            Route::get('/menu_categories/arrange/{id}', [MenuCategoryController::class, 'arrange'])->name('arrangeMenuCategory');
            Route::post('/menu_categories/arrange/{id}', [MenuCategoryController::class, 'arrange_submit'])->name('arrangeSubmitMenuCategory');
            Route::get('/menu_categories/copy/{id}', [MenuCategoryController::class, 'copy_category'])->name('copyMenuCategory');
            Route::post('/menu_categories/copy', [MenuCategoryController::class, 'copy_category_post'])->name('copyMenuCategoryPost');
            // Modifiers Routes
            Route::resource('/modifiers', ModifierController::class, []);
            Route::get('/modifiers/delete/{id}', [ModifierController::class, 'destroy']);
            Route::get('/modifiers/active/{id}/{is_ready}', [ModifierController::class, 'active'])->name('activeModifier');
            // Options Routes
            Route::resource('/additions', OptionController::class, []);
            Route::get('/additions/delete/{id}', [OptionController::class, 'destroy']);
            Route::get('/additions/active/{id}/{is_active}', [OptionController::class, 'active'])->name('activeOption');
            // socials Routes
            Route::resource('/socials', SocialController::class, []);
            Route::get('/socials/delete/{id}', [SocialController::class, 'destroy']);

            // Restaurant Order Seller Codes Routes
            Route::resource('/order_seller_codes', RestaurantOrderSellerCodeController::class, []);
            Route::get('/order_seller_codes/delete/{id}', [RestaurantOrderSellerCodeController::class, 'destroy']);

            // employees Routes
            Route::resource('/employees', EmployeeController::class, []);
            Route::get('/employees/delete/{id}', [EmployeeController::class, 'destroy']);

            // deliveries Routes
            Route::resource('/deliveries', DeliveryController::class, []);
            Route::get('/deliveries/delete/{id}', [DeliveryController::class, 'destroy']);

            // sensitivities Routes
            Route::resource('/sensitivities', SensitivityController::class, []);
            Route::get('/sensitivities/delete/{id}', [SensitivityController::class, 'destroy']);

            // sliders Routes
            Route::resource('/sliders', SliderController::class, []);
            Route::get('/sliders/delete/{id}', [SliderController::class, 'destroy']);
            Route::post('/sliders/upload-video', [SliderController::class, 'uploadVideo'])->name('sliders.uploadVideo');

            // res_branches Routes
            Route::resource('/res_branches', ResBranchesController::class, []);
            Route::get('/res_branches/delete/{id}', [ResBranchesController::class, 'destroy']);

            // sub_categories Routes
            Route::controller(SubCategoryController::class)->group(function () {
                Route::get('/sub_categories/{id}', 'index')->name('sub_categories.index');
                Route::get('/sub_categories/create/{id}', 'create')->name('sub_categories.create');
                Route::post('/sub_categories/store/{id}', 'store')->name('sub_categories.store');
                Route::get('/sub_categories/edit/{id}', 'edit')->name('sub_categories.edit');
                Route::post('/sub_categories/update/{id}', 'update')->name('sub_categories.update');
                Route::get('/sub_categories/delete/{id}', [SubCategoryController::class, 'destroy']);
            });

            // setting Routes
            Route::controller(RestaurantSettingController::class)->group(function () {
                Route::get('/restaurant_setting', 'index')->name('restaurant_setting.index');
                Route::get('/restaurant_setting/create', 'create')->name('restaurant_setting.create');
                Route::post('/restaurant_setting/store', 'store')->name('restaurant_setting.store');
                Route::get('/restaurant_setting/edit/{id}', 'edit')->name('restaurant_setting.edit');
                Route::post('/restaurant_setting/update/{id}', 'update')->name('restaurant_setting.update');
                Route::get('/restaurant_setting/delete/{id}', 'destroy');
                Route::get('/foodics/restaurant_setting/{id}', 'foodics_settings')->name('FoodicsOrderSetting');
                Route::post('/foodics/restaurant_setting/{id}', 'foodics_settings_update')->name('updateFoodicsOrderSetting');
            });

            // order setting days Routes
            Route::controller(OrderSettingDaysController::class)->group(function () {
                Route::get('/order_setting_days/{id}', 'index')->name('order_setting_days.index');
                Route::get('/order_setting_days/{id}/create', 'create')->name('order_setting_days.create');
                Route::post('/order_setting_days/{id}/store', 'store')->name('order_setting_days.store');
                Route::get('/order_setting_days/edit/{id}', 'edit')->name('order_setting_days.edit');
                Route::post('/order_setting_days/update/{id}', 'update')->name('order_setting_days.update');
                Route::get('/order_setting_days/delete/{id}', 'destroy');

                Route::get('/order_previous_days/{branch_id}/{setting_id?}', 'previous_index')->name('order_previous_days.index');
                Route::get('/order_previous_days/{id}/create/{setting_id?}', 'previous_create')->name('order_previous_days.create');
                Route::post('/order_previous_days/{id}/store', 'previous_store')->name('order_previous_days.store');
                Route::get('/edit_order_previous_days/{id}', 'previous_edit')->name('order_previous_days.edit');
                Route::post('/order_previous_days/update/{id}', 'previous_update')->name('order_previous_days.update');
                Route::get('/delete_order_previous_days/delete/{id}', 'previous_destroy');

            });

            // order setting days Routes
            Route::controller(OrderFoodicsDaysController::class)->group(function () {
                Route::get('/order_foodics_days/{id}', 'index')->name('order_foodics_days.index');
                Route::get('/order_foodics_days/{id}/create', 'create')->name('order_foodics_days.create');
                Route::post('/order_foodics_days/{id}/store', 'store')->name('order_foodics_days.store');
                Route::get('/order_foodics_days/edit/{id}', 'edit')->name('order_foodics_days.edit');
                Route::post('/order_foodics_days/update/{id}', 'update')->name('order_foodics_days.update');
                Route::get('/order_foodics_days/delete/{id}', 'destroy');

                Route::get('/menu_foodics_days/{id}', 'foodics_index')->name('menu_foodics_days.index');
                Route::get('/menu_foodics_days/{id}/create', 'foodics_create')->name('menu_foodics_days.create');
                Route::post('/menu_foodics_days/{id}/store', 'foodics_store')->name('menu_foodics_days.store');
                Route::get('/menu_foodics_days/edit/{id}', 'foodics_edit')->name('menu_foodics_days.edit');
                Route::post('/menu_foodics_days/update/{id}', 'foodics_update')->name('menu_foodics_days.update');
                Route::get('/menu_foodics_days/delete/{id}', 'foodics_destroy');
            });


            // posters Routes
            Route::resource('/posters', PosterController::class, []);
            Route::get('/posters/delete/{id}', [PosterController::class, 'destroy']);

            // Offer Routes
            Route::resource('/offers', OfferController::class, []);
            Route::get('/offers/delete/{id}', [OfferController::class, 'destroy']);
            Route::get('/offers/photo/{id}/remove', [OfferController::class, 'remove_photo'])->name('imageOfferRemove');

            Route::post('/menu_category/update-image', [MenuCategoryController::class, 'uploadImage'])->name('restaurant.menu_category.update_image');
            Route::post('/profile/update-image', [UserRestaurant::class, 'uploadImage'])->name('restaurant.profile.update_image');
            Route::post('/ads/update-image', [AdsController::class, 'uploadImage'])->name('restaurant.ads.update_image');
            Route::post('/ads/upload-video', [AdsController::class, 'uploadVideo'])->name('ads.uploadVideo');
            Route::post('/offer/update-image', [OfferController::class, 'uploadImage'])->name('restaurant.offer.update_image');
            // products Routes
            Route::resource('/products', ProductController::class, []);
            Route::get('/branch/products/{id}',  [ProductController::class, 'branch_products'])->name('BranchProducts');

            Route::post('/products/update-image', [ProductController::class, 'updateProductImage'])->name('restaurant.product.update_image');
            Route::get('/products/arrange/{id}', [ProductController::class, 'arrange'])->name('arrangeProduct');
            Route::post('/products/arrange/{id}', [ProductController::class, 'arrange_submit'])->name('arrangeSubmitProduct');
            Route::get('/products/copy/{id}', [ProductController::class, 'copy_product'])->name('copyProduct');
            Route::post('/products/copy/{id}', [ProductController::class, 'copy_product_submit'])->name('submitCopyProduct');

            Route::get('/products/delete/{id}', [ProductController::class, 'destroy']);
            Route::get('/products/deleteProductPhoto/{id}', [ProductController::class, 'deleteProductPhoto'])->name('deleteProductPhoto');
            Route::get('/products/active/{id}/{active}', [ProductController::class, 'active'])->name('activeProduct');
            Route::post('/products/upload-video', [ProductController::class, 'uploadVideo'])->name('products.uploadVideo');


            // products options routes
            Route::controller(ProductOptionController::class)->group(function () {
                Route::get('/product_options/{id}', 'index')->name('productOption');
                Route::get('/product_options/{id}/create', 'create')->name('createProductOption');
                Route::post('/product_options/{id}/store', 'store')->name('storeProductOption');
                Route::get('/product_options/{id}/edit', 'edit')->name('editProductOption');
                Route::post('/product_options/{id}/update', 'update')->name('updateProductOption');
                Route::get('/product_options/delete/{id}', 'destroy')->name('deleteProductOption');
            });

            // products sizes routes
            Route::controller(ProductSizeController::class)->group(function () {
                Route::get('/product_sizes/{id}', 'index')->name('productSize');
                Route::get('/product_sizes/{id}/create', 'create')->name('createProductSize');
                Route::post('/product_sizes/{id}/store', 'store')->name('storeProductSize');
                Route::get('/product_sizes/{id}/edit', 'edit')->name('editProductSize');
                Route::post('/product_sizes/{id}/update', 'update')->name('updateProductSize');
                Route::get('/product_sizes/delete/{id}', 'destroy')->name('deleteProductSize');
            });

            Route::controller(IntegrationController::class)->group(function () {
                Route::get('/integrations', 'index')->name('RestaurantIntegration');
                Route::get('/tentative_services', 'tentative_services')->name('tentative_services');
                Route::get('/foodics_subscription/{id}', 'foodics_subscription')->name('foodics_subscription');
                Route::post('/foodics_subscription/{id}', 'foodics_subscription_submit')->name('foodics_subscription_submit');
                Route::get('/check-foodics-status/{id1?}/{id2?}', 'check_status')->name('checkRestaurantFoodicsStatus');

                Route::get('/print_service_invoice/{id}', 'print_service_invoice')->name('print_service_invoice');

                Route::get('/foodics_integration', 'foodics_integration')->name('foodics_integration');
            });


            Route::get('/history/{id}', [SettingController::class, 'show_restaurant_history'])->name('show_restaurant_history');

            // restaurant period Routes
            Route::controller(PeriodController::class)->group(function () {
                Route::get('/periods/{id}', 'index')->name('BranchPeriod');
                Route::get('/periods/{id}/create', 'create')->name('createBranchPeriod');
                Route::post('/periods/{id}/store', 'store')->name('storeBranchPeriod');
                Route::get('/periods/{id}/edit', 'edit')->name('editBranchPeriod');
                Route::post('/periods/{id}/update', 'update')->name('updateBranchPeriod');
                Route::get('/periods/delete/{id}', 'destroy')->name('deleteBranchPeriod');
            });
        });


    });
});
/**
 * End @restaurant Routes
 */


/**
 * Start @marketer Routes
 */
Route::get('/marketer/home', [MarketerHome::class, 'index'])->name('marketer.home');
Route::prefix('marketer')->group(function () {

    Route::get('login', [MarketerLogin::class, 'showLoginForm'])->name('marketer.login');
    Route::post('login', [MarketerLogin::class, 'login'])->name('marketer.login.submit');
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('marketer.password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('marketer.password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('marketer.password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('marketer.password.update');
    Route::post('logout', [MarketerLogin::class, 'logout'])->name('marketer.logout');


    Route::group(['middleware' => ['web', 'auth:marketer']], function () {
        Route::controller(UserMarketer::class)->group(function () {
            Route::get('/profile', 'my_profile')->name('marketerProfile');
            Route::post('/profileEdit/{id?}', 'my_profile_edit')->name('MarketerUpdateProfile');
            Route::get('/confirmed_operations', 'confirmed_operations')->name('confirmed_operations');
            Route::get('/not_confirmed_operations', 'not_confirmed_operations')->name('not_confirmed_operations');
            Route::get('/transfers', 'transfers')->name('transfersMarketer');
            Route::post('/profileChangePass/{id?}', 'change_pass_update')->name('MarketerChangePassword');
        });

    });
});
/**
 * End @marketer Routes
 */


/**
 * Start @Employees Routes
 */
Route::get('/casher/home', [EmployeeHome::class, 'index'])->name('employee.home');
Route::prefix('casher')->group(function () {

    Route::get('login', [EmployeeLogin::class, 'showLoginForm'])->name('employee.login');
    Route::post('login', [EmployeeLogin::class, 'login'])->name('employee.login.submit');
    Route::post('logout', [EmployeeLogin::class, 'logout'])->name('employee.logout');

    Route::group(['middleware' => ['web', 'auth:employee']], function () {
        Route::controller(UserEmployee::class)->group(function () {
            Route::get('/profile', 'my_profile')->name('employeeProfile');
            Route::post('/profileEdit/{id?}', 'my_profile_edit')->name('employeeUpdateProfile');

        });
        Route::controller(EmployeeOrder::class)->group(function () {
            Route::get('/delivery/orders/{status}', 'delivery_orders')->name('employeeDeliveryOrders');
            Route::get('/takeaway/orders/{status}', 'takeaway_orders')->name('employeeTakeawayOrders');
            Route::get('/previous/orders/{status}', 'previous_orders')->name('employeePreviousOrders');
            Route::get('/tables/orders/{status}', 'table_orders')->name('employeeTableOrders');
            Route::post('/change_order_status/{order}', 'change_order_status')->name('change_order_status');
            Route::post('/change_table_order_status/{order}', 'change_table_order_status')->name('change_table_order_status');
            Route::post('/change_order_payment/{order}', 'change_order_payment')->name('change_order_payment');
        });

    });
});
/**
 * End @Employees Routes
 */

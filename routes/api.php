<?php

use App\Models\Branch;
use App\Models\Package;
use App\Models\RestaurantSlider;
use App\Models\Setting;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\AuthController;
use \App\Http\Controllers\Api\ProfileController;
use App\Models\Poster;
use App\Models\RestaurantSensitivity;
use App\Models\Sensitivity;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/get_cities', function () {
    foreach (get_cities() as $city) {
        // check the city
        $check = \App\Models\City::where('name_ar', $city['name_ar'])
            ->orWhere('name_en', $city['name'])
            ->first();
        if ($check) {
            $check->update(['old_id' => $city['id']]);
        }
    }
});
Route::get('/meals', function () {
    get_meals(2989 , 120);
});
Route::get('/get_res_email/{id}', function ($id) {
    get_res_email($id);
});
Route::get('/get_res/{email}', function ($email) {
    $res = array_values(json_decode(store_restaurant($email), true));
//    dd($res['2']);
    // check if the restaurant are created before
    $check = \App\Models\Restaurant::whereEmail($res['2']['email'])->first();
    if ($check != null) {
        echo 'المطعم موجود في السيستم ي نجم';
    } else {
        // create new restaurant
        $info = pathinfo('https://old.easymenu.site/uploads/users/' . $res['2']['image']);
        $contents = file_get_contents('https://old.easymenu.site/uploads/users/' . $res['2']['image']);
        $file = '/tmp/' . $info['basename'];
        file_put_contents($file, $contents);

        $image = $info['basename'];
        $destinationPath = public_path('/' . 'uploads/restaurants/logo');
        $img = Image::make($file);
        $img->resize(500, 500, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $image);
        $restaurant = \App\Models\Restaurant::create([
            'name_ar' => $res['2']['name_ar'],
            'name_en' => $res['2']['name'],
            'country_id' => \App\Models\Country::where('old_id', $res['2']['country_code_id'])->first() == null ? 2 : \App\Models\Country::where('old_id', $res['2']['country_code_id'])->first()->id,
            'city_id' => \App\Models\City::where('old_id', $res['2']['city_id'])->first() == null ? 3 : \App\Models\City::where('old_id', $res['2']['city_id'])->first()->id,
            'package_id' => 1,
            'phone_number' => $res['2']['phone_number'],
            'email' => $res['2']['email'],
            'password' => $res['2']['password'],
            'latitude' => $res['2']['latitude'],
            'longitude' => $res['2']['longitude'],
            'status' => $res['2']['active'] == 1 ? 'active' : 'finished',     //ENUM( 'inComplete','tentative','active','finished' ),
            'logo' => $image == null ? 'logo.png' : $image,
            'name_barcode' => $res['2']['name'], // the name used for barcode,
            'tax_value' => $res['2']['vat'],
            'menu' => 'vertical',
            'views' => $res['2']['viewer'],
            'ar' => 'true',
            'en' => 'true',
            'total_tax_price' => 'true',
            'tax' => $res['2']['vat'] == 0 ? 'false' : 'true',
            'description_ar' => $res['2']['description_ar'],
            'description_en' => $res['2']['description'],
            'information_ar' => ' يحتاج البالغون الى 2000 سعر حراري في المتوسط يومياً',
            'information_en' => 'Adults need an average of 2,000 calories per day',
        ]);
        //4- create restaurant sensitivity
        // create_restaurant_sensitivity($restaurant->id);
        defaultPostersAndSens($restaurant);

        // create the main Branch for this  restaurant
        $branch = Branch::create([
            'restaurant_id' => $restaurant->id,
            'country_id' => $restaurant->country_id,
            'city_id' => $restaurant->city_id,
            'name_ar' => $restaurant->name_ar,
            'name_en' => $restaurant->name_en,
            'name_barcode' => $restaurant->name_barcode,
            'main' => 'true',
            'status' => 'active',
            'email' => $restaurant->email,
            'phone_number' => $restaurant->phone_number,
            'latitude' => $restaurant->latitude,
            'longitude' => $restaurant->longitude,
        ]);

        // create restaurant subscription
        $subscription = Subscription::create([
            'package_id' => 1,
            'restaurant_id' => $restaurant->id,
            'branch_id' => $branch->id,
            'price' => Package::find(1)->price,
            'status' => 'active',    // active ,notActive , tentative , finished
            'end_at' => get_end_at($res['2']['id']),
            'type' => 'restaurant',
        ]);
        // create restaurant Sliders
        if (is_array(get_sliders($res['2']['id'])) || is_object(get_sliders($res['2']['id']))) {
            if (count(get_sliders($res['2']['id'])) > 0) {
                foreach (get_sliders($res['2']['id']) as $slider) {
                    $infoS = pathinfo('https://old.easymenu.site/uploads/sliders/' . $slider['image']);
                    $contentsS = file_get_contents('https://old.easymenu.site/uploads/sliders/' . $slider['image']);
                    $fileS = '/tmp/' . $infoS['basename'];
                    file_put_contents($fileS, $contentsS);

                    $imageS = $infoS['basename'];
                    $destinationPathS = public_path('/' . 'uploads/sliders');
                    $img = Image::make($fileS);
                    $img->resize(500, 500, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPathS . '/' . $imageS);
                    RestaurantSlider::create([
                        'restaurant_id' => $restaurant->id,
                        'photo' => $imageS,
                    ]);
                }
            } else {
                RestaurantSlider::create([
                    'restaurant_id' => $restaurant->id,
                    'photo' => 'slider2.png',
                ]);
                RestaurantSlider::create([
                    'restaurant_id' => $restaurant->id,
                    'photo' => 'slider1.png',
                ]);
            }
        }
        // create restaurant categories
        if (is_array(get_categories($res['2']['id'])) || is_object(get_categories($res['2']['id']))) {
            if (count(get_categories($res['2']['id'])) > 0) {
                foreach (get_categories($res['2']['id']) as $category) {
                    $imageC = null;
                    if ($category['image'] != null)
                    {
                        $infoC = pathinfo('https://old.easymenu.site/uploads/categories/' . $category['image']);
                        $contentsC = file_get_contents('https://old.easymenu.site/uploads/categories/' . $category['image']);
                        $fileC = '/tmp/' . $infoC['basename'];
                        file_put_contents($fileC, $contentsC);

                        $imageC = $infoC['basename'];
                        $destinationPathC = public_path('/' . 'uploads/menu_categories');
                        $imgC = Image::make($fileC);
                        $imgC->resize(500, 500, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($destinationPathC . '/' . $imageC);
                    }
                    $cat = \App\Models\MenuCategory::create([
                        'restaurant_id' => $restaurant->id,
                        'branch_id' => Branch::where('main', 'true')->where('restaurant_id', $restaurant->id)->first()->id,
                        'name_ar' => $category['name_ar'],
                        'name_en' => $category['name'],
                        'photo' => $imageC,
                        'foodics_image' => $category['foodics_image'],
                        'foodics_id' => $category['foodics_id'],
                        'active' => $category['active'] == 1 ? 'true' : 'false',
                        'arrange' => $category['arranging'],
                        'start_at' => $category['time_from'],
                        'end_at' => $category['time_to'],
                        'time' => $category['time_from'] != null ? 'true' : 'false',
                        'old_id' => $category['id']
                    ]);

                    // get Category days
                    if (get_category_days($category['id']) != null)
                    {
                        if (is_array(get_category_days($category['id'])) || is_object(get_category_days($category['id']))) {
                            if (count(get_category_days($category['id'])) > 0) {
                                foreach (get_category_days($category['id']) as $catDay) {
                                    \App\Models\MenuCategoryDay::create([
                                        'menu_category_id' => $cat->id,
                                        'day_id' => $catDay['day_id'],
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
        if (is_array(get_modifiers($res['2']['id'])) || is_object(get_modifiers($res['2']['id']))) {
            if (count(get_modifiers($res['2']['id'])) > 0) {
                foreach (get_modifiers($res['2']['id']) as $modifier) {
                    \App\Models\Modifier::create([
                        'restaurant_id' => $restaurant->id,
                        'name_ar' => $modifier['name_ar'],
                        'name_en' => $modifier['name_en'],
                        'is_ready' => 'true',
                        'old_id' => $modifier['id'],
                    ]);
                }
            }
        }
        if (is_array(get_options($res['2']['id'])) || is_object(get_options($res['2']['id']))) {
            if (count(get_options($res['2']['id'])) > 0) {
                if (is_array(get_options($res['2']['id'])) || is_object(get_options($res['2']['id']))) {
                    foreach (get_options($res['2']['id']) as $option) {
                        \App\Models\Option::create([
                            'restaurant_id' => $restaurant->id,
                            'name_ar' => $option['name_ar'],
                            'name_en' => $option['name'],
                            'modifier_id' => \App\Models\Modifier::whereOldId($option['main_addition_id'])->first()->id,
                            'is_active' => $option['active'] == 1 ? 'true' : 'false',
                            'price' => $option['price'],
                            'calories' => $option['calories'],
                            'foodics_id' => $option['foodics_id'],
                            'old_id' => $option['id'],
                        ]);
                    }
                }
            }
        }
        get_meals($res['2']['id'] , $restaurant->id);

        echo 'تم أضافه المطعم بنجاح';
    }
});


Route::get('/get_branch_menu/{old_id}/{new_id}' , function ($old , $new){

    // check if the restaurant are created before
    $branch = \App\Models\Branch::find($new);
    // create restaurant categories
    if (is_array(get_categories($old)) || is_object(get_categories($old))) {
        if (count(get_categories($old)) > 0) {
            foreach (get_categories($old) as $category) {
                $imageC = null;
                if ($category['image'] != null)
                {
                    $infoC = pathinfo('https://old.easymenu.site/uploads/categories/' . $category['image']);
                    $contentsC = file_get_contents('https://old.easymenu.site/uploads/categories/' . $category['image']);
                    $fileC = '/tmp/' . $infoC['basename'];
                    file_put_contents($fileC, $contentsC);

                    $imageC = $infoC['basename'];
                    $destinationPathC = public_path('/' . 'uploads/menu_categories');
                    $imgC = Image::make($fileC);
                    $imgC->resize(500, 500, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPathC . '/' . $imageC);
                }
                $cat = \App\Models\MenuCategory::create([
                    'restaurant_id' => $branch->restaurant->id,
                    'branch_id' => $branch->id,
                    'name_ar' => $category['name_ar'],
                    'name_en' => $category['name'],
                    'photo' => $imageC,
                    'foodics_image' => $category['foodics_image'],
                    'foodics_id' => $category['foodics_id'],
                    'active' => $category['active'] == 1 ? 'true' : 'false',
                    'arrange' => $category['arranging'],
                    'start_at' => $category['time_from'],
                    'end_at' => $category['time_to'],
                    'time' => $category['time_from'] != null ? 'true' : 'false',
                    'old_id' => $category['id']
                ]);

                // get Category days
                if (get_category_days($category['id']) != null)
                {
                    if (is_array(get_category_days($category['id'])) || is_object(get_category_days($category['id']))) {
                        if (count(get_category_days($category['id'])) > 0) {
                            foreach (get_category_days($category['id']) as $catDay) {
                                \App\Models\MenuCategoryDay::create([
                                    'menu_category_id' => $cat->id,
                                    'day_id' => $catDay['day_id'],
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
    if (is_array(get_modifiers($old)) || is_object(get_modifiers($old))) {
        if (count(get_modifiers($old)) > 0) {
            foreach (get_modifiers($old) as $modifier) {
                \App\Models\Modifier::create([
                    'restaurant_id' => $branch->restaurant->id,
                    'name_ar' => $modifier['name_ar'],
                    'name_en' => $modifier['name_en'],
                    'is_ready' => 'true',
                    'old_id' => $modifier['id'],
                ]);
            }
        }
    }
    if (is_array(get_options($old)) || is_object(get_options($old))) {
        if (count(get_options($old)) > 0) {
            if (is_array(get_options($old)) || is_object(get_options($old))) {
                foreach (get_options($old) as $option) {
                    \App\Models\Option::create([
                        'restaurant_id' => $branch->restaurant->id,
                        'name_ar' => $option['name_ar'],
                        'name_en' => $option['name'],
                        'modifier_id' => \App\Models\Modifier::whereOldId($option['main_addition_id'])->first()->id,
                        'is_active' => $option['active'] == 1 ? 'true' : 'false',
                        'price' => $option['price'],
                        'calories' => $option['calories'],
                        'foodics_id' => $option['foodics_id'],
                        'old_id' => $option['id'],
                    ]);
                }
            }
        }
    }
    get_meals($old , $branch->restaurant->id , $branch->id);
    echo 'تم أضافه الفرع بنجاح';
});


Route::get('/pull_branch_menu/{id}/{dd}' , function ($id , $dd){
    $branch = Branch::find($id);
    // 1- get menu cats
    $menu_cats = \App\Models\MenuCategory::whereBranchId($branch->id)->get();
    foreach ($menu_cats as $cat)
    {
        $category = \App\Models\MenuCategory::create([
            'restaurant_id' => $branch->restaurant->id,
            'branch_id' => $dd,
            'name_ar' => $cat->name_ar,
            'name_en' => $cat->name_en,
            'photo' => $cat->photo,
            'foodics_image' => $cat->foodics_image,
            'foodics_id' => $cat->foodics_id,
            'active' => $cat->active,
            'arrange' => $cat->arrange,
            'start_at' => $cat->start_at,
            'end_at' => $cat->end_at,
            'time' => $cat->time,
            'old_id' => $cat->id,
        ]);
        $days = \App\Models\MenuCategoryDay::where('menu_category_id' , $cat->id)->get();
        if ($days->count() > 0)
        {
            foreach ($days as $day)
            {
                \App\Models\MenuCategoryDay::create([
                    'menu_category_id' => $category->id,
                    'day_id' => $day->day_id,
                ]);
            }
        }
    }
    // 2 meals
    $meals = \App\Models\Product::whereBranchId($id)->get();
    foreach ($meals as $meal)
    {
        $product = \App\Models\Product::create([
            'restaurant_id' => $branch->restaurant->id,
            'branch_id' => $dd,
            'menu_category_id' => \App\Models\MenuCategory::where('old_id', $meal->menu_category_id)->first()->id,
            'name_ar' => $meal->name_ar,
            'name_en' => $meal->name_en,
            'price' => $meal->price,
            'calories' => $meal->calories,
            'arrange' => $meal->arrange,
            'photo' => $meal->photo,
            'active' => $meal->active,
            'start_at' => $meal->start_at,
            'end_at' => $meal->end_at,
            'time' => $meal->time,
            'description_ar' => $meal->description_ar,
            'description_en' => $meal->description_en,
            'foodics_image' => $meal->foodics_image,
            'foodics_id' => $meal->foodics_id,
            'old_id' => $meal->id,
        ]);

        // store the product photos
        $photos = \App\Models\ProductPhoto::whereProductId($meal->id)->get();
        if ($photos->count() > 0)
        {
            foreach ($photos as $photo)
            {
                \App\Models\ProductPhoto::create([
                    'product_id' => $product->id,
                    'photo' => $photo->photo,
                ]);
            }
        }

        // get products sizes
        $sizes = \App\Models\ProductSize::whereProductId($meal->id)->get();
        if ($sizes->count() > 0)
        {
            foreach ($sizes as $size)
            {
                \App\Models\ProductSize::create([
                    'name_ar' => $size->name_ar,
                    'name_en' => $size->name_en,
                    'price' => $size->price,
                    'calories' => $size->calories,
                    'product_id' => $product->id,
                ]);
            }
        }

        // get products modifires
        $meal_mods = \App\Models\ProductModifier::whereProductId($meal->id)->get();
        if ($meal_mods->count() > 0)
        {
            foreach ($meal_mods as $meal_mod)
            {
                \App\Models\ProductModifier::create([
                    'product_id' => $product->id,
                    'modifier_id' => $meal_mod->modifier_id,
                ]);
            }
        }
        // get products options
        $meal_ops = \App\Models\ProductOption::whereProductId($meal->id)->get();
        if ($meal_ops->count() > 0)
        {
            foreach ($meal_ops as $meal_op)
            {
                \App\Models\ProductOption::create([
                    'product_id' => $product->id,
                    'modifier_id' => $meal_op->modifier_id,
                    'option_id' => $meal_op->option_id,
                    'min' => $meal_op->min,
                    'max' => $meal_op->max,
                ]);
            }
        }

        // get meals days
        $meal_days = \App\Models\ProductDay::whereProductId($meal->id)->get();
        if ($meal_days->count() > 0)
        {
            foreach ($meal_days as $meal_day)
            {
                \App\Models\ProductDay::create([
                    'product_id' => $product->id,
                    'day_id' => $meal_day->day_id,
                ]);
            }
        }

    }
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->group(function () {
    
    
    Route::post('webhook_url', [\App\Http\Controllers\HomeController::class , 'webhook']);
    // Route::get('webhook_url', [\App\Http\Controllers\HomeController::class , 'webhook']);
    
    
    Route::middleware(['cors', 'localization'])->group(function () {
        /*user register*/
        Route::controller(AuthController::class)->group(function () {
            Route::post('/register_mobile', 'registerMobile');
            Route::post('/phone_verification', 'register_phone_post');
            Route::post('/resend_code', 'resend_code');
            Route::post('/register', 'register');
            Route::post('/login', 'login');
            Route::post('/forget_password', 'forgetPassword');
            Route::post('/confirm_reset_code', 'confirmResetCode');
            Route::post('/reset_password', 'resetPassword');
            Route::get('/user_data/{id}', 'user_data');
        });
        /*end user register*/
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/terms_and_conditions', 'terms_and_conditions');
            Route::get('/about_us', 'about_us');
            Route::get('/contact_number', 'contact_number');
            Route::get('/banks', 'banks');
            Route::get('/get_user_data/{id}', 'get_user_data');
        });
    });

    Route::group(['middleware' => ['auth:api', 'cors', 'localization']], function () {
        Route::controller(AuthController::class)->group(function () {
            Route::post('/change_password', 'changePassword');
            Route::post('/change_phone_number', 'change_phone_number');
            Route::post('/check_code_change_phone_number', 'check_code_changeNumber');
            Route::post('/edit_account', 'user_edit_account');
            //===============logout========================
            Route::post('/logout', 'logout');
        });
        Route::post('/contact_us', 'Api\ProfileController@contact_us');
        Route::get('/list_notifications', 'Api\ApiController@listNotifications');
        Route::post('/delete_Notifications/{id}', 'Api\ApiController@delete_Notifications');
    });
});


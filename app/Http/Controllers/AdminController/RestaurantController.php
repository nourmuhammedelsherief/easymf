<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\CountryPackage;
use App\Models\MarketerOperation;
use App\Models\Package;
use App\Models\Report;
use App\Models\Restaurant;
use App\Models\RestaurantCategory;
use App\Models\SellerCode;
use App\Models\ServiceSubscription;
use App\Models\Setting;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request , $status)
    {
        $allData = ['restaurants' , 'status'];
        if ($status == 'active')
        {
            $restaurants = Restaurant::with('subscription')
                ->whereHas('subscription' , function ($q){
                    $q->where('status' , 'active');
                    $q->where('package_id' , 1);
                    $q->where('type' , 'restaurant');
                    $q->whereDate('end_at', '>=', now()->addDays(30));
                })
                ->where('status' , 'active')
                ->where('archive' , 'false')
                ->where('admin_activation' , 'true')
                ->orderBy('id' , 'desc')
                ->paginate(1000);
        }
        elseif($status == 'tentative_finished')
        {
            $restaurants = Restaurant::with('subscription')
                ->whereHas('subscription' , function ($q){
                    $q->where('status' , 'tentative_finished');
                    $q->where('package_id' , 1);
                    $q->where('type' , 'restaurant');
                })
                ->where('status' , 'tentative')
                ->where('archive' , 'false')
                ->where('admin_activation' , 'true')
                ->orderBy('id' , 'desc')
                ->paginate(1000);
        }
        elseif($status == 'tentative_active')
        {
            $restaurants = Restaurant::with('subscription')
                ->whereHas('subscription' , function ($q){
                    $q->where('status' , 'tentative');
                    $q->where('package_id' , 1);
                    $q->where('type' , 'restaurant');
                })
                ->where('status' , 'tentative')
                ->where('archive' , 'false')
                ->where('admin_activation' , 'true')
                ->orderBy('id' , 'desc')
                ->paginate(1000);
        }
        elseif($status == 'finished')
        {
            $restaurants = Restaurant::with('subscription')
                ->whereHas('subscription' , function ($q){
                    $q->where('status' , 'finished');
                    $q->where('package_id' , 1);
                    $q->where('type' , 'restaurant');
                })
                ->where('status' , 'finished')
                ->where('archive' , 'false')
                ->where('admin_activation' , 'true')
                ->orderBy('id' , 'desc')
                ->paginate(1000);
        }
        elseif($status == 'less_30_day')
        {
            $restaurants = Restaurant::with('subscription')
                ->whereHas('subscription' , function ($q){
                    $q->where('status' , 'active');
                    $q->where('package_id' , 1);
                    $q->where('type' , 'restaurant');
                    //    $q->where('end_at', '>', now()->subDays(30));
                    $q->whereDate('end_at', '<=', now()->addDays(30));
                })
                ->where('status' , 'active')
                ->where('archive' , 'false')
                ->where('admin_activation' , 'true')
                ->orderBy('id' , 'desc')
                ->paginate(1000);
        }
        elseif($status == 'inComplete')
        {
            $restaurants = Restaurant::where('status' , 'inComplete')
                ->where('archive' , 'false')
                ->orderBy('id' , 'desc')
                ->paginate(1000);
        }
        elseif($status == 'archived')
        {
            $restaurants = Restaurant::where('archive' , 'true')
                ->orderBy('id' , 'desc')
                ->paginate(1000);
        }elseif ($status == 'InActive')
        {
            $restaurants = Restaurant::where('admin_activation' , 'false')
                ->whereNotIn('status' , ['inComplete'])
                ->orderBy('id' , 'desc')
                ->paginate(1000);
        }elseif($status == 'categories'){
            $categoryId = (!empty($request->category_id) && is_numeric($request->category_id)) ? $request->category_id : 0;

            $restaurants = Restaurant::with('subscription' )
                ->whereHas('restaurantCategories' , function($query)use($categoryId){
                    $query->where('category_id' , $categoryId);
                })
                // ->whereHas('subscription' , function ($q){
                //     $q->where('status' , 'active');
                //     $q->where('package_id' , 1);
                //     $q->where('type' , 'restaurant');
                //     $q->whereDate('end_at', '>=', now()->addDays(30));
                // })
                // ->where('status' , 'active')
                // ->where('archive' , 'false')
                // ->where('admin_activation' , 'true')
                ->orderBy('id' , 'desc')
                ->paginate(1000);
            $category = Category::find($categoryId);
            $allData[] = 'category';
        }

        return view('admin.restaurants.index' , compact($allData));
    }

    public function branches($status)
    {
        if ($status == 'active')
        {
            $branches = Branch::with('subscription')
                ->whereHas('subscription' , function ($q){
                    $q->where('status' , 'active');
                    $q->whereDate('end_at', '>=', now()->addDays(30));
                })
                ->where('status' , 'active')
                ->where('archive' , 'false')
                ->where('main' , 'false')
                ->orderBy('id' , 'desc')
                ->get();
        }elseif($status == 'tentativeA')
        {
            $branches = Branch::with('subscription')
                ->whereHas('subscription' , function ($q){
                    $q->where('status' , 'tentative');
                })
                ->where('status' , 'tentative')
                ->where('main' , 'false')
                ->where('archive' , 'false')
                ->orderBy('id' , 'desc')
                ->get();
        }
        elseif($status == 'tentative_finished')
        {
            $branches = Branch::with('subscription')
                ->whereHas('subscription' , function ($q){
                    $q->where('status' , 'tentative_finished');
                })
                ->where('status' , 'tentative_finished')
                ->where('main' , 'false')
                ->where('archive' , 'false')
                ->orderBy('id' , 'desc')
                ->get();
        }
        elseif($status == 'finished')
        {
            $branches = Branch::with('subscription')
                ->whereHas('subscription' , function ($q){
                    $q->where('status' , 'finished');
                })
                ->where('status' , 'finished')
                ->where('main' , 'false')
                ->where('archive' , 'false')
                ->orderBy('id' , 'desc')
                ->get();
        }
        elseif($status == 'less_30_day')
        {
            $branches = Branch::with('subscription')
                ->whereHas('subscription' , function ($q){
                    $q->where('status' , 'active');
                    $q->where('package_id' , 1);
//                    $q->where('end_at', '>', now()->subDays(30));
                    $q->whereDate('end_at', '<=', now()->addDays(30));
                })
                ->where('status' , 'active')
                ->where('archive' , 'false')
                ->where('main' , 'false')
                ->orderBy('id' , 'desc')
                ->get();
        }
        elseif($status == 'archived')
        {
            $branches = Branch::where('archive' , 'true')
                ->where('main' , 'false')
                ->orderBy('id' , 'desc')
                ->get();
        }elseif ($status == 'in_complete')
        {
            $branches = Branch::where('status' , 'not_active')
                ->where('main' , 'false')
                ->orderBy('id' , 'desc')
                ->get();
        }

        return view('admin.restaurants.branches' , compact('branches' , 'status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::where('active' , 'true')->get();
        $categories = Category::all();
        return view('admin.restaurants.create' , compact('countries' , 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request , [
            'email' => 'required|string|email|max:255|unique:restaurants',
            'password' => 'required|string|min:8|confirmed',
            'country_id' => 'required|exists:countries,id',
            'phone_number' => ['required', 'unique:restaurants', 'regex:/^((05)|(01))[0-9]{8}/'],
            'name_en' => 'required|string|max:255|regex:/(^([\pL\s\-]+)([a-zA-Z]+)(\d+)?$)/u',
            'name_ar' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'name_barcode'  => 'required|string|max:191|unique:restaurants|regex:/(^([\pL\s\-\_]+)([a-zA-Z]+)(\d+)?$)/u',
//            'seller_code' => 'nullable|exists:seller_codes,seller_name',
            'category_id' => 'required',
            'end_at' => 'required|date',
//            'latitude' => 'required',
        ]);
        $barcode  = str_replace(' ' , '-' , $request->name_barcode);
        $restaurant = Restaurant::create([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'country_id' => $request->country_id,
            'phone_number' => $request->phone_number,
            'city_id' => $request->city_id,
            'name_barcode' =>$barcode ,
            'package_id' => 1,
            'status' => 'tentative',
        ]);

        defaultResturantData($restaurant);
        // create the main Branch for this  restaurant
        $barcode  = str_replace(' ' , '-' , $request->name_barcode);
        $branch = Branch::create([
            'restaurant_id' => $restaurant->id,
            'country_id'    => $restaurant->country_id,
            'city_id'       => $restaurant->city_id,
            'name_ar'       => $restaurant->name_ar,
            'name_en'       => $restaurant->name_en,
            'name_barcode'  => $barcode,
            'main'          => 'true',
            'status'        => 'active',
            'email'         => $restaurant->email,
            'phone_number'  => $restaurant->phone_number,
        ]);

        // create restaurant subscription
        $tax = Setting::first()->tax;
        $price = Package::find(1)->price;
        $tax_value = ($tax  * $price) / 100;
        $price += $tax_value;
        $subscription = Subscription::create([
            'package_id' => 1,
            'restaurant_id' => $restaurant->id,
            'branch_id' => $branch->id,
            'price' => $price,
            'status' => 'tentative',    // active ,notActive , tentative , finished
            'end_at' => $request->end_at,
            'type' => 'restaurant',
            'is_new' => 1 ,
            'tax_value' => $tax_value,
        ]);

        // update the main branch
        $main_branch = Branch::whereRestaurantId($restaurant->id)
            ->where('main' , 'true')
            ->first();
        $main_branch->update([
            'status'  => 'active',
        ]);
        $main_branch->subscription->update([
            'status'  => 'tentative',
            'end_at'  => $request->end_at,
        ]);
        // store restaurant categories
        if ($request->category_id != null) {
            foreach ($request->category_id as $category) {
                RestaurantCategory::create([
                    'category_id' => $category,
                    'restaurant_id' => $restaurant->id,
                ]);
            }
        }
        Report::create([
            'restaurant_id' => $restaurant->id,
            'branch_id'  => $branch->id,
            'amount' => $price,
            'discount' => 0,
            'status' => 'registered',
            'type' => 'restaurant',
            'tax_value' => $tax_value,
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('restaurants' , 'active');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Restaurant::findOrFail($id);
        $cities = City::whereCountryId($user->country_id)->get();
        return view('admin.restaurants.show' , compact('user' , 'cities'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $countries = Country::where('active' , 'true')->get();
        $categories = Category::all();
        return view('admin.restaurants.edit' , compact('countries' ,'restaurant', 'categories'));
    }

    public function editInComplete($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $countries = Country::where('active' , 'true')->get();
        $categories = Category::all();
        $inComplete = true;
        return view('admin.restaurants.edit' , compact('countries' , 'inComplete' ,'restaurant', 'categories'));
    }

    public function updateInComplete(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $this->validate($request , [
            'email' => 'required|string|email|max:255|unique:restaurants,email,'.$restaurant->id,
            'password' => 'nullable|string|min:8|confirmed',
            'country_id' => 'required|exists:countries,id',
            'phone_number' => ['required', 'unique:restaurants,phone_number,' .$restaurant->id, 'regex:/^((05)|(01))[0-9]{8}/'],
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'name_barcode'  => 'required|string|max:191|unique:restaurants,name_barcode,'.$restaurant->id,
//            'seller_code' => 'nullable|exists:seller_codes,seller_name',
        ]);

        $package = $restaurant->package;
        // update the main branch
        $check_branch = Branch::whereRestaurantId($restaurant->id)->with('subscription')
            ->where('main' , 'true')
            ->first();
        // return $check_branch;
        $barcode  = str_replace(' ' , '-' , $request->name_barcode);
        $restaurant->update([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'email' => $request->email,
            'password' => $request->password == null ? $restaurant->password : Hash::make($request->password),
            'country_id' => $request->country_id,
            'phone_number' => $request->phone_number,
            'city_id' => $request->city_id,
            'name_barcode' => $barcode,
            'status' => $restaurant->status == 'inComplete' ? 'tentative' : 'active',
            'package_id' =>$package->id,
            'menu_arrange' => 'true',
            'product_arrange' => 'true',
        ]);
        if (isset($check_branch->id))
        {
            $check_branch->update([
                'status'  => 'active',
            ]);
            $check_price = CountryPackage::whereCountry_id($restaurant->country_id)
                ->wherePackageId($request->package_id)
                ->first();
            if ($check_price == null)
            {

                $package_actual_price = $package->price;
            }else{
                $package_actual_price = $check_price->price;
            }
            if(!isset($check_branch->subscription->id)):

                $subscription = Subscription::create([
                    'package_id' => $package->id,
                    'restaurant_id' => $restaurant->id,
                    'branch_id' => $check_branch->id,
                    'price' => $package_actual_price,
                    'status' => 'tentative',    // active ,notActive , tentative , finished
                    'end_at' => Carbon::now()->addDays(Setting::find(1)->tentative_period),
                    'type' => 'restaurant',
                ]);
            else:
                $subscription = $check_branch->subscription;
            endif;
            // return $check_branch->subscription;
            $subscription->update([
                'status'  => $restaurant->status == 'inComplete' ? 'tentative' : 'active',
            ]);

            if ($seller_code = $subscription->seller_code) {
                $package_price = $package_actual_price;
                $discount_percentage = $seller_code->code_percentage;
                $discount = ($package_price * $discount_percentage) / 1000;
                $price_after_percentage = $package_price - $discount;
                $commission = $package_price * ($seller_code->percentage - $seller_code->code_percentage) / 1000;
                $total_commission = $seller_code->commission + $commission;
                $seller_code->update([
                    'commission' => $total_commission,
                ]);
                // store this operation to marketer history
                if($markterOperation = MarketerOperation::where('restaurant_id' , $restaurant->id)->where('seller_code_id' , $seller_code->id)->where('status' , 'not_done')->first()):
                    $markterOperation->update([
                        'marketer_id'   => $seller_code->marketer_id,
                        'seller_code_id' => $seller_code->id,
                        'restaurant_id' => $restaurant->id,
                        'subscription_id' => $subscription->id,
                        'status'    => 'not_done',
                        'amount'    => $total_commission,
                    ]);
                else:
                    MarketerOperation::create([
                        'marketer_id'   => $seller_code->marketer_id,
                        'seller_code_id' => $seller_code->id,
                        'restaurant_id' => $restaurant->id,
                        'subscription_id' => $subscription->id,
                        'status'    => 'not_done',
                        'amount'    => $total_commission,
                    ]);
                endif;

                // $restaurant->update([
                //     'seller_code_id' => $seller_code->id,
                //     'price' => $price_after_percentage,
                // ]);
            }
            $main_branch = $check_branch;
        }else{

            $main_branch = Branch::create([
                'restaurant_id' => $restaurant->id,
                'country_id'    => $restaurant->country_id,
                'city_id'       => $restaurant->city_id,
                'name_ar'       => $restaurant->name_ar,
                'name_en'       => $restaurant->name_en,
                'name_barcode'  => $restaurant->name_barcode,
                'main'          => 'true',
                'status'        => 'active',
                'email'         => $restaurant->email,
                'phone_number'  => $restaurant->phone_number,
            ]);
        }
        if (empty($restaurant->subscription->id))
        {
            $check_price = CountryPackage::whereCountry_id($restaurant->country_id)
                ->wherePackageId($request->package_id)
                ->first();
            if ($check_price == null)
            {

                $package_actual_price = $package->price;
            }else{
                $package_actual_price = $check_price->price;
            }
            $subscription = Subscription::create([
                'package_id' => $package->id,
                'restaurant_id' => $restaurant->id,
                'branch_id' => $main_branch->id,
                'price' => $package_actual_price,
                'status' => 'tentative',    // active ,notActive , tentative , finished
                'end_at' => Carbon::now()->addDays(Setting::find(1)->tentative_period),
                'type' => 'restaurant',
            ]);

        }
        defaultResturantData($restaurant);
        $restaurant->update([
            'menu_arrange' => 'true',
            'product_arrange' => 'true',
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('restaurants' , 'tentative_active');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $this->validate($request , [
            'email' => 'required|string|email|max:255|unique:restaurants,email,'.$restaurant->id,
            'password' => 'nullable|string|min:8|confirmed',
            'country_id' => 'required|exists:countries,id',
            'phone_number' => ['required', 'unique:restaurants,phone_number,' .$restaurant->id, 'regex:/^((05)|(01)|())[0-9]{8}/'],
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'end_at'   => 'sometimes|date',
            'name_barcode'  => 'required|string|max:191|unique:restaurants,name_barcode,'.$restaurant->id,

        ]);

        // update the main branch
        $check_branch = Branch::whereRestaurantId($restaurant->id)->with('subscription')
            ->where('main' , 'true')
            ->first();
        $barcode  = str_replace(' ' , '-' , $request->name_barcode);
        $restaurant->update([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'email' => $request->email,
            'password' => $request->password == null ? $restaurant->password : Hash::make($request->password),
            'country_id' => $request->country_id,
            'phone_number' => $request->phone_number,
            'city_id' => $request->city_id,
            'name_barcode' => $barcode,
            'menu_arrange' => 'true',
            'product_arrange' => 'true',
            'status' => $restaurant->status == 'tentative' ? 'tentative' : 'active',
        ]);
        if ($restaurant->subscription != null and $request->end_at != null)
        {
            $restaurant->subscription->update([
                'end_at'  => $request->end_at,
            ]);
        }

        if (isset($check_branch->id))
        {
            $check_branch->update([
                'status'  => 'active',
            ]);
            $check_branch->subscription->update([
                'status'  => $restaurant->status == 'tentative' ? 'tentative' : 'active',
            ]);
            $main_branch = $check_branch;
        }else{
            $main_branch = Branch::create([
                'restaurant_id' => $restaurant->id,
                'country_id'    => $restaurant->country_id,
                'city_id'       => $restaurant->city_id,
                'name_ar'       => $restaurant->name_ar,
                'name_en'       => $restaurant->name_en,
                'name_barcode'  => $restaurant->name_barcode,
                'main'          => 'true',
                'status'        => 'active',
                'email'         => $restaurant->email,
                'phone_number'  => $restaurant->phone_number,
//            'latitude'      => $restaurant->latitude,
//            'longitude'     => $restaurant->longitude,
            ]);
        }
        if (empty($restaurant->subscription->id))
        {
            $package = $restaurant->package;
            $check_price = CountryPackage::whereCountry_id($restaurant->country_id)
                ->wherePackageId($package->id)
                ->first();
            if ($check_price == null)
            {

                $package_actual_price = $package->price;
            }else{
                $package_actual_price = $check_price->price;
            }
            Subscription::create([
                'package_id' => $package->id,
                'restaurant_id' => $restaurant->id,
                'branch_id' => $main_branch->id,
                'price' => $package_actual_price,
                'status' => 'tentative',    // active ,notActive , tentative , finished
                'end_at' => $request->end_at == null ? Carbon::now()->addDays(Setting::find(1)->tentative_period) : $request->end_at,
                'type' => 'restaurant',
            ]);
        }

//
//        // store restaurant categories
//        if ($request->category_id != null) {
//            foreach ($request->category_id as $category) {
//                RestaurantCategory::create([
//                    'category_id' => $category,
//                    'restaurant_id' => $restaurant->id,
//                ]);
//            }
//        }
        if(!empty($request->password)):
            // return $restaurant;
            Auth::guard('restaurant')->logoutOtherDevices2($restaurant , $request->password);
        endif;
        flash(trans('messages.updated'))->success();
        return redirect()->route('restaurants' , 'active');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        if ($restaurant->logo != null)
        {
            if ($restaurant->logo != 'logo.png')
            {
                @unlink(public_path('/uploads/restaurants/logo/' . $restaurant->logo));
            }
        }
        if ($restaurant->sensitivities->count() > 0)
        {
            foreach ($restaurant->sensitivities as $sensitivity)
            {
                if ($sensitivity->photo != 'fish.png' && $sensitivity->photo != 'egg.png' && $sensitivity->photo != 'hop.png' && $sensitivity->photo != 'aqra.png' && $sensitivity->photo != 'milk.png' && $sensitivity->photo != 'kardal.png' && $sensitivity->photo != 'raky.png' && $sensitivity->photo != 'butter.png' && $sensitivity->photo != 'capret.png' && $sensitivity->photo != 'rfs.png' && $sensitivity->photo != 'kago.png' && $sensitivity->photo != 'smsm.png' && $sensitivity->photo != 'soia.png' && $sensitivity->photo != 'terms.png')
                {
                    @unlink(public_path('/sensitivities/' . $sensitivity->photo));
                }
                $sensitivity->delete();
            }
        }
        if ($restaurant->products->count() > 0)
        {
            foreach ($restaurant->products  as $product)
            {
                if ($product->photo != null)
                {
                    @unlink(public_path('/uploads/products/' . $product->photo));
                }
                $product->delete();
            }
        }
        $services_subscriptions = ServiceSubscription::whereRestaurantId($restaurant->id)->delete();
        $restaurant->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->back();
    }

    public function control_service_subscription($id)
    {
        $serviceSubscription = ServiceSubscription::findOrFail($id);
        $restaurant = $serviceSubscription->restaurant;
        return view('admin.restaurants.subscriptions.control_service' , compact('restaurant' , 'serviceSubscription'));
    }
    public function controlServiceChanges(Request $request , $id)
    {
        $this->validate($request , [
            'days' => 'required|numeric',
        ]);
        $serviceSubscription = ServiceSubscription::findOrFail($id);
        
        $serviceSubscription->update([
        //            'package_id' => $request->package_id == null ? $serviceSubscription->package->id : $request->package_id,
            'end_at'     => $request->days == null ? $serviceSubscription->end_at : Carbon::now()->addDays($request->days),
            'status'     => $serviceSubscription->status == 'tentative' ? 'tentative' : 'active',
        ]);
        
        flash(trans('messages.updated'))->success();
        return redirect()->route('admin.service.service_restaurants' , [$serviceSubscription->service_id , $serviceSubscription->status]);
    }
    public function control_subscription($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        return view('admin.restaurants.subscriptions.control' , compact('restaurant'));
    }
    public function controlChanges(Request $request , $id)
    {
        $this->validate($request , [
            'days' => 'required|numeric',
        ]);
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->subscription->update([
        //            'package_id' => $request->package_id == null ? $restaurant->subscription->package->id : $request->package_id,
            'end_at'     => $request->days == null ? $restaurant->subscription->end_at : Carbon::now()->addDays($request->days),
            'status'     => $restaurant->status == 'tentative' ? 'tentative' : 'active',
        ]);
        $restaurant->update([
            'status'     => $restaurant->status == 'tentative' ? 'tentative' : 'active',
        ]);
        // update the main branch
        $main_branch = Branch::whereRestaurantId($restaurant->id)
            ->where('main' , 'true')
            ->first();
        $main_branch->update([
            'status'  => 'active',
        ]);
        $main_branch->subscription->update([
            'status'  => $restaurant->status == 'tentative' ? 'tentative' : 'active',
            'end_at'     => $request->days == null ? $restaurant->subscription->end_at : Carbon::now()->addDays($request->days),
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('showRestaurant' , $restaurant->id);
    }

    public function control_branch_subscription($id)
    {
        $branch = Branch::findOrFail($id);
        return view('admin.restaurants.subscriptions.control_branch' , compact('branch'));
    }
    public function controlBranchChanges(Request $request , $id)
    {
        $this->validate($request , [
            'days' => 'required|numeric',
        ]);
        $branch = Branch::findOrFail($id);
        $end_at = $request->days == null ? $branch->subscription->end_at : (Carbon::now() > $branch->subscription->end_at ? Carbon::now()->addDays($request->days) : $branch->subscription->end_at->addDays($request->days));
        $branch->subscription->update([
            'end_at'     => $end_at,
            'status'     => ($branch->status == 'tentative' or $branch->status == 'tentative_finished') ? 'tentative' : 'active',
        ]);
        $branch->update([
            'status'     => ($branch->status == 'tentative' or $branch->status == 'tentative_finished') ? 'tentative' : 'active',
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->back();
    }

    public function ArchiveRestaurant(Request $request , $id , $state)
    {
        $restaurant = Restaurant::findOrFail($id);
        if(!empty($request->archive_category_id)) $archive = $request->archive_category_id;
        elseif($state == 'true'){
            flash('يرجي اختيار سبب الارشفة')->error();
            return redirect()->back();
        }

        $restaurant->update([
            'archive'   => $state,
            'archive_category_id' => $state == 'false' ? $restaurant->archive_category_id : $archive
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->back();
    }
    public function ArchiveBranch($id , $state)
    {
        $branch = Branch::findOrFail($id);
        $branch->update([
            'archive'   => $state,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->back();
    }
    public function delete_branches($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->back();
    }

    public function edit_branch($id)
    {
        $branch = Branch::findOrFail($id);
        $countries = Country::where('active' , 'true')->get();
        return view('admin.restaurants.branches.edit' , compact('branch' , 'countries'));
    }
    public function update_branch(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);
        $this->validate($request, [
            'city_id' => 'required|exists:cities,id',
            'name_ar' => 'nullable|string|max:191',
            'name_en' => 'nullable|string|max:191|unique:branches,name_barcode,' . $id,
//            'name_barcode' => 'required|string|max:191|unique:branches,name_barcode,' . $id,
        ]);
        if ($request->name_ar == null && $request->name_en == null) {
            flash(trans('messages.name_required'))->error();
            return redirect()->back();
        }
        $barcode = str_replace(' ', '-', $request->name_en);
        $branch->update([
            'city_id' => $request->city_id,
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'name_barcode' => $barcode,
        ]);
        if ($branch->main == 'true') {
            $barcode = str_replace(' ', '-', $request->name_en);
            $branch->restaurant->update([
                'city_id' => $request->city_id,
                'name_ar' => $request->name_ar,
                'name_en' => $request->name_en,
                'name_barcode' => $barcode,
            ]);
        }
        $branch->update([
            'status'  => 'active'
        ]);
        if ($branch->subscription != null)
        {
            $branch->subscription->update([
                'status'  => 'active'
            ]);
        }else{
            Subscription::create([
                'package_id'   => 1,
                'restaurant_id' => $branch->restaurant_id,
                'branch_id'     => $branch->id,
                'price'         => Package::find(1)->price,
                'status'        => 'active',
                'end_at'        => Carbon::now()->addMonths(Package::find(1)->duration),
                'type'          => 'branch',
            ]);
        }
        flash(trans('messages.updated'))->success();
        return redirect()->to(url('/admin/branches/active'));
    }

    public function ActiveRestaurant($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->update([
            'admin_activation' => 'true',
        ]);
        $subscription = Subscription::whereRestaurantId($restaurant->id)->first();
        if ($subscription)
        {
            $subscription->update([
                'end_at' => Carbon::now()->addDays(Setting::find(1)->tentative_period),
            ]);
        }
        return redirect()->back();
    }

}

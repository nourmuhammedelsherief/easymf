<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Jobs\SendMailCompleteRestaurantJob;
use App\Jobs\SendMailNewRestaurantJob;
use App\Models\Admin;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\CountryPackage;
use App\Models\History;
use App\Models\Marketer;
use App\Models\MarketerOperation;
use App\Models\Package;
use App\Models\Report;
use App\Models\Restaurant;
use App\Models\RestaurantCategory;
use App\Models\RestaurantDelivery;
use App\Models\RestaurantOffer;
use App\Models\RestaurantOfferPhoto;
use App\Models\RestaurantSensitivity;
use App\Models\RestaurantSlider;
use App\Models\RestaurantSocial;
use App\Models\SellerCode;
use App\Models\Setting;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:restaurant')->except('show_register','reset_password_post','reset_password', 'password_verification_post','password_verification','submitStep2', 'storeStep2', 'code_verification', 'phone_verification', 'submit_step1', 'resend_code' , 'forget_password' , 'forget_password_submit' , 'checkEmailAndPhone' , 'sellerRegisters' , 'sellerVerificationPhone' , 'sellerRestaurantPayment');
    }

    /**
     * @show the first phase of restaurants register
     * @show_register
     */
    public function show_register()
    {
        $countries = Country::with('cities')
            ->where('active' , 'true')
            ->orderBy('created_at' , 'asc')
            ->get();
        // $restaurant = Restaurant::findOrFail($id);
        // $cities = City::whereCountryId($restaurant->country_id)->get();
//        $packages = Package::whereIn('id' , [1,2])->get();
        $categories = Category::all();
        return view('restaurant.authAdmin.first_step_register', compact('countries'  , 'categories'));
    }

    public function sellerRegisters(Request $request , $code)
    {
        $date = date('Y-m-d');
        if(!$seller = SellerCode::where('active' , 'true' )->whereRaw('(start_at is null or start_at <= "'.$date.'") and (end_at is null or end_at >= "'.$date.'")')->where('custom_url' , $code)->orderBy('updated_at' , 'desc')->first()):
            abort(404);
        endif;
        if($request->method() == 'POST'):
            $validator = \Validator::make($request->all(), [
                'email' => 'required|string|email|max:255|unique:restaurants',
                'password' => 'required|string|min:8|confirmed',
                'country_id' => 'required|exists:countries,id',
                'phone_number' => ['required', 'unique:restaurants', 'regex:/^((05)|(01)|())[0-9]{8}/'],
                'name_en' => 'required|string|max:255|regex:/(^([\pL\s\-]+)([a-zA-Z]+)(\d+)?$)/u',
                'name_ar' => 'required|string|max:255',
                'city_id' => 'required|exists:cities,id',
                //   |regex:/(^([\pL\s\-]+)([a-zA-Z]+)(\d+)?$)/u
                //    'name_barcode'  => 'required|string|max:191|unique:restaurants',
                'seller_code' => 'nullable|exists:seller_codes,seller_name',
                //    'package_id' => 'required|exists:packages,id',
                'answer_id' => 'nullable',
                'category_id' => 'required|array|min:1',
                // 'category_id.*' => 'required|integer|exists:menu_categories,id'
            ] , [
                'category_id.*' => trans('messages.error_category_id_invalid') ,
                'category_id.*.*' => trans('messages.error_category_id_invalid') ,
                'name_en.*' => trans('messages.error_name_en_invaild') ,
                'name_ar.*' => trans('messages.error_name_ar_invalid') ,
                'city_id.*' => trans('messages.error_city_id_invaild') ,
                // 'answer_id.*' => trans('messages.error_answer_id_fail'),
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first() ,
                    'errors' => $validator->errors()]);
            }
            // check barcode name
            $barcodeName =  str_replace(' ' , '-' , $request->name_en);
            if(Restaurant::where('name_barcode' , $barcodeName)->count() > 0){
                return response([
                    'status' => false,
                    'message' => trans('messages.error_barcode_name_exist')  ,
                    'errors' => [
                        'name_en' => [[trans('messages.error_barcode_name_exist') ]]
                    ] ,
                ]);
            }
            // create new restaurant
            $code = mt_rand(1000, 9999);
            // $code = 1111; // test
            $packageId = !empty($seller->package_id) ? $seller->package_id : 1;
            $restaurant = Restaurant::create([
                'name_ar' => $request->name_ar,
                'name_en' => $request->name_en,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'country_id' => $request->country_id,
                'city_id' => $request->city_id ,
                'phone_number' => $request->phone_number,
                'phone_verification' => $code,
                'status' => 'inComplete',
                'package_id' => $packageId,
                'answer_id' => $request->answer_id,
                'name_barcode' =>$barcodeName ,
                'menu_arrange' => 'true',
                'product_arrange' => 'true',
                'admin_activation' => 'false',
            ]);
            if ($request->category_id != null) {
                foreach ($request->category_id as $category) {
                    RestaurantCategory::create([
                        'category_id' => $category,
                        'restaurant_id' => $restaurant->id,
                    ]);
                }
            }
            // seller info

            // store this operation to marketer history
            MarketerOperation::create([
                'marketer_id'   => $seller->marketer_id,
                'seller_code_id' => $seller->id,
                'restaurant_id' => $restaurant->id,
                'status'    => 'not_done',
                'amount'    => 0,
            ]);
            // send sms to restaurant owner
            $country = $restaurant->country->code;
            // send code to phone_number
            $msg = app()->getLocale() == 'ar' ? 'كود التحقق الخاص بك في أيزي منيو هو' . ' : ' . $code . '  ' . 'مؤسسة تقني' : 'EasyMenu verification code is : ' . $code. '  ' . 'مؤسسة تقني';
            $check = substr($restaurant->phone_number, 0, 2) === '05';
            if ($check == true) {
                $phone = $country . ltrim($restaurant->phone_number, '0');
            } else {
                $phone = $country . $restaurant->phone_number;
            }
            taqnyatSms($msg, $phone);


            dispatch(new SendMailNewRestaurantJob($restaurant));
            if ($restaurant) {
                return response([
                    'status' => true ,
                    'message' => trans('messages.send_code_success'),
                    'data' => [
                        'restaurant_id' => $restaurant->id ,
                    ] ,
                ]);
                return response()->json(['url' => url('/restaurant/phone_verification/' . $restaurant->id), 'msg' => trans('messages.success_register')]);
            }
        endif;

        $countries = Country::with('cities')->where('id' , $seller->country_id)
            ->where('active' , 'true')
            ->orderBy('created_at' , 'asc')
            ->get();

        $categories = Category::all();
        $banks = Bank::whereNull('restaurant_id')->where('country_id' , $seller->country_id)->get();

        $check_price = CountryPackage::whereCountry_id($seller->country_id)
            ->wherePackageId($seller->package_id)
            ->first();
        if ($check_price == null)
        {
            $package_actual_price = Package::find($seller->package_id)->price;
        }else{
            $package_actual_price = $check_price->price;
        }

        $package_price = $package_actual_price;
        $discount_percentage = $seller->code_percentage;
        $discount = ($package_price * $discount_percentage) / 100;
        $finalPrice = $package_price - $discount;
        $tax = Setting::find(1)->tax;
        $tax_value = $finalPrice * $tax / 100;
        $finalPrice = $finalPrice + $tax_value;
        $commission = $package_price * ($seller->percentage - $seller->code_percentage) / 100;
        $total_commission = $seller->commission + $commission;
        return view('restaurant.authAdmin.seller_register', compact('countries'  , 'categories' , 'seller' , 'banks' , 'package_price'  , 'discount_percentage' , 'discount' , 'finalPrice'));
    }
    public function sellerVerificationPhone(Request $request , $code , $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $this->validate($request, [
            'code' => 'required|numeric'
        ]);
        if ($request->code == $restaurant->phone_verification) {
            $restaurant->update([
                'phone_verification' => null,
            ]);
            flash(trans('messages.success_code'))->success();
            if($request->wantsJson()){
                return response([
                    'status' => 1,
                    'message' => trans('messages.success_code')
                ]);

            }
            return redirect()->route('restaurant.step2Register', $restaurant->id);
        } else {

            if($request->wantsJson()){
                return response([
                    'status' => false ,
                    'message' => trans('messages.error_code') ,
                    'request' => $request->all() ,
                ]);
            }
            flash(trans('messages.error_code'))->error();
            return redirect()->back();
        }
    }

    public function sellerRestaurantPayment(Request $request , $code , $id)
    {
        $date = date('Y-m-d');
        if(!$seller_code = SellerCode::where('active' , 'true' )->whereRaw('(start_at is null or start_at <= "'.$date.'") and (end_at is null or end_at >= "'.$date.'")')->where('custom_url' , $code)->orderBy('updated_at' , 'desc')->first()):
            abort(404);
        endif;
        $restaurant = Restaurant::findOrFail($id);
        $request->validate([
            'payment_type' => 'required|in:bank,online' ,
            'bank_id' => 'required_if:payment_type,bank|exists:banks,id' ,
            'photo' => 'required_if:payment_type,bank|image' ,
            'payment_method' => 'required_if:payment_type,online|in:visa,apple_pay,mada'
        ]);
        $barcode = str_replace(' ' , '-' , $restaurant->name_en);
        if($request->payment_type == 'bank'):

            $restaurant->update([
                'name_barcode' => $barcode ,
                'status' => 'tentative',
                'menu_arrange'  => 'true',
                'product_arrange' => 'true',
                'logo' => 'logo.png',
                'menu' =>'vertical',
                'description_ar' => 'نبذة عن المطعم . محتوي  يتم تغييره من خلال لوحة تحكم المطعم',
                'description_en' => 'A Brief About Restaurant Can Be Changed From Restaurant Control Panel',
                'information_ar' => ' يحتاج البالغون الى 2000 سعر حراري في المتوسط يومياً
يحتاج غير البالغون الى 1400 سعر حراري في المتوسط يومياً',
                'information_en' => 'Adults need an average of 2,000 calories per day',
            ]);

            // create the main Branch for this  restaurant
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
                //    'latitude'      => $restaurant->latitude,
                //    'longitude'     => $restaurant->longitude,
            ]);

            // create restaurant subscription
            $check_price = CountryPackage::whereCountry_id($restaurant->country_id)
                ->wherePackageId($restaurant->package_id)
                ->first();
            if ($check_price == null)
            {
                $package_actual_price = Package::find($restaurant->package_id)->price;
            }else{
                $package_actual_price = $check_price->price;
            }
            $tax = Setting::find(1)->tax;
            $tax_value = $package_actual_price * $tax / 100;
            $package_actual_price = $package_actual_price + $tax_value;
            $subscription = Subscription::create([
                'package_id' => $restaurant->package_id,
                'restaurant_id' => $restaurant->id,
                'branch_id' => $branch->id,
                'price' => $package_actual_price,
                'status' => 'tentative',    // active ,notActive , tentative , finished
                'end_at' => Carbon::now()->addDays(Setting::find(1)->tentative_period),
                'type' => 'restaurant',
                'seller_code_id' => $seller_code->id ,
                'bank_id' => $request->bank_id ,
                'payment_type' => 'bank' ,
                'payment' => true ,
                'tax_value'   => $tax_value,
                'discount_value' => 0,
                'transfer_photo' => UploadImage($request->file('photo'), 'photo', '/uploads/transfers'),
            ]);

            Report::create([
                'restaurant_id'  => $restaurant->id,
                'branch_id'      => $branch->id,
                'amount'         => $package_actual_price,
                'status'         => 'registered',
                'type'           => 'restaurant',
                'tax_value'      => $tax_value,
                'discount'       => 0,
            ]);

            if ($seller_code) {
                $package_price = $subscription->price;
                $discount_percentage = $seller_code->code_percentage;
                $discount = ($package_price * $discount_percentage) / 100;
                $price_after_percentage = $package_price - $discount;
                $commission = $package_price * ($seller_code->percentage - $seller_code->code_percentage) / 100;
                $total_commission = $seller_code->commission + $commission;
                $seller_code->update([
                    'commission' => $total_commission,
                ]);
                // store this operation to marketer history
                if($markterOperation = MarketerOperation::where('restaurant_id' , $restaurant->id)->where('seller_code_id' , $seller_code->id)->where('status' , 'not_done')->first()):
                    $markterOperation->update([
                        'marketer_id'   => $seller_code->marketer_id,
                        'seller_code_id' => $seller_code->id,
                        'subscription_id' => $subscription->id,
                        'status'    => 'not_done',
                        'amount'    => $total_commission,
                    ]);
                else:
                    MarketerOperation::create([
                        'marketer_id'   => $seller_code->marketer_id,
                        'seller_code_id' => $seller_code->id,
                        'subscription_id' => $subscription->id,
                        'status'    => 'not_done',
                        'amount'    => $total_commission,
                    ]);
                endif;

                $subscription->update([
                    'seller_code_id' => $seller_code->id,
                    'price' => $price_after_percentage,
                    'discount_value' => $discount,
                ]);
                // store restaurant register at reports
                Report::create([
                    'restaurant_id'  => $restaurant->id,
                    'branch_id'      => $branch->id,
                    'seller_code_id' => $seller_code->id,
                    'bank_id'        => $request->bank_id,
                    'amount'         => $price_after_percentage,
                    'discount'       => $discount,
                    'status'         => 'subscribed',
                    'type'           => 'restaurant',
                    'tax_value'      => $tax_value,
                    'transfer_photo' => UploadImage($request->file('photo'), 'photo', '/uploads/transfers'),
                ]);
            }else{
                // store restaurant register at reports
                Report::create([
                    'restaurant_id'  => $restaurant->id,
                    'branch_id'      => $branch->id,
                    'bank_id'        => $request->bank_id,
                    'amount'         => $package_actual_price,
                    'status'         => 'subscribed',
                    'type'           => 'restaurant',
                    'tax_value'      => $tax_value,
                    'transfer_photo' => UploadImage($request->file('photo'), 'photo', '/uploads/transfers'),
                ]);
            }

            if ($restaurant->package_id == 2)
            {
                // create order services
                // 1- create takeaway service
                gold_services($restaurant->id , 5 , $subscription->end_at);
                // 2- create delivery service
                gold_services($restaurant->id , 6 , $subscription->end_at);
                // 3 - create previous service
                gold_services($restaurant->id , 7 , $subscription->end_at);
                // 4- create Table service
                gold_services($restaurant->id , 8 , $subscription->end_at);
            }


            // create a trying content for restaurant
            // 1- create slider
            RestaurantSlider::create([
                'restaurant_id' => $restaurant->id,
                'photo'         => 'slider2.png',
            ]);
            RestaurantSlider::create([
                'restaurant_id' => $restaurant->id,
                'photo'         => 'slider1.png',
            ]);

            //4- create restaurant sensitivity
            // create_restaurant_sensitivity($restaurant->id);
            defaultPostersAndSens($restaurant);
            // send email to admins
            dispatch(new SendMailCompleteRestaurantJob($restaurant));
            Auth::guard('restaurant')->login($restaurant);
            // flash(trans('messages.success_full_register'))->success();
            return response()->json(['status' => true , 'url' => url('/restaurant/home'), 'message' => trans('messages.success_full_register')]);
        else: // online payment
            $check_price = CountryPackage::whereCountry_id($restaurant->country_id)
                ->wherePackageId($restaurant->package_id)
                ->first();
            if ($check_price == null)
            {
                $package_actual_price = Package::find($restaurant->package_id)->price;
            }else{
                $package_actual_price = $check_price->price;
            }
            $tax = Setting::find(1)->tax;
            $tax_value = $package_actual_price * $tax / 100;
            $package_actual_price = $package_actual_price + $tax_value;

            if(!$branch = Branch::whereRestaurantId($id)->where('main' , 'true')->first()):
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
                    //    'latitude'      => $restaurant->latitude,
                    //    'longitude'     => $restaurant->longitude,
                ]);
            endif;
            if(!$subscription = $branch->subscription){
                $subscription = Subscription::create([
                    'package_id' => $restaurant->package_id,
                    'restaurant_id' => $restaurant->id,
                    'branch_id' => $branch->id,
                    'price' => $package_actual_price,
                    'status' => 'tentative',    // active ,notActive , tentative , finished
                    'end_at' => Carbon::now()->addDays(Setting::find(1)->tentative_period),
                    'type' => 'restaurant',
                    'seller_code_id' => $seller_code->id ,
                    'payment' => true ,
                    'payment_type' => 'online',
                    'tax_value'   => $tax_value,
                    'discount_value' => 0,
                ]);
                Report::create([
                    'restaurant_id'  => $restaurant->id,
                    'branch_id'      => $branch->id,
                    'amount'         => $package_actual_price,
                    'status'         => 'registered',
                    'type'           => 'restaurant',
                    'tax_value'      => $tax_value,
                    'discount'       => 0,
                ]);
            }
            if ($seller_code) {
                $package_price = $package_actual_price;
                $discount_percentage = $seller_code->code_percentage;
                $discount = ($package_price * $discount_percentage) / 100;
                $price_after_percentage = $package_price - $discount;
                $commission = $package_price * ($seller_code->percentage - $seller_code->code_percentage) / 100;
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
                $subscription->update([
                    'discount_value'  => $discount,
                ]);

            }
            // online payment By My fatoorah

            $amount = check_restaurant_amount($branch->id , $price_after_percentage);
            if ($request->payment_method == 'visa') {
                $charge = 2;
            } elseif ($request->payment_method == 'mada') {
                $charge = 6;
            } elseif ($request->payment_method == 'apple_pay') {
                $charge = 11;
            } else {
                $charge = 2;
            }
            $name = $restaurant->name_en;
            $token = "1IzSLeOkLFQH1zljLzerCm2RpB8AjFZLf8MMhSy4d8rHb0h1uHqrBleBFlFv-M4SHnyeiWg2zWQraKYJGndFcFvaBIeCPDQNrNs1Zwo7O-4apFyAXXUVZOAKbYzncpn-1ay0BPxB1X5dNH0EuWQ9OTqzcnOI7c5Ola5Esxz0imTrbVuhmKZl7SWBrPCU_SOYt80BSDe5j2XY5skkK7e5TxDRbbibdZPM7S11aYmQ7xKmZvaSj916IhTNXuIZA73TYE4xxkXyL_8dhHobugeLHF58VJNBjMv2UvzEP0pSk0RqGs3-AeqYwD6S3BbVrOaGIbx4fwKlowd6SOoSqkMoD9tmFwgdbkMxzWKYGqxg7bcvB0r62jBqn4YXh0Ej8FO6mrTdWZo5bHviUDRPMitO2KQDvyXrlWnf74n9DxOfV7MbuutAr2K2L3hzCYkdfU0eqA3snq-3Xh1KFjRvd5QqofEt9ubK71Xd4TooKLyXD4hby5prqJTTEVi23bPsPmB1uZ0D1cawjjJB8eUTIwEiPTgdPOSIOTkVFm4OIrHEXT44NbJ9MyDInxmQK8-pGDr0rNeBeLo8J_uyHbfh_sVlpS7XT0d-ehaTDtENoyG0XMe7hgWDYWsNxIe1N3dXwwtyBXLgggAl6JvdDXBzY3wp13oFfTHdASeOtyO3d0zwvkF-9j0uF2WgHd-kqgEyQVV0_UifcbMafuYwTgbBod5iB1soNMoVcTfjlsmr8LV8CK7Nr8xq";
            $data = array(
                'PaymentMethodId' => $charge,
                'CustomerName' => $name,
                'DisplayCurrencyIso' => 'SAR',
                'MobileCountryCode' => $restaurant->country->code,
                'CustomerMobile' => $restaurant->phone_number,
                'CustomerEmail' => $restaurant->email,
                'InvoiceValue' => $amount,
                'CallBackUrl' => route('restaurant.seller.register.myfatoora'),
                'ErrorUrl' => url('/error'),
                'Language' => app()->getLocale(),
                'CustomerReference' => 'ref 1',
                'CustomerCivilId' => '12345678',
                'UserDefinedField' => 'Custom field',
                'ExpireDate' => '',
                'CustomerAddress' => array(
                    'Block' => '',
                    'Street' => '',
                    'HouseBuildingNo' => '',
                    'Address' => '',
                    'AddressInstructions' => '',
                ),
                'InvoiceItems' => [array(
                    'ItemName' => $name,
                    'Quantity' => '1',
                    'UnitPrice' => $amount,
                )],
            );
            $data = json_encode($data);
            $fatooraRes = MyFatoorah($token, $data);
            $result = json_decode($fatooraRes);
            if ($result != null) {
                if ($result->IsSuccess === true) {
                    $restaurant->subscription->update([
                        'invoice_id' => $result->Data->InvoiceId,
                    ]);
                    return redirect()->to($result->Data->PaymentURL);
                } else {
                    return redirect()->to(url('/error'));
                }
            } else {
                return redirect()->to(url('/error'));
            }

        endif; // end check payment type.

    }


    public function sellerCodeRestaurantMyFatoora(Request $request){

        $token = "1IzSLeOkLFQH1zljLzerCm2RpB8AjFZLf8MMhSy4d8rHb0h1uHqrBleBFlFv-M4SHnyeiWg2zWQraKYJGndFcFvaBIeCPDQNrNs1Zwo7O-4apFyAXXUVZOAKbYzncpn-1ay0BPxB1X5dNH0EuWQ9OTqzcnOI7c5Ola5Esxz0imTrbVuhmKZl7SWBrPCU_SOYt80BSDe5j2XY5skkK7e5TxDRbbibdZPM7S11aYmQ7xKmZvaSj916IhTNXuIZA73TYE4xxkXyL_8dhHobugeLHF58VJNBjMv2UvzEP0pSk0RqGs3-AeqYwD6S3BbVrOaGIbx4fwKlowd6SOoSqkMoD9tmFwgdbkMxzWKYGqxg7bcvB0r62jBqn4YXh0Ej8FO6mrTdWZo5bHviUDRPMitO2KQDvyXrlWnf74n9DxOfV7MbuutAr2K2L3hzCYkdfU0eqA3snq-3Xh1KFjRvd5QqofEt9ubK71Xd4TooKLyXD4hby5prqJTTEVi23bPsPmB1uZ0D1cawjjJB8eUTIwEiPTgdPOSIOTkVFm4OIrHEXT44NbJ9MyDInxmQK8-pGDr0rNeBeLo8J_uyHbfh_sVlpS7XT0d-ehaTDtENoyG0XMe7hgWDYWsNxIe1N3dXwwtyBXLgggAl6JvdDXBzY3wp13oFfTHdASeOtyO3d0zwvkF-9j0uF2WgHd-kqgEyQVV0_UifcbMafuYwTgbBod5iB1soNMoVcTfjlsmr8LV8CK7Nr8xq";
        $PaymentId = \Request::query('paymentId');

        $resData = MyFatoorahStatus($token, $PaymentId);
        $result = json_decode($resData);
        if ($result->IsSuccess === true && $result->Data->InvoiceStatus === "Paid") {
            $InvoiceId = $result->Data->InvoiceId;
            $subscription = Subscription::where('invoice_id', $InvoiceId)->firstOrFail();
            $restaurant = $subscription->restaurant;
            $barcode = str_replace(' ' , '-' , $restaurant->name_en);
            $end_at = Carbon::now()->addMonths($subscription->package->duration);
            $restaurant->update([
                'name_barcode' => $barcode ,
                'status' => 'tentative',
                'menu_arrange'  => 'true',
                'product_arrange' => 'true',
                'logo' => 'logo.png',
                'menu' =>'vertical',
                'description_ar' => 'نبذة عن المطعم . محتوي  يتم تغييره من خلال لوحة تحكم المطعم',
                'description_en' => 'A Brief About Restaurant Can Be Changed From Restaurant Control Panel',
                'information_ar' => ' يحتاج البالغون الى 2000 سعر حراري في المتوسط يومياً
يحتاج غير البالغون الى 1400 سعر حراري في المتوسط يومياً',
                'information_en' => 'Adults need an average of 2,000 calories per day',
            ]);

            $subscription->update([
                //                'invoice_id' => null,
                'status' => 'active',
                'end_at' => $end_at,
            ]);

            if ($subscription->type == 'restaurant') {
                $subscription->restaurant->update([
                    'status' => 'active',
                    'admin_activation' => 'true',
                ]);

                // update the main branch
                $main_branch = Branch::whereRestaurantId($subscription->restaurant->id)
                    ->where('main', 'true')
                    ->first();
                $main_branch->update([
                    'status' => 'active',
                ]);
                $main_branch->subscription->update([
                    'status' => 'active',
                    'end_at' => $end_at,
                ]);

                $operation = MarketerOperation::whereSubscriptionId($subscription->id)
                    ->where('status', 'not_done')
                    ->first();
                if ($operation) {
                    $operation->update([
                        'status' => 'done',
                    ]);
                    $balance = $operation->marketer->balance + $operation->amount;
                    $operation->marketer->update([
                        'balance' => $balance
                    ]);
                    $subscription->update(['seller_code_id' => $operation->seller_code_id]);
                }
                $isNew = true;
                if(History::where('branch_id' , $subscription->branch_id)->where('restaurant_id' , $subscription->restaurant->id)->count() > 0) $isNew = false;
                History::create([
                    'restaurant_id' => $subscription->restaurant->id,
                    'package_id' => $subscription->package->id,
                    'branch_id' => $subscription->branch_id,
                    'operation_date' => Carbon::now(),
                    'details' =>  !$isNew ? trans('messages.restaurantNewSubscription') :  trans('messages.restaurantRenewSubscription'),
                    'payment_type' => 'online',
                    'invoice_id' => $subscription->invoice_id,
                    'paid_amount' => $subscription->price,
                    'is_new' => $isNew,
                    'discount_value' => $subscription->discount_value,
                    'tax_value'      => $subscription->tax_value,
                ]);

                // store restaurant register at reports
                Report::create([
                    'restaurant_id'  => $restaurant->id,
                    'branch_id'      => $subscription->branch_id,
                    'amount'         => $subscription->price,
                    'status'         => 'subscribed',
                    'type'           => 'restaurant',
                    'invoice_id'     => $subscription->invoice_id,
                ]);
            }
            flash(trans('messages.onlinePaymentDone'))->success();
            if ($subscription->branch != null) {
                $subscription->branch->update([
                    'status' => 'active',
                ]);
                if ($subscription->type == 'branch') {
                    $isNew = true;
                    if(History::where('branch_id' , $subscription->branch_id)->where('restaurant_id' , $subscription->restaurant->id)->count() > 0) $isNew = false;
                    History::create([
                        'restaurant_id' => $subscription->restaurant->id,
                        'package_id' => $subscription->package->id,
                        'branch_id' => $subscription->branch_id,
                        'operation_date' => Carbon::now(),
                        'details'        => $isNew  ? trans('messages.branchCreatedDate') : trans('messages.branchRenewDate'),
                        'payment_type' => 'online',
                        'invoice_id' => $subscription->invoice_id,
                        'paid_amount' => $subscription->price,
                        'is_new' => $isNew,
                        'discount_value' => $subscription->discount_value,
                        'tax_value'      => $subscription->tax_value,
                    ]);

                    // store branch register at reports
                    Report::create([
                        'restaurant_id'  => $subscription->restaurant_id,
                        'branch_id'      => $subscription->branch_id,
                        'amount'         => $subscription->price,
                        'status'         => 'subscribed',
                        'type'           => 'branch',
                        'invoice_id'     => $subscription->invoice_id,
                    ]);
                }
            }
            if ($restaurant->package_id == 2)
            {
                // create order services
                // 1- create takeaway service
                gold_services($restaurant->id , 5 , $subscription->end_at);
                // 2- create delivery service
                gold_services($restaurant->id , 6 , $subscription->end_at);
                // 3 - create previous service
                gold_services($restaurant->id , 7 , $subscription->end_at);
                // 4- create Table service
                gold_services($restaurant->id , 8 , $subscription->end_at);
            }


            // create a trying content for restaurant
            // 1- create slider
            RestaurantSlider::create([
                'restaurant_id' => $restaurant->id,
                'photo'         => 'slider2.png',
            ]);
            RestaurantSlider::create([
                'restaurant_id' => $restaurant->id,
                'photo'         => 'slider1.png',
            ]);

            //4- create restaurant sensitivity
            // create_restaurant_sensitivity($restaurant->id);
            defaultPostersAndSens($restaurant);
            // send email to admins
            dispatch(new SendMailCompleteRestaurantJob($restaurant));
            Auth::guard('restaurant')->login($restaurant);
            // flash(trans('messages.success_full_register'))->success();
            return response()->json(['status' => true , 'url' => url('/restaurant/home'), 'message' => trans('messages.success_full_register')]);

        }
    }


    public function submit_step1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:restaurants',
            'password' => 'required|string|min:8|confirmed',
            'country_id' => 'required|exists:countries,id',
            'phone_number' => ['required', 'unique:restaurants', 'regex:/^((05)|(01)|())[0-9]{8}/'],
            'name_en' => 'required|string|max:255|regex:/(^([\pL\s\-]+)([a-zA-Z]+)(\d+)?$)/u',
            'name_ar' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            //   |regex:/(^([\pL\s\-]+)([a-zA-Z]+)(\d+)?$)/u
            //    'name_barcode'  => 'required|string|max:191|unique:restaurants',
            'seller_code' => 'nullable|exists:seller_codes,seller_name',
            //    'package_id' => 'required|exists:packages,id',
            'answer_id' => 'nullable',
            'category_id' => 'required|array|min:1',
        ], [
            'category_id.*' => trans('messages.error_category_id_invalid') ,
            'category_id.*.*' => trans('messages.error_category_id_invalid') ,
            'name_en.*' => trans('messages.error_name_en_invaild') ,
            'name_ar.*' => trans('messages.error_name_ar_invalid') ,
            'city_id.*' => trans('messages.error_city_id_invaild') ,
            // 'answer_id.*' => trans('messages.error_answer_id_fail'),
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' =>  $validator->errors(),]);
        }
        $barcodeName = str_replace(' ' , '-' , $request->name_en);
        if(Restaurant::where('name_barcode' , $barcodeName)->count() > 0){
            return response([
                'status' => false,
                'message' => trans('messages.error_barcode_name_exist'),
                'errors' => [
                    'name_en' => [[trans('messages.error_barcode_name_exist'),]]
                ] ,
            ]);
        }
        // create new restaurant
        $code = mt_rand(1000, 9999);
        // $code = 1111; // test
        $packageId = $request->package == 'gold' ? 2 : 1;
        $restaurant = Restaurant::create([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'country_id' => $request->country_id,
            'city_id' => $request->city_id ,
            'phone_number' => $request->phone_number,
            'phone_verification' => $code,
            'status' => 'inComplete',
            'package_id' => $packageId,
            'answer_id' => $request->answer_id,
            'name_barcode' => $barcodeName,
            'menu_arrange' => 'true',
            'product_arrange' => 'true',
            'admin_activation' => 'false',
        ]);
        if ($request->category_id != null) {
            foreach ($request->category_id as $category) {
                RestaurantCategory::create([
                    'category_id' => $category,
                    'restaurant_id' => $restaurant->id,
                ]);
            }
        }
        // send sms to restaurant owner
        $country = $restaurant->country->code;
        // send code to phone_number
        $msg = app()->getLocale() == 'ar' ? 'كود التحقق الخاص بك في أيزي منيو هو' . ' : ' . $code. '  ' . 'مؤسسة تقني' : 'EasyMenu verification code is : ' . $code. '  ' . 'مؤسسة تقني';
        $check = substr($restaurant->phone_number, 0, 2) === '05';
        if ($check == true) {
            $phone = $country . ltrim($restaurant->phone_number, '0');
        } else {
            $phone = $country . $restaurant->phone_number;
        }
        taqnyatSms($msg, $phone);

        dispatch(new SendMailNewRestaurantJob($restaurant));
        if ($restaurant) {
            return response([
                'status' => true ,
                'message' => trans('messages.send_code_success'),
                'data' => [
                    'restaurant_id' => $restaurant->id ,
                ] ,
            ]);
            return response()->json(['url' => url('/restaurant/phone_verification/' . $restaurant->id), 'msg' => trans('messages.success_register')]);
        }
    }

    public function phone_verification($id)
    {
        $restaurant = Restaurant::find($id);
        return view('restaurant.authAdmin.phone_verification', compact('restaurant'));
    }

    public function code_verification(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $this->validate($request, [
            'code' => 'required|numeric'
        ]);
        if ($request->code == $restaurant->phone_verification) {
            $restaurant->update([
                'phone_verification' => null,
            ]);
            flash(trans('messages.success_code'))->success();
            if($request->wantsJson()){

                return $this->submitStep2($request , $restaurant->id);
                // return response([
                //     'status' => true ,
                //     // 'message' =>
                //     'data' => [
                //         'view' => view('restaurant.authAdmin.secondStep', compact('restaurant', 'packages','categories', 'cities'))->render()
                //     ] ,
                // ]);
            }
            return redirect()->route('restaurant.step2Register', $restaurant->id);
        } else {

            if($request->wantsJson()){
                return response([
                    'status' => false ,
                    'message' => trans('messages.error_code') ,
                    'request' => $request->all() ,
                ]);
            }
            flash(trans('messages.error_code'))->error();
            return redirect()->back();
        }
    }

    public function storeStep2($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $cities = City::whereCountryId($restaurant->country_id)->get();
        $packages = Package::all();
        $categories = Category::all();
        return view('restaurant.authAdmin.secondStep', compact('restaurant', 'packages','categories', 'cities'));
    }

    protected function submitStep2(Request $request , $id)
    {
        $restaurant = Restaurant::findOrFail($id);

        $barcode = str_replace(' ' , '-' , $restaurant->name_en);
        // return response([
        //     'status' => false,
        //     'message' => null ,
        //     'errors' => $request->all(),
        // ]);
        $restaurant->update([
            'name_barcode' => $barcode ,
            'status' => 'tentative',
            'menu_arrange'  => 'true',
            'product_arrange' => 'true',
            'logo' => 'logo.png',
            'menu' =>'vertical',
            'description_ar' => 'نبذة عن المطعم . محتوي  يتم تغييره من خلال لوحة تحكم المطعم',
            'description_en' => 'A Brief About Restaurant Can Be Changed From Restaurant Control Panel',
            'information_ar' => ' يحتاج البالغون الى 2000 سعر حراري في المتوسط يومياً
يحتاج غير البالغون الى 1400 سعر حراري في المتوسط يومياً',
            'information_en' => 'Adults need an average of 2,000 calories per day',
        ]);

        // create the main Branch for this  restaurant
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
            //    'latitude'      => $restaurant->latitude,
            //    'longitude'     => $restaurant->longitude,
        ]);

        // create restaurant subscription
        $check_price = CountryPackage::whereCountry_id($restaurant->country_id)
            ->wherePackageId($restaurant->package_id)
            ->first();
        if ($check_price == null)
        {
            $package_actual_price = Package::find($restaurant->package_id)->price;
        }else{
            $package_actual_price = $check_price->price;
        }
        $tax = Setting::find(1)->tax;
        $tax_value = $package_actual_price * $tax / 100;
        $package_actual_price = $package_actual_price + $tax_value;
        $subscription = Subscription::create([
            'package_id' => $restaurant->package_id,
            'restaurant_id' => $restaurant->id,
            'branch_id' => $branch->id,
            'price' => $package_actual_price,
            'status' => 'tentative',    // active ,notActive , tentative , finished
            'end_at' => Carbon::now()->addDays(Setting::find(1)->tentative_period),
            'type' => 'restaurant',
            'tax_value' => $tax_value,
            'discount_value' => 0,
        ]);
        // store restaurant register at reports
        Report::create([
            'restaurant_id'  => $restaurant->id,
            'branch_id'      => $branch->id,
            'amount'         => $subscription->price,
            'status'         => 'registered',
            'type'           => 'restaurant',
            'tax_value'      => $tax_value,
        ]);

        if ($restaurant->package_id == 2)
        {
            // create order services
            // 1- create takeaway service
            gold_services($restaurant->id , 5 , $subscription->end_at);
            // 2- create delivery service
            gold_services($restaurant->id , 6 , $subscription->end_at);
            // 3 - create previous service
            gold_services($restaurant->id , 7 , $subscription->end_at);
            // 4- create Table service
            gold_services($restaurant->id , 8 , $subscription->end_at);
        }

        // store restaurant categories
        // if ($request->category_id != null) {
        //     foreach ($request->category_id as $category) {
        //         RestaurantCategory::create([
        //             'category_id' => $category,
        //             'restaurant_id' => $restaurant->id,
        //         ]);
        //     }
        // }
        //    // store the operation at restaurant history
        //    History::create([
        //        'restaurant_id'  => $restaurant->id,
        //        'package_id'     => $request->package_id,
        //        'branch_id'      => $branch->id,
        //        'operation_date' => Carbon::now(),
        //        'details'        => trans('messages.registerDate'),
        //    ]);

        // create a trying content for restaurant
        // 1- create slider
        RestaurantSlider::create([
            'restaurant_id' => $restaurant->id,
            'photo'         => 'slider2.png',
        ]);
        RestaurantSlider::create([
            'restaurant_id' => $restaurant->id,
            'photo'         => 'slider1.png',
        ]);

        //4- create restaurant sensitivity
        // create_restaurant_sensitivity($restaurant->id);
        defaultPostersAndSens($restaurant);
        // send email to admins
        dispatch(new SendMailCompleteRestaurantJob($restaurant));
        Auth::guard('restaurant')->login($restaurant);
        // flash(trans('messages.success_full_register'))->success();
        return response()->json(['status' => true , 'url' => url('/restaurant/home'), 'message' => trans('messages.success_full_register')]);
    }

    public function resend_code(Request $request , $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $code = mt_rand(1000, 9999);
        // $code = 1111; //test
        $country = $restaurant->country->code;
        // send code to phone_number
        $msg = app()->getLocale() == 'ar' ? 'كود التحقق الخاص بك في أيزي منيو هو' . ' : ' . $code. '  ' . 'مؤسسة تقني' : 'EasyMenu verification code is : ' . $code. '  ' . 'مؤسسة تقني';
        $check = substr($restaurant->phone_number, 0, 2) === '05';
        if ($check == true) {
            $phone = $country . ltrim($restaurant->phone_number, '0');
        } else {
            $phone = $country . $restaurant->phone_number;
        }
        $restaurant->update(['phone_verification' => $code]);
        taqnyatSms($msg, $phone);
        if($request->wantsJson()){
            return response([
                'status' => true,
                'message' => trans('messages.send_code_success')
            ]);
        }
        flash(trans('messages.code_send_successfully'))->success();
        return redirect()->back();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('restaurant.home');
    }

    public function get_regions($id)
    {
        $regions = City::where('parent_id', $id)->select('id', 'name')->get();
        $data['regions'] = $regions;
        return json_encode($data);
    }

    public function forget_password()
    {
        return view('restaurant.authAdmin.forget_password.forget');
    }
    public function forget_password_submit(Request $request)
    {
        $this->validate($request , [
            'phone_number' => ['required', 'regex:/^((05)|(01))[0-9]{8}/'],
        ]);
        $user = Restaurant::where('phone_number' , $request->phone_number)->first();
        if ($user)
        {
            $code = mt_rand(1000, 9999);
            $country = $user->country->code;
            // send code to phone_number
            $msg = app()->getLocale() == 'ar' ? 'كود التحقق الخاص بك في أيزي منيو هو' . ' : ' . $code. '  ' . 'مؤسسة تقني' : 'EasyMenu verification code is : ' . $code. '  ' . 'مؤسسة تقني';
            $check = substr($request->phone_number, 0, 2) === '05';
            if ($check == true) {
                $phone = $country . ltrim($request->phone_number, '0');
            } else {
                $phone = $country . $request->phone_number;
            }
            taqnyatSms($msg, $phone);
            $user->update([
                'phone_verification' => $code
            ]);
            return redirect()->route('forget_password_verification' , $user->id);
        }else{
            flash(trans('messages.phoneNotFound'))->error();
            return redirect()->route('restaurant.login');
        }

    }

    public function password_verification($res)
    {
        $user = Restaurant::find($res);
        return view('restaurant.authAdmin.forget_password.verification' , compact('user'));
    }
    public function password_verification_post(Request $request , $id)
    {
        $restaurant = Restaurant::find($id);
        $this->validate($request , [
            'code' => 'required',
        ]);
        $code = $request->code;
        if ($restaurant->phone_verification == $code)
        {
            flash(trans('messages.success_code'))->success();
            return redirect()->route('password_reset_restaurant' , $restaurant->id);
        }else{
            return redirect()->route('restaurant.login');
        }
    }
    public function reset_password($id)
    {
        $restaurant = Restaurant::find($id);
        return view('restaurant.authAdmin.forget_password.reset' , compact('restaurant'));
    }
    public function reset_password_post(Request $request , $id)
    {
        $restaurant = Restaurant::find($id);
        $this->validate($request , [
            'password' => 'required|string|min:8|confirmed',
        ]);
        $restaurant->update([
            'password' => Hash::make($request->password),
        ]);
        Auth::guard('restaurant')->login($restaurant);
        return redirect()->route('restaurant.home');
    }


    public function checkEmailAndPhone(Request $request){
        if(!empty($request->country_id) and $country = Country::find($request->country_id)){
            if($country->code == '2') $min = 'digits:11';
            elseif($country->code == '973') $min = 'digits:8';
            else $min = 'digits:10';
        }else $min = '';

        $vaildator =  validator( $request->all(), [
            'email' => 'nullable|email|unique:restaurants,email' ,
            'phone_number' => ['nullable','unique:restaurants,phone_number' , $min]
        ]);

        if($vaildator->fails()){
            return response([
                'status' => false,
                'data' => [
                    'email' => $vaildator->errors()->first('email') ,
                    'phone_number' => $vaildator->errors()->first('phone_number') ,
                ]
            ]);
        }
        return response(['status' => true ]);
    }
}

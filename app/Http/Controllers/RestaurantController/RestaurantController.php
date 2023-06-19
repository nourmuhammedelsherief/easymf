<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\City;
use App\Models\CountryPackage;
use App\Models\History;
use App\Models\MarketerOperation;
use App\Models\Order;
use App\Models\Package;
use App\Models\Report;
use App\Models\Restaurant;
use App\Models\RestaurantColors;
use App\Models\RestaurantPermission;
use App\Models\RestaurantUser;
use App\Models\SellerCode;
use App\Models\ServiceSubscription;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\Theme;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RestaurantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:restaurant');
    }
    public function my_profile()
    {
        if(!auth('restaurant')->check()){
            return redirect(url('restaurant/login'));
        }
        $user = Auth::guard('restaurant')->user();
        if ($user->type == 'employee'):
            if (check_restaurant_permission($user->id , 2) == false):
                abort(404);
            endif;
            $user = Restaurant::find($user->restaurant_id);
        endif;
        $cities = City::whereCountryId($user->country_id)->get();
        $themes = Theme::all();
        return view('restaurant.user.my_subscription', compact('user','themes', 'cities'));
    }
    public function my_restaurant_users()
    {
        if(!auth('restaurant')->check()){
            return redirect(url('restaurant/login'));
        }
        $restaurant = Auth::guard('restaurant')->user();
        // $users = RestaurantUser::whereRestaurantId($restaurant->id)
        //     ->paginate(500);
        $usersId = Order::where('restaurant_id' , $restaurant->id)->get()->pluck('user_id');
        $users = User::whereIn('id' , $usersId)->paginate(500);
        return view('restaurant.user.my_users', compact('users'));
    }
    public function updateMyInformation(Request $request  , $id = null){
        if(!auth('admin')->check() and !auth('restaurant')->check()){
            abort(401);
        }
        if ($id != null) {
            $user = Restaurant::findOrFail($id);
        } else {
            $user = Auth::guard('restaurant')->user();
            if ($user->type == 'employee'):
                if (check_restaurant_permission($user->id , 6) == false):
                    abort(404);
                endif;
                $user = Restaurant::find($user->restaurant_id);
            endif;
        }
        if($request->method() == 'POST'):

            $this->validate($request, [
                'is_call_phone' => 'required|in:true,false',
                'is_whatsapp' => 'required|in:true,false',
                'call_phone' => 'required_if:is_call_phone,true|nullable|numeric' ,
                'whatsapp_number' => 'required_if:is_whatsapp,true|nullable|numeric' ,
            ]);

            $user->update([
                'is_call_phone' => $request->is_call_phone,
                'is_whatsapp' => $request->is_whatsapp,
                'call_phone' => $request->call_phone,
                'whatsapp_number' => $request->whatsapp_number,

            ]);
            flash(trans('messages.updated'))->success();
        endif;


        return view('restaurant.user.contact_information' , compact('user'));
    }
    public function updateBarcode(Request $request  , $id = null){
        if(!auth('admin')->check() and !auth('restaurant')->check()){
            abort(401);
        }
        if ($id != null) {
            $user = Restaurant::findOrFail($id);
        } else {
            $user = Auth::guard('restaurant')->user();
            if ($user->type == 'employee'):
                if (check_restaurant_permission($user->id , 2) == false):
                    abort(404);
                endif;
                $user = Restaurant::find($user->restaurant_id);
            endif;
        }
        if ($request->ar == 'false' && $request->en == 'false') {
            flash(trans('messages.languageError'))->error();
            return redirect()->back();
        }
        $this->validate($request, [
            'name_ar' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
//            'name_barcode' => 'required|string|max:191|unique:restaurants,name_barcode,' . $user->id,
        ]);
        foreach([' ' , ',' , '.'] as $value):
            if(count(explode($value , $request->name_barcode)) > 1):
                throw ValidationException::withMessages([
                    'name_barcode' => trans('messages.error_barcode_name')
                ]);
            endif;
        endforeach;
//        $barcode = str_replace(' ' , '-' , $request->name_barcode);
        $user->update([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
//            'name_barcode' => $barcode,
        ]);
        // here the main branch should be edited with his restaurant
//        $branch = Branch::whereRestaurantId($user->id)
//            ->where('main', 'true')
//            ->first();
//        if ($branch) {
////            $barcode = str_replace(' ' , '-' , $request->name_barcode);
//            $branch->update([
//                'name_ar' => $request->name_ar,
//                'name_en' => $request->name_en,
////                'name_barcode' => $barcode,
//            ]);
//        }
        flash(trans('messages.updated'))->success();
        return redirect()->back();
    }
    public function my_profile_edit(Request $request, $id = null)
    {
        if(!auth('admin')->check() and !auth('restaurant')->check()){
            abort(401);
        }
        if ($id != null) {
            $user = Restaurant::findOrFail($id);
        } else {
            $user = Auth::guard('restaurant')->user();
            if ($user->type == 'employee'):
                if (check_restaurant_permission($user->id , 2) == false):
                    abort(404);
                endif;
                $user = Restaurant::find($user->restaurant_id);
            endif;
        }
        if ($request->ar == 'false' && $request->en == 'false') {
            flash(trans('messages.languageError'))->error();
            return redirect()->back();
        }

        $this->validate($request, [

            'email' => 'required|email|max:191|unique:restaurants,email,' . $user->id,
//            'phone_number' => ['required', 'unique:restaurants,phone_number,'.$user->id, 'regex:/^((05)|(01))[0-9]{8}/'],
//            'city_id' => 'required|exists:cities,id',
            // 'logo' => 'sometimes|mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000',
            'ar' => 'required|in:true,false',
            'en' => 'required|in:true,false',
            'enable_fixed_category' => 'required|in:true,false',

//            'tax' => 'required|in:true,false',
//            'total_tax_price' => 'required|in:true,false',
//            'tax_value' => "required_if:tax,==,ture",
        ]);
        if($request->ar == 'true' and $request->en == 'false'):
            $dlang = 'ar';
        elseif($request->en == 'true' and $request->ar == 'false'):
            $dlang = 'en';
        else:
            $dlang = $request->default_lang ;
        endif;
        $user->update([

            'email' => $request->email,
//            'city_id' => $request->city_id,
//            'phone_number' => $request->phone_number,
            'ar' => $request->ar,
            'en' => $request->en,
            'default_lang' => $dlang,
//            'total_tax_price' => $request->total_tax_price,
//            'tax' => $request->tax,
//            'tax_value' => $request->tax_value,
            'enable_fixed_category' => $request->enable_fixed_category,

            // 'logo' => $request->file('logo') == null ? $user->logo : UploadImageEdit($request->file('logo'), 'logo', '/uploads/restaurants/logo', $user->logo),
        ]);

        if($user->id == 1145):

            $request->validate([
                'theme_id' => 'required|exists:themes,id',
            ]);
            $user->update([
                'theme_id' => $request->theme_id,
            ]);
        endif;
        // here the main branch should be edited with his restaurant
        $branch = Branch::whereRestaurantId($user->id)
            ->where('main', 'true')
            ->first();
        if ($branch) {
            $branch->update([
                'email' => $request->email,
                // 'city_id' => $request->city_id,
                'phone_number' => $request->phone_number,
            ]);
        }
        flash(trans('messages.updated'))->success();
        return redirect()->back();
    }

    public function barcode()
    {
        $model = Auth::guard('restaurant')->user();
        if ($model->type == 'employee'):
            if (check_restaurant_permission($model->id , 2) == false):
                abort(404);
            endif;
            $model = Restaurant::find($model->restaurant_id);
        endif;
        return view('restaurant.user.barcode', compact('model'));
    }

    public function change_pass_update(Request $request, $id = null)
    {
        if(!auth('admin')->check() and !auth('restaurant')->check()){
            abort(401);
        }
        if ($id != null) {
            $user = Restaurant::findOrFail($id);
        } else {
            $user = Auth::guard('restaurant')->user();
            if ($user->type == 'employee'):
                if (check_restaurant_permission($user->id , 2) == false):
                    abort(404);
                endif;
                $user = Restaurant::find($user->restaurant_id);
            endif;
        }
        $this->validate($request, [
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user->password = Hash::make($request->password);
        $user->save();
        flash(trans('messages.updated'))->success();
        return redirect()->back();
    }

    public function renew_subscription($id , $admin = null)
    {
        $user = Restaurant::findOrFail($id);
        $admin = $admin == null ? 'restaurant' : 'admin';
        return view('restaurant.user.subscription', compact('user' , 'admin'));
    }

    public function store_subscription(Request $request, $id , $admin = null)
    {
        $user = Restaurant::findOrFail($id);
        $this->validate($request, [
            'payment_method' => 'required|in:bank,online',
            'payment_type' => 'sometimes|in:visa,mada,apple_pay',
            'seller_code' => 'nullable|exists:seller_codes,seller_name',

        ]);
        if ($request->payment == 'true') {
            $user->subscription->update([
                'payment' => 'true'
            ]);
        }
        // get the package price
        $check_price = CountryPackage::whereCountry_id($user->country_id)
            ->wherePackageId(1)
            ->first();
        $tax = Setting::find(1)->tax;
        $discount = 0;
        if ($check_price == null) {
            $package_actual_price = Package::find(1)->price;
        } else {
            $package_actual_price = $check_price->price;
        }
        // check if there are a seller code or not
        if ($request->seller_code != null) {
            $seller_code = SellerCode::where('seller_name', $request->seller_code)
                ->where('active', 'true')
                ->where('country_id' , $user->country_id)
                ->whereIn('type', ['restaurant' , 'both'])
                //    ->where('discount' , 'subscription')
                ->first();
            if ($seller_code)
            {
                if ($seller_code->start_at <= Carbon::now() && $seller_code->end_at >= Carbon::now())
                {
                    $package_price = $package_actual_price;
                    $discount_percentage = $seller_code->code_percentage;
                    $discount = ($package_price * $discount_percentage) / 100;
                    $price_after_percentage = $package_price - $discount;
                    $commission = $package_price * ($seller_code->percentage - $seller_code->code_percentage) / 100;
                    $seller_code->update([
                        'commission' => $commission + $seller_code->commission,
                    ]);
                    // store this operation to marketer history
                    MarketerOperation::create([
                        'marketer_id' => $seller_code->marketer_id,
                        'seller_code_id' => $seller_code->id,
                        'subscription_id' => $user->subscription->id,
                        'status' => 'not_done',
                        'amount' => $commission,
                    ]);
                    $price = $price_after_percentage;
                    $tax_value = $price * $tax / 100;
                    $price = $price + $tax_value;
                }else{
                    $price = $package_actual_price;
                    $tax_value = $price * $tax / 100;
                    $price = $price + $tax_value;
                }
            }else{
                $price = $package_actual_price;
                $tax_value = $price * $tax / 100;
                $price = $price + $tax_value;
            }
        } else {
            $price = $package_actual_price;
            $tax_value = $price * $tax / 100;
            $price = $price + $tax_value;
        }
        $user->subscription->update([
            'package_id' => 1,
            'bank_id' => $request->bank_id,
            'payment_type' => $request->payment_method,
            'price' => $price,
            'tax_value' => $tax_value,
            'discount_value' => $discount,
        ]);

        if ($request->payment_method == 'bank') {
            $banks = Bank::whereCountryId($user->country_id)
                ->where('restaurant_id' , null)
                ->get();
            $admin = $admin == null ? 'restaurant' : $admin;
            return view('restaurant.user.payments.bank_transfer', compact('user', 'tax','tax_value','discount','banks' , 'admin'));
        }
        else {
            // online payment By My fatoorah
            $branch = Branch::whereRestaurantId($id)
                ->where('main' , 'true')
                ->first();
            $amount = check_restaurant_amount($branch->id , $price);
            $amount = number_format((float)$amount, 2, '.', '');
            if ($request->payment_type == 'visa') {
                $charge = 2;
            } elseif ($request->payment_type == 'mada') {
                $charge = 6;
            } elseif ($request->payment_type == 'apple_pay') {
                $charge = 11;
            } else {
                $charge = 2;
            }
            $name = $user->name_en;
            $token = "1IzSLeOkLFQH1zljLzerCm2RpB8AjFZLf8MMhSy4d8rHb0h1uHqrBleBFlFv-M4SHnyeiWg2zWQraKYJGndFcFvaBIeCPDQNrNs1Zwo7O-4apFyAXXUVZOAKbYzncpn-1ay0BPxB1X5dNH0EuWQ9OTqzcnOI7c5Ola5Esxz0imTrbVuhmKZl7SWBrPCU_SOYt80BSDe5j2XY5skkK7e5TxDRbbibdZPM7S11aYmQ7xKmZvaSj916IhTNXuIZA73TYE4xxkXyL_8dhHobugeLHF58VJNBjMv2UvzEP0pSk0RqGs3-AeqYwD6S3BbVrOaGIbx4fwKlowd6SOoSqkMoD9tmFwgdbkMxzWKYGqxg7bcvB0r62jBqn4YXh0Ej8FO6mrTdWZo5bHviUDRPMitO2KQDvyXrlWnf74n9DxOfV7MbuutAr2K2L3hzCYkdfU0eqA3snq-3Xh1KFjRvd5QqofEt9ubK71Xd4TooKLyXD4hby5prqJTTEVi23bPsPmB1uZ0D1cawjjJB8eUTIwEiPTgdPOSIOTkVFm4OIrHEXT44NbJ9MyDInxmQK8-pGDr0rNeBeLo8J_uyHbfh_sVlpS7XT0d-ehaTDtENoyG0XMe7hgWDYWsNxIe1N3dXwwtyBXLgggAl6JvdDXBzY3wp13oFfTHdASeOtyO3d0zwvkF-9j0uF2WgHd-kqgEyQVV0_UifcbMafuYwTgbBod5iB1soNMoVcTfjlsmr8LV8CK7Nr8xq";
            $data = array(
                'PaymentMethodId' => $charge,
                'CustomerName' => $name,
                'DisplayCurrencyIso' => 'SAR',
                'MobileCountryCode' => $user->country->code,
                'CustomerMobile' => $user->phone_number,
                'CustomerEmail' => $user->email,
                'InvoiceValue' => $amount,
                'CallBackUrl' => route('checkRestaurantStatus' , $admin),
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
                    $user->subscription->update([
                        'invoice_id' => $result->Data->InvoiceId,
                    ]);
                    return redirect()->to($result->Data->PaymentURL);
                } else {
                    return redirect()->to(url('/error'));
                }
            } else {
                return redirect()->to(url('/error'));
            }
        }
    }

    public function check_status(Request $request , $admin = null)
    {

        $token = "1IzSLeOkLFQH1zljLzerCm2RpB8AjFZLf8MMhSy4d8rHb0h1uHqrBleBFlFv-M4SHnyeiWg2zWQraKYJGndFcFvaBIeCPDQNrNs1Zwo7O-4apFyAXXUVZOAKbYzncpn-1ay0BPxB1X5dNH0EuWQ9OTqzcnOI7c5Ola5Esxz0imTrbVuhmKZl7SWBrPCU_SOYt80BSDe5j2XY5skkK7e5TxDRbbibdZPM7S11aYmQ7xKmZvaSj916IhTNXuIZA73TYE4xxkXyL_8dhHobugeLHF58VJNBjMv2UvzEP0pSk0RqGs3-AeqYwD6S3BbVrOaGIbx4fwKlowd6SOoSqkMoD9tmFwgdbkMxzWKYGqxg7bcvB0r62jBqn4YXh0Ej8FO6mrTdWZo5bHviUDRPMitO2KQDvyXrlWnf74n9DxOfV7MbuutAr2K2L3hzCYkdfU0eqA3snq-3Xh1KFjRvd5QqofEt9ubK71Xd4TooKLyXD4hby5prqJTTEVi23bPsPmB1uZ0D1cawjjJB8eUTIwEiPTgdPOSIOTkVFm4OIrHEXT44NbJ9MyDInxmQK8-pGDr0rNeBeLo8J_uyHbfh_sVlpS7XT0d-ehaTDtENoyG0XMe7hgWDYWsNxIe1N3dXwwtyBXLgggAl6JvdDXBzY3wp13oFfTHdASeOtyO3d0zwvkF-9j0uF2WgHd-kqgEyQVV0_UifcbMafuYwTgbBod5iB1soNMoVcTfjlsmr8LV8CK7Nr8xq";
        $PaymentId = \Request::query('paymentId');
        $resData = MyFatoorahStatus($token, $PaymentId);
        $result = json_decode($resData);
        if ($result->IsSuccess === true && $result->Data->InvoiceStatus === "Paid") {
            $InvoiceId = $result->Data->InvoiceId;
            $subscription = Subscription::where('invoice_id', $InvoiceId)->first();
            $end_at = Carbon::now()->addMonths($subscription->package->duration);
            if ($subscription->status == 'finished' or $subscription->status == 'active')
            {
                // create report as renewed
                Report::create([
                    'restaurant_id'  => $subscription->restaurant_id,
                    'branch_id'      => $subscription->branch_id,
                    'seller_code_id' => $subscription->seller_code_id,
                    'amount'         => $subscription->price,
                    'status'         => 'renewed',
                    'type'           => $subscription->type == 'restaurant' ? 'restaurant' : 'branch',
                    'invoice_id'     => $InvoiceId,
                    'discount'       => $subscription->discount_value,
                    'tax_value'      => $subscription->tax_value,
                ]);
                History::create([
                    'restaurant_id' => $subscription->restaurant->id,
                    'package_id' => $subscription->package->id,
                    'branch_id' => $subscription->branch_id,
                    'operation_date' => Carbon::now(),
                    'details' =>   $subscription->type == 'restaurant' ?'تجديد اشتراك المطعم':'تجديد اشتراك الفرع',
                    'payment_type' => 'online',
                    'invoice_id' => $subscription->invoice_id,
                    'paid_amount' => $subscription->price,
                    'discount_value' => $subscription->discount_value,
                    'tax_value'      => $subscription->tax_value,
                ]);
            }else{
                // create report as subscribed
                Report::create([
                    'restaurant_id'  => $subscription->restaurant_id,
                    'branch_id'      => $subscription->branch_id,
                    'seller_code_id' => $subscription->seller_code_id,
                    'amount'         => $subscription->price,
                    'status'         => 'subscribed',
                    'type'           => $subscription->type == 'restaurant' ? 'restaurant' : 'branch',
                    'invoice_id'     => $InvoiceId,
                    'discount'       => $subscription->discount_value,
                    'tax_value'      => $subscription->tax_value,
                ]);
                History::create([
                    'restaurant_id' => $subscription->restaurant->id,
                    'package_id' => $subscription->package->id,
                    'branch_id' => $subscription->branch_id,
                    'operation_date' => Carbon::now(),
                    'details' =>   $subscription->type == 'restaurant' ?' اشتراك مطعم جديد':' اشتراك فرع جديد',
                    'payment_type' => 'online',
                    'invoice_id' => $subscription->invoice_id,
                    'paid_amount' => $subscription->price,
                    'discount_value' => $subscription->discount_value,
                    'tax_value'      => $subscription->tax_value,
                ]);
            }
            $subscription->update([
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

            }
            flash(trans('messages.onlinePaymentDone'))->success();
            if ($subscription->branch != null) {
                $subscription->branch->update([
                    'status' => 'active',
                ]);
            }
            if ($subscription->type == 'restaurant') {
                if ($admin == 'admin')
                {
                    return redirect()->route('showRestaurant' , $subscription->restaurant->id);
                }else{
                    return redirect()->route('RestaurantProfile');
                }
            } else {
                return redirect()->route('branches.index');
            }
        }
    }
    public function check_service_status(Request $request)
    {
        $token = "1IzSLeOkLFQH1zljLzerCm2RpB8AjFZLf8MMhSy4d8rHb0h1uHqrBleBFlFv-M4SHnyeiWg2zWQraKYJGndFcFvaBIeCPDQNrNs1Zwo7O-4apFyAXXUVZOAKbYzncpn-1ay0BPxB1X5dNH0EuWQ9OTqzcnOI7c5Ola5Esxz0imTrbVuhmKZl7SWBrPCU_SOYt80BSDe5j2XY5skkK7e5TxDRbbibdZPM7S11aYmQ7xKmZvaSj916IhTNXuIZA73TYE4xxkXyL_8dhHobugeLHF58VJNBjMv2UvzEP0pSk0RqGs3-AeqYwD6S3BbVrOaGIbx4fwKlowd6SOoSqkMoD9tmFwgdbkMxzWKYGqxg7bcvB0r62jBqn4YXh0Ej8FO6mrTdWZo5bHviUDRPMitO2KQDvyXrlWnf74n9DxOfV7MbuutAr2K2L3hzCYkdfU0eqA3snq-3Xh1KFjRvd5QqofEt9ubK71Xd4TooKLyXD4hby5prqJTTEVi23bPsPmB1uZ0D1cawjjJB8eUTIwEiPTgdPOSIOTkVFm4OIrHEXT44NbJ9MyDInxmQK8-pGDr0rNeBeLo8J_uyHbfh_sVlpS7XT0d-ehaTDtENoyG0XMe7hgWDYWsNxIe1N3dXwwtyBXLgggAl6JvdDXBzY3wp13oFfTHdASeOtyO3d0zwvkF-9j0uF2WgHd-kqgEyQVV0_UifcbMafuYwTgbBod5iB1soNMoVcTfjlsmr8LV8CK7Nr8xq";
        $PaymentId = \Request::query('paymentId');
        $resData = MyFatoorahStatus($token, $PaymentId);
        $result = json_decode($resData);
        if ($result->IsSuccess === true && $result->Data->InvoiceStatus === "Paid") {
            $InvoiceId = $result->Data->InvoiceId;
            $service = ServiceSubscription::where('invoice_id', $InvoiceId)->first();
            $end_at = Carbon::now()->addYear();
            $service->update([
                'invoice_id' => $InvoiceId,
                'paid_at' => Carbon::now(),
                'end_at'  => Carbon::now()->addYear(),
                'status'  => 'active',
            ]);
            // add operation to histories
            History::create([
                'restaurant_id' => $service->restaurant->id,
                'branch_id' => $service->branch_id,
                'operation_date' => Carbon::now(),
                'details' =>  app()->getLocale() == 'ar' ? 'أشتراك المطعم في خدمه جديده' : 'register new service',
                'payment_type' => 'online',
                'invoice_id' => $service->invoice_id,
                'paid_amount' => $service->price,
                'type'      => 'service',
                'discount_value' => $service->discount,
                'tax_value'     => $service->tax_value,
            ]);
            Report::create([
                'restaurant_id' => $service->restaurant_id,
                'amount' => $service->price,
                'status' => $service->created_at < Carbon::now()->addYears(-1) ? 'renewed' : 'subscribed',
                'type' => 'service',
                'service_subscription_id' => $service->id,
                'service_id' => $service->service_id,
                'tax_value' => $service->tax_value,
                'discount' => $service->discount,
            ]);
            flash(trans('messages.onlinePaymentDone'))->success();
            return redirect()->to(url('restaurant/services_store'));
        }
    }


    public function renewSubscriptionBank(Request $request, $id , $admin = null)
    {
        $user = Restaurant::findOrFail($id);
        $this->validate($request, [
            'bank_id' => 'required|exists:banks,id',
            'transfer_photo' => 'required|mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000',
        ]);
        // update user subscription
        $user->subscription->update([
            'bank_id' => $request->bank_id,
            'transfer_photo' => UploadImage($request->file('transfer_photo'), 'photo', '/uploads/transfers'),
        ]);
        flash(trans('messages.bankTransferDone'))->success();
        if ($admin == 'restaurant')
        {
            return redirect()->route('RestaurantProfile');
        }elseif ($admin == 'admin'){
            return redirect()->route('showRestaurant' , $user->id);
        }
    }

    public function information()
    {
        if(!auth('restaurant')->check()){
            return redirect(url('restaurant/login'));
        }
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 6) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        return view('restaurant.user.information' , compact('restaurant'));
    }

    public function store_information(Request $request)
    {
        if(!auth('restaurant')->check()){
            return redirect(url('restaurant/login'));
        }
        $this->validate($request, [
            'information_ar' => 'sometimes|string|max:1020',
            'information_en' => 'sometimes|string|max:1020'
        ]);
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 6) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $restaurant->update([
            'information_ar' => $request->information_ar,
            'information_en' => $request->information_en,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->back();
    }

    public function RestaurantChangeExternal(Request $request, $id = null)
    {
        if(!auth('admin')->check() and !auth('restaurant')->check()){
            abort(401);
        }
        $this->validate($request, [
//            'state' => 'required|in:open,closed,busy,un_available',
            'cart' => 'nullable|in:true,false',
            'menu' => 'nullable|in:vertical,horizontal',
            'show_branches_list' => 'nullable|in:true,false',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'latitude' => 'sometimes'
        ]);
//        dd($request->all());
        if ($id != null) {
            $restaurant = Restaurant::findOrFail($id);
        } else {
            $restaurant = Auth::guard('restaurant')->user();
            if ($restaurant->type == 'employee'):
                if (check_restaurant_permission($restaurant->id , 2) == false):
                    abort(404);
                endif;
                $restaurant = Restaurant::find($restaurant->restaurant_id);
            endif;
        }

        $restaurant->update([
//            'state' => $request->state,
            'menu' => $request->menu,
            'cart' => $request->cart == null ? $restaurant->cart : $request->cart,
            'show_branches_list' => $request->show_branches_list,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'description_ar' => $request->description_ar == null ? $restaurant->description_ar : $request->description_ar,
            'description_en' => $request->description_en == null ? $restaurant->description_en : $request->description_en,
        ]);
        $main_branch = Branch::whereRestaurantId($restaurant->id)
            ->where('main', 'true')
            ->first();
        if ($main_branch) {
            $main_branch->update([
                'latitude' => $restaurant->latitude,
                'longitude' => $restaurant->longitude,
            ]);
        }
        flash(trans('messages.updated'))->success();
        return redirect()->back();
    }

    public function RestaurantChangeColors(Request $request, $id)
    {
        if(!auth('admin')->check() and !auth('restaurant')->check()){
            abort(401);
        }
        $this->validate($request, [
            'restaurant_id' => 'sometimes',
            'main_heads' => 'sometimes',
            'icons' => 'sometimes',
            'options_description' => 'sometimes',
            'background' => 'sometimes',
            'product_background' => 'sometimes',
            'category_background' => 'sometimes',
        ]);
        $restaurant = Restaurant::findOrFail($id);
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 2) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        RestaurantColors::updateOrCreate(
            ['restaurant_id' => $restaurant->id],
            [
                'main_heads' => $request->main_heads,
                'icons' => $request->icons,
                'options_description' => $request->options_description,
                'background' => $request->background,
                'product_background' => $request->product_background,
                'category_background' => $request->category_background,
            ]
        );
        flash(trans('messages.updated'))->success();
        return redirect()->back();
    }

    public function Reset_to_main($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        if ($restaurant) {
            if ($restaurant->color != null) {
                $restaurant->color->delete();
            }
            flash(trans('messages.updated'))->success();
            return redirect()->back();
        }

    }

    public function uploadImage(Request $request){

        $request->validate([
            'photo' => 'required|mimes:png,jepg,jpg,svg' ,
            'action' => 'required|in:edit' ,
            'item_id' => 'required_if:action,edit|integer|exists:restaurants,id' ,
        ]);
        if($request->action == 'edit')
            $item = Restaurant::findOrFail($request->item_id);

        if ($request->photo != null)
        {
            $photo = UploadImageEdit($request->file('photo'),'photo' , '/uploads/restaurants/logo' , (isset($item->photo) ? $item->photo : null));
            if(isset($item->id))
                $item->update([
                    'logo' => $photo ,
                ]);
            return response([
                'photo' =>  $photo,
                'status' => true ,
            ]);
        }
        return response('error' , 500);
    }
    public function myfatoora_token()
    {
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $restaurant = auth('restaurant')->user();
        return view('restaurant.user.myfatoora_token' , compact('restaurant'));
    }
    public function update_myfatoora_token(Request $request)
    {
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $data = $this->validate($request , [
            'payment_company' => 'nullable|in:myFatoourah,tap,express',
            'online_token' => 'nullable|string',
            'merchant_key' => 'nullable|string',
            'express_password' => 'nullable|string',
            'enable_reservation_online_pay' => 'required|in:true,false',
            'online_payment_fees' => 'nullable|numeric|min:0.01|max:100' ,
        ]);
        $restaurant = auth('restaurant')->user();
        $restaurant->update($data);
        flash(trans('messages.updated'))->success();
        return redirect()->back();
    }

}

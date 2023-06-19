<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Restaurant;
use App\Models\RestaurantOrderSetting;
use App\Models\ServiceSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class RestaurantSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth('restaurant')->check()){
            return redirect(route('restaurant.login'));
        }
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $checkOrderService = ServiceSubscription::whereRestaurantId($restaurant->id)
            ->whereIn('service_id' , [9 , 10])
            ->first();
        if ($checkOrderService == null)
        {
            abort(404);
        }
        $settings = RestaurantOrderSetting::where('restaurant_order_settings.restaurant_id' , $restaurant->id)
            ->leftJoin('branches' , 'branches.id' , 'restaurant_order_settings.branch_id' )
            ->leftJoin('subscriptions' , 'branches.id' , 'subscriptions.branch_id')
            ->select(DB::raw('restaurant_order_settings.* , subscriptions.package_id as sub_package_id'))
            ->orderBy('restaurant_order_settings.id', 'desc')
            ->get();
        // return $settings;
        return view('restaurant.settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!$restaurant = auth('restaurant')->user()){
            return redirect(route('restaurant.login'));
        }
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $checkOrderService = ServiceSubscription::whereRestaurantId($restaurant->id)
            ->whereIn('service_id' , [9 , 10])
            ->first();
        if ($checkOrderService == null)
        {
            abort(404);
        }
        $branches = Branch::with('subscription')
            ->whereHas('subscription' , function ($q){
                $q->where('end_at' , '!=' , null);
            })
            ->whereRestaurantId($restaurant->id)
            ->whereIn('status',['active' , 'tentative'])
            ->where('foodics_status' , 'false')
            ->get();
        return view('restaurant.settings.create', compact('branches' , 'restaurant'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$restaurant = auth('restaurant')->user()):
            return redirect(route('restaurant.login'));
        endif;
        $this->validate($request, [
            'branch_id'   => 'required',
            'order_type' => 'required|in:delivery,takeaway,previous,whatsapp,easymenu',
            'distance' => 'required_if:order_type,delivery',
            'delivery_value' => 'required_if:order_type,delivery',
            'receipt_payment' => 'required|in:true,false',
            'online_payment'  => 'required|in:true,false',
            'payment_company'   => 'required_if:online_payment,true',
            'online_token'   => 'required_if:payment_company,tap',
            'merchant_key'   => 'required_if:payment_company,express',
            'express_password'   => 'required_if:payment_company,express',
            'latitude' => 'required',
            'delivery' => 'required_if:order_type,previous|in:true,false',
            'takeaway' => 'required_if:order_type,previous|in:true,false',
            'takeaway_distance' => 'required_if:takeaway,true',
            'previous' => 'required_if:order_type,previous|in:true,false',
            'previous_order_type' => 'required_if:previous,true|in:delivery,takeaway,both',
            'previous_distance' => 'required_if:previous,true',
            'table'    => 'required_if:order_type,previous|in:true,false',
            'pre_delivery_value' => 'required_if:delivery,true',
            'bank_transfer' => 'sometimes|in:false,true',
            'delivery_payment' => 'required_if:delivery,true|in:receipt,online,both',
            'takeaway_payment' => 'required_if:takeaway,true|in:receipt,online,both',
            'previous_payment' => 'required_if:previous,true|in:receipt,online,both',
            'table_payment' => 'required_if:table,true|in:receipt,online,both',

        ]);
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        // check if restaurant subscribe on package correct package
        if ($request->order_type == 'previous')
        {
//            if(!checkRestaurantPackageId($branch , 'branch' , 3)):
//                flash(trans('dashboard.errors.should_select_fimaly_package'))->error();
//                return redirect()->back();
//            endif;
            if ($request->delivery == null && $request->takeaway == null)
            {
                flash(trans('messages.shouldSelectOrderType'))->error();
                return redirect()->back();
            }
        }elseif($request->order_type == 'whatsapp'){
            $request->validate([
                'whatsapp_number' =>'required|string|min:10|max:16'
            ]);
        }
        $service_subscription = ServiceSubscription::with('service')
            ->whereHas('service' , function ($q) use ($request){
                $q->where('type' , $request->order_type);
            })
            ->whereRestaurantId($restaurant->id)
            ->whereBranchId($request->branch_id)
            ->first();
        $checkBranchOrderType = RestaurantOrderSetting::whereBranchId($request->branch_id)
            ->where('order_type' , $request->order_type)
            ->first();
        if ($checkBranchOrderType == null)
        {
            // create new order setting
            RestaurantOrderSetting::create([
                'restaurant_id' => $restaurant->id,
                'branch_id' => $service_subscription == null ? $request->branch_id : $service_subscription->branch_id,
                'order_type' => $request->order_type,
                'distance' => $request->distance == null ? null : $request->distance,
                'takeaway_distance' => $request->takeaway_distance == null ? null : $request->takeaway_distance,
                'previous_distance' => $request->previous_distance == null ? null : $request->previous_distance,
                'delivery_value' => $request->delivery_value != null ?$request->delivery_value : ($request->pre_delivery_value == null ? null : $request->pre_delivery_value),
                'receipt_payment' => $request->receipt_payment,
                'online_payment' => $request->online_payment,
                'payment_company' => $request->payment_company,
                'online_token' => $request->online_token,
                'merchant_key' => $request->merchant_key == null ? null : $request->merchant_key,
                'express_password' => $request->express_password == null ? null : $request->express_password,
                'delivery' => $request->delivery,
                'takeaway' => $request->takeaway,
                'table' => $request->table == null ? 'false' : $request->table,
                'previous' => $request->previous == null ? 'false' : $request->previous,
                'previous_order_type' => $request->previous_order_type == null ? 'both' : $request->previous_order_type,
                'bank_transfer' => $request->bank_transfer == null ? 'false' : $request->bank_transfer,
                'whatsapp_number' => $request->order_type == 'whatsapp' ? $request->whatsapp_number : null,
                'delivery_payment' => $request->delivery_payment == null ? null : $request->delivery_payment,
                'takeaway_payment' => $request->takeaway_payment == null ? null : $request->takeaway_payment,
                'previous_payment' => $request->previous_payment == null ? null : $request->previous_payment,
                'table_payment' => $request->table_payment == null ? null : $request->table_payment,
            ]);
            $branch = Branch::find($service_subscription == null ? $request->branch_id : $service_subscription->branch_id);
            if ($branch->main == 'true')
            {
                $branch->update([
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
                $branch->restaurant->update([
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
            }else{
                $branch->update([
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
            }
            flash(trans('messages.created'))->success();
        }else{
            flash(trans('messages.branchHasSetting'))->error();
        }
        return redirect()->route('restaurant_setting.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $checkOrderService = ServiceSubscription::whereRestaurantId($restaurant->id)
            ->whereIn('service_id' , [9 , 10])
            ->first();
        if ($checkOrderService == null)
        {
            abort(404);
        }
        $setting = RestaurantOrderSetting::findOrFail($id);
        return view('restaurant.settings.edit', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!$restaurant = auth('restaurant')->user()):
            return redirect(route('restaurant.login'));
        endif;
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $setting = RestaurantOrderSetting::findOrFail($id);
        $this->validate($request, [
            'distance' => 'required_if:delivery,true',
            'takeaway_distance' => 'required_if:takeaway,true',
            'delivery_value' => 'required_if:order_type,delivery',
            'receipt_payment' => 'required|in:true,false',
            'online_payment'  => 'required|in:true,false',
            'payment_company'   => 'required_if:online_payment,true',
            'online_token'   => 'required_if:payment_company,tap',
            'merchant_key'   => 'required_if:payment_company,express',
            'express_password'   => 'required_if:payment_company,express',
            'latitude' => 'nullable',
            'delivery' => 'required_if:order_type,previous|in:true,false',
            'takeaway' => 'required_if:order_type,previous|in:true,false',
            'previous' => 'required_if:order_type,previous|in:true,false',
            'previous_order_type' => 'required_if:previous,true|in:delivery,takeaway,both',
            'previous_distance' => 'required_if:previous,true',
            'table' => 'required_if:order_type,previous|in:true,false',
            'pre_delivery_value' => 'required_if:delivery,true',
            'whatsapp_number' => $setting->order_type == 'whatsapp' ? 'required|string' : 'nullable',
            'delivery_payment' => 'required_if:delivery,true|in:receipt,online,both',
            'takeaway_payment' => 'required_if:takeaway,true|in:receipt,online,both',
            'previous_payment' => 'required_if:previous,true|in:receipt,online,both',
            'table_payment' => 'required_if:table,true|in:receipt,online,both',
        ]);

        if ($setting->order_type == 'previous')
        {
            if(checkRestaurantPackageId($restaurant ,'restaurant', 3)):
                flash(trans('dashboard.errors.should_select_fimaly_package'))->error();
                return redirect()->back();
            endif;
            if ($request->delivery == null && $request->takeaway == null)
            {
                flash(trans('messages.shouldSelectOrderType'))->error();
                return redirect()->back();
            }
        }elseif($setting->order_type == 'whatsapp'){
            $request->validate([
                'whatsapp_number' =>'required|string|min:10|max:16'
            ]);
        }
        $setting->update([
            'distance' => $request->distance == null ? $setting->distance : $request->distance,
            'takeaway_distance' => $request->takeaway_distance == null ? $setting->takeaway_distance : $request->takeaway_distance,
            'previous_distance' => $request->previous_distance == null ? $setting->previous_distance : $request->previous_distance,
            'receipt_payment' => $request->receipt_payment,
            'online_payment' => $request->online_payment,
            'delivery' => $request->delivery,
            'takeaway' => $request->takeaway,
            'previous' => $request->previous,
            'table' => $request->table,
            'online_token' => $request->online_token,
            'payment_company' => $request->payment_company,
            'merchant_key' => $request->merchant_key == null ? $setting->merchant_key : $request->merchant_key,
            'express_password' => $request->express_password == null ? $setting->express_password : $request->express_password,
            'bank_transfer' => $request->bank_transfer == null ? 'false' : $request->bank_transfer,
            'delivery_payment' => $request->delivery_payment,
            'takeaway_payment' => $request->takeaway_payment,
            'previous_payment' => $request->previous_payment,
            'table_payment' => $request->table_payment,
            'previous_order_type' => $request->previous_order_type == null ? $setting->previous_order_type : $request->previous_order_type,
        ]);
        $branch = $setting->branch;
        if ($branch->main == 'true')
        {
            $branch->update([
                'latitude' => $request->latitude == null ? $branch->latitude : $request->latitude,
                'longitude' => $request->longitude == null ? $branch->longitude : $request->longitude,
            ]);
            $branch->restaurant->update([
                'latitude' => $request->latitude == null ? $branch->latitude : $request->latitude,
                'longitude' => $request->longitude == null ? $branch->longitude : $request->longitude,
            ]);
        }else{
            $branch->update([
                'latitude' => $request->latitude == null ? $branch->latitude : $request->latitude,
                'longitude' => $request->longitude == null ? $branch->longitude : $request->longitude,
            ]);
        }
        if ($setting->order_type == 'delivery')
        {
            $setting->update([
                'delivery_value' => $request->delivery_value,
            ]);
        }elseif ($setting->order_type == 'previous')
        {
            $setting->update([
                'delivery_value' => $request->pre_delivery_value,
            ]);
        }elseif($setting->order_type == 'whatsapp'){
            $setting->update([
                'whatsapp_number' => $request->whatsapp_number
            ]);
        }
        flash(trans('messages.updated'))->success();
        return redirect()->route('restaurant_setting.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $setting = RestaurantOrderSetting::findOrFail($id);
        $setting->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('restaurant_setting.index');
    }

    public function foodics_settings($id)
    {
        $branch = Branch::findOrFail($id);
        $checkOrderService = ServiceSubscription::whereRestaurantId($branch->restaurant->id)
            ->where('service_id' , 4)
            ->first();
        if ($checkOrderService == null)
        {
            abort(404);
        }
        return view('restaurant.settings.foodics_setting', compact('branch'));
    }
    public function foodics_settings_update(Request $request , $id)
    {
        $branch = Branch::findOrFail($id);
        $this->validate($request , [
            'delivery' => 'required|in:true,false',
            'previous' => 'required|in:true,false',
            'takeaway' => 'required|in:true,false',
            'table'   => 'required|in:true,false',
            'receipt_payment' => 'required|in:true,false',
            'online_payment'  => 'required|in:true,false',
            'payment_company'   => 'required_if:online_payment,true',
            'online_token'   => 'required_if:payment_company,tap',
            'merchant_key'   => 'required_if:payment_company,express',
            'express_password'   => 'required_if:payment_company,express',
            'delivery_distance'  => 'required_if:delivery,ture',
            'takeaway_distance'  => 'required_if:takeaway,ture',
            'latitude'         => 'sometimes',
            'longitude'        => 'sometimes',
        ]);
        $branch->update([
            'delivery' => $request->delivery,
            'previous' => $request->previous,
            'takeaway' => $request->takeaway,
            'table'    => $request->table,
            'receipt_payment' => $request->receipt_payment,
            'online_payment'  => $request->online_payment,
            'table_payment'  => $request->table_payment,
            'online_token' => $request->online_token == null ? $branch->online_token : $request->online_token,
            'payment_company' => $request->payment_company,
            'merchant_key' => $request->merchant_key == null ? $branch->merchant_key : $request->merchant_key,
            'express_password' => $request->express_password == null ? $branch->express_password : $request->express_password,
            'delivery_distance'  => $request->delivery_distance,
            'takeaway_distance'  => $request->takeaway_distance,
            'latitude'           => $request->latitude,
            'longitude'          => $request->longitude,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->back();
    }
    public function get_branch_service($id)
    {
        $branch = Branch::findOrFail($id);
        $whats = 'false';
        $easy  = 'false';
        if(checkOrderService($branch->restaurant->id , 9 , $branch->id) == true and checkOrderSetting($branch->restaurant->id , 'whatsapp' , $branch->id) == false):
            $whats = 'true';
        endif;
        if (checkOrderService($branch->restaurant->id , 10 , $branch->id) == true and checkOrderSetting($branch->restaurant->id , 'easymenu' , $branch->id) == false):
            $easy = 'true';
        endif;
        return response()->json(array('success' => true, 'whats' => $whats , 'easy' => $easy ));
    }

}

<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Restaurant;
use App\Models\RestaurantOrderSellerCode;
use App\Models\RestaurantOrderSetting;
use App\Models\ServiceSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestaurantOrderSellerCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        $codes = RestaurantOrderSellerCode::whereRestaurantId($restaurant->id)
            ->orderBy('id' , 'desc')
            ->paginate(500);
        return view('restaurant.order_codes.index' , compact('codes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $checkOrderService = ServiceSubscription::whereRestaurantId($restaurant->id)
            ->whereIn('service_id' , [9,10])
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
            ->whereStatus('active')
            ->where('foodics_status' , 'false')
            ->get();
        return view('restaurant.order_codes.create' , compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $this->validate($request , [
            'branch_id'  => 'required|exists:branches,id',
            'seller_code'  => 'required|numeric',
            'discount_percentage' => 'required|numeric',
            'start_at'     => 'required|date',
            'end_at'       => 'required|date',
        ]);
        // create new order seller code
        RestaurantOrderSellerCode::create([
            'restaurant_id'  => $restaurant->id,
            'branch_id'      => $request->branch_id,
            'seller_code'    => $request->seller_code,
            'discount_percentage' => $request->discount_percentage,
            'start_at'  => $request->start_at,
            'end_at'    => $request->end_at,
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('order_seller_codes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
            ->whereIn('service_id' , [9,10])
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
            ->whereStatus('active')
            ->where('foodics_status' , 'false')
            ->get();
        $code = RestaurantOrderSellerCode::findOrFail($id);
        return view('restaurant.order_codes.edit' , compact('code' , 'branches'));
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
        $code = RestaurantOrderSellerCode::findOrFail($id);
        $this->validate($request , [
            'branch_id'  => 'required|exists:branches,id',
            'seller_code'  => 'required|numeric',
            'discount_percentage' => 'required|numeric',
            'start_at'     => 'required|date',
            'end_at'       => 'required|date',
        ]);
        // update order seller code
        $code->update([
            'branch_id'      => $request->branch_id,
            'seller_code'    => $request->seller_code,
            'discount_percentage' => $request->discount_percentage,
            'start_at'  => $request->start_at,
            'end_at'    => $request->end_at,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('order_seller_codes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $code = RestaurantOrderSellerCode::findOrFail($id);
        $code->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('order_seller_codes.index');
    }
}

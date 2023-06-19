<?php

namespace App\Http\Controllers\RestaurantController\Reservation;

use App\Http\Controllers\Controller;
use App\Models\Reservation\ReservationOrder;
use App\Models\Restaurant;
use App\Models\ServiceSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:restaurant');
    }
    public function getSettings(Request $request){
        $restaurant = auth('restaurant')->user();
        if($request->method() == 'POST'):
            // return $request->all();
            $data = $request->validate([
                'reservation_service' => 'required|in:true,false' ,
                'reservation_to_restaurant' => 'required|in:true,false' ,
                'reservation_is_call_phone' => 'required|in:true,false' ,
                'reservation_is_whatsapp' => 'required|in:true,false' ,
                'reservation_tax' => 'required|in:true,false' ,
                'reservation_tax_value' => 'required_if:reservation_tax,true|nullable|numeric|min:0.01|max:100' ,
                'reservation_call_phone' => 'required_if:reservation_is_call_phone,true|nullable|numeric' ,
                'reservation_whatsapp_number' => 'required_if:reservation_is_whatsapp,true|nullable|numeric' ,
            ]);
            $data['reservation_call_number'] = $request->reservation_call_phone;
            $restaurant->update($data);
        endif;
        return view('restaurant.reservations.settings' , compact('restaurant'));
    }
    public function reservationDescription(Request $request){
        $restaurant  = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $checkReservation = ServiceSubscription::whereRestaurantId($restaurant->id)
            ->where('service_id' , 1)
            ->whereIn('status' , ['active' , 'tentative'])
            ->first();
        if ($checkReservation == null)
        {
            abort(404);
        }
        if($request->method() == 'POST'):
            $data = $request->validate([
                'reservation_description_en' => 'required|min:1|max:100000' ,
                'reservation_description_ar' => 'required|min:1|max:100000' ,
            ]);
            $restaurant->update($data);
            flash(trans('messages.updated'))->success();
            return redirect()->route('restaurant.reservation.description.edit');
        else:
            return view('restaurant.reservations.reservation_description' , compact('restaurant'));
        endif;
    }
    private function deleteIncompletedOrder(){
        $now = Carbon::now()->subDays(7);
        $items = ReservationOrder::where('is_order' , 0)->where('created_at','>' , $now->format('Y-m-d H:i:s'))->get();
        $now = Carbon::now();
        foreach($items as $item):
            $date = Carbon::createFromTimestamp(strtotime($item->created_at))->addDays(30);
            if($date->lessThan($now)) $item->delete();
        endforeach;
    }

    public function index()
    {
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        // $this->deleteIncompletedOrder();
        $checkReservation = ServiceSubscription::whereRestaurantId($restaurant->id)
            ->where('service_id' , 1)
            ->whereIn('status' , ['active' , 'tentative'])
            ->first();
        if ($checkReservation == null)
        {
            abort(404);
        }
        $orders = ReservationOrder::whereRestaurantId($restaurant->id)->where('is_order' , 1)->with(['table' => function($query){
            $query->withTrashed()->with('branch' , 'place');
        }])->with([ 'date' , 'period'])
            ->orderBy('status' , 'asc')->where('is_confirm' ,'=' ,0)
            ->whereIn('status' , ['not_paid' , 'paid'])
            ->whereDate('date' , '>=' , Carbon::now())
            ->paginate(500);
        // return $orders;
        $status = 'active';
        return view('restaurant.reservations.orders.index' , compact('orders' , 'status'));
    }

    public function completed()
    {
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        // $this->deleteIncompletedOrder();
        $checkReservation = ServiceSubscription::whereRestaurantId($restaurant->id)
            ->where('service_id' , 1)
            ->whereIn('status' , ['active' , 'tentative'])
            ->first();
        if ($checkReservation == null)
        {
            abort(404);
        }
        $orders = ReservationOrder::whereRestaurantId($restaurant->id)->where('is_order' , 1)->with(['table' => function($query){
            $query->withTrashed()->with('branch' , 'place');
        }])->with([ 'date' , 'period'])
            ->orderBy('status' , 'asc')->where('is_confirm' , 1)
            ->whereDate('date' , '>=' , Carbon::now())
            ->paginate(500);
        // return $orders;
        $status = 'active';
        return view('restaurant.reservations.orders.index' , compact('orders' , 'status'));
    }
    // expire reservation
    public function finished()
    {
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $checkReservation = ServiceSubscription::whereRestaurantId($restaurant->id)
            ->where('service_id' , 1)
            ->whereIn('status' , ['active' , 'tentative'])
            ->first();
        if ($checkReservation == null)
        {
            abort(404);
        }
        $orders = ReservationOrder::whereRestaurantId($restaurant->id)->where('is_order' , 1)->with(['table' => function($query){
            $query->withTrashed()->with('branch' , 'place');
        }])->with([ 'date' , 'period'])
            ->orderBy('status' , 'asc')
            ->whereDate('date' , '<' , Carbon::now())
            ->paginate(20);
        $status = 'finished';
        // return $orders;
        return view('restaurant.reservations.orders.index' , compact('orders' , 'status'));
    }
    public function canceled()
    {
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $checkReservation = ServiceSubscription::whereRestaurantId($restaurant->id)
            ->where('service_id' , 1)
            ->whereIn('status' , ['active' , 'tentative'])
            ->first();
        if ($checkReservation == null)
        {
            abort(404);
        }
        $orders = ReservationOrder::whereRestaurantId($restaurant->id)->where('is_order' , 1)->with(['table' => function($query){
            $query->withTrashed()->with('branch' , 'place');
        }])->with([ 'date' , 'period'])
            ->orderBy('status' , 'asc')
            ->where('status' , 'canceled')
            ->paginate(20);
        $status = 'cenceled';
        // return $orders;
        return view('restaurant.reservations.orders.index' , compact('orders' , 'status'));
    }


    public function service_setting(Request $request){
        if(!$restaurant = auth('restaurant')->user()){
            abort(422);
        }
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        if($request->method() == 'POST' and $request->has('reservation_service')):
            $request->validate([
                'reservation_service' => 'required|in:true,false'
            ]);
            $restaurant->update([
                'reservation_service' => $request->reservation_service
            ]);
        endif;

        return view('restaurant.reservations.orders.settings' , compact('restaurant'));
    }


    public function confirmBankOrder(Request $request , ReservationOrder $order){
        // return $request->all();
        if(in_array($order->status , ['not_paid'  , 'paid']) and $request->cancel == 1){
            $order->update([
                'status' => 'canceled' ,
                'reason' => $request->reason ,
                'is_confirm' => -1
            ]);
            flash(trans('dashboard.messages.save_successfully'))->success();
            $page = (!empty($request->page) and is_numeric($request->page)) ? $request->page : 1;
            return redirect()->back();
            // return redirect(route('restaurant.reservation.index') . '?page=' . $page);
        }
        elseif($order->status == 'not_paid' and $order->payment_type == 'bank'){
            $order->update([
                'status' => 'paid' ,
            ]);

            flash(trans('dashboard.messages.save_successfully'))->success();
            $page = (!empty($request->page) and is_numeric($request->page)) ? $request->page : 1;
            return redirect(route('restaurant.reservation.index') . '?page=' . $page);
        }
        flash(trans('dashboard.errors.order_paid_fail'))->error();
        return back();
    }

    public function show(ReservationOrder $order){
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        if($order->restaurant_id != $restaurant->id) abort(404);
        return response([
            'order' => view('restaurant.reservations.orders.user_info' , compact('order'))->render(),
        ]);
    }

    public function servicesIndex(){
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $action = 'reservation' ;
        return view('restaurant.reservations.orders.payment_services' , compact('restaurant'));
    }

    public function cashSettings(Request $request){
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        if($request->method() == 'POST'):
            $data = $request->validate([
                'enable_reservation_cash' => 'required|in:true,false' ,
            ]);
            $restaurant->update($data);
            flash(trans('messages.updated'))->success();
            return redirect(route('resetaurant.reservation.services'));
        endif;

        return view('restaurant.reservations.orders.cash_payment' ,compact( 'restaurant'));
    }
}

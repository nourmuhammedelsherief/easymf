<?php

namespace App\Http\Controllers\EmployeeController\Order;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyPoint;
use App\Models\LoyaltyPointHistory;
use App\Models\Order;
use App\Models\ServiceSubscription;
use App\Models\TableOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private function getPackageId($casher){
        $branch = $casher->branch()->with('subscription')->first();
        if(isset($branch->subscription->id)) return $branch->subscription->package_id;
        return false;
    }
    public function delivery_orders($status)
    {
        $casher = auth('employee')->user();
        if(!$casher and checkOrderService($casher->restaurant_id , 10)):
            return redirect(url('casher/home'));
        endif;

        $restaurant = $casher->restaurant;
        $branch     = $casher->branch;
        $orders = Order::whereRestaurantId($casher->restaurant->id)
            ->where('branch_id' , $branch->id)
            ->where('status' , $status)
            ->where('type' , 'delivery')
            ->orderBy('id' , 'desc')
            ->paginate(100);
        $type = 'delivery';
        return view('employee.orders.index' , compact('casher' ,'type','status', 'restaurant' , 'branch' , 'orders'));
    }
    public function takeaway_orders($status)
    {
        $casher = auth('employee')->user();
        if(!$casher and checkOrderService($casher->restaurant_id , 10)):
            return redirect(url('casher/home'));
        endif;
        $restaurant = $casher->restaurant;
        $branch     = $casher->branch;
        $orders = Order::whereRestaurantId($casher->restaurant->id)
            ->where('branch_id' , $branch->id)
            ->where('status' , $status)
            ->where('type' , 'takeaway')
            ->orderBy('id' , 'desc')
            ->paginate(100);
        $type = 'takeaway';
        return view('employee.orders.index' , compact('casher' ,'type' ,'status', 'restaurant' , 'branch' , 'orders'));
    }
    public function previous_orders($status)
    {
        $casher = auth('employee')->user();
        if(!$casher and checkOrderService($casher->restaurant_id , 10)):
            return redirect(url('casher/home'));
        endif;
        $restaurant = $casher->restaurant;
        $branch     = $casher->branch;
        $orders = Order::whereRestaurantId($casher->restaurant->id)
            ->where('branch_id' , $branch->id)
            ->where('status' , $status)
            ->where('type' , 'previous')
            ->orderBy('id' , 'desc')
            ->paginate(100);
        return view('employee.orders.previous_orders' , compact('casher' ,'status', 'restaurant' , 'branch' , 'orders'));
    }

    public function change_order_status(Request  $request ,$id)
    {
        $order = Order::find($id);
        $this->validate($request , [
            'status' => 'required|in:active,completed,canceled',
        ]);
        $order->update([
            'status'  => $request->status,
        ]);
        $loyaltySubscription =  ServiceSubscription::whereRestaurantId($order->restaurant->id)->whereHas('service' , function($query){
            $query->where('id' , 11);
           })
            ->whereIn('status' , ['active' , 'tentative'])
            ->first();
        if(  $order->restaurant->enable_loyalty_point == 'true' and isset($loyaltySubscription->id) and $request->status == 'completed' and !LoyaltyPointHistory::where('order_id' , $order->id)->first()):
            $points = 0 ; 
            $items = $order->order_items;
            foreach($items as $t):
                if($t->loyalty_points > 0) $points+= ($t->loyalty_points * $t->product_count);
            endforeach;
            if($points > 0):
                LoyaltyPointHistory::create([
                    'restaurant_id' => $order->restaurant_id , 
                    'order_id' => $order->id , 
                    'user_id' => $order->user_id , 
                    'points' => $points , 
                ]);
                if($balance = LoyaltyPoint::where('type' , 'point')->where('user_id' , $order->user_id)->where('restaurant_id' , $order->restaurant_id)->first()):
                    $balance->update([
                        'amount' => ($balance->amount + $points) , 
                    ]);
                else:
                    LoyaltyPoint::create([
                        'type' => 'point' , 
                        'restaurant_id' => $order->restaurant_id , 
                        'user_id' => $order->user_id , 
                        'amount' => $points , 
                    ]);
                endif;
            endif;
        endif;
        return redirect()->back();
    }
    public function change_table_order_status(Request  $request ,$id)
    {
        $order = TableOrder::find($id);
        $this->validate($request , [
            'status' => 'required|in:new,active,completed,canceled,in_reservation',
        ]);
        $order->update([
            'status'  => $request->status,
        ]);
      
        return redirect()->back();
    }
    public function table_orders($status)
    {
        $casher = auth('employee')->user();
        if(!$casher and checkOrderService($casher->restaurant_id , 10)):
            return redirect(url('casher/home'));
        endif;

        $restaurant = $casher->restaurant;
        $branch     = $casher->branch;
        $orders = TableOrder::whereRestaurantId($casher->restaurant->id)
            ->where('branch_id' , $branch->id)
            ->where('status' , $status)
            ->orderBy('id' , 'desc')
            ->paginate(100);
        return view('employee.orders.table_orders' , compact('casher' ,'status', 'restaurant' , 'branch' , 'orders'));
    }
    public function change_order_payment(Request $request , $id)
    {
        $order = TableOrder::find($id);
        $this->validate($request , [
            'payment_type' => 'required|in:cash,online',
        ]);
        $order->update([
            'payment_type'  => $request->payment_type,
        ]);
        return redirect()->back();
    }

}

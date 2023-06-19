<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyPoint;
use App\Models\LoyaltyPointHistory;
use App\Models\Order;
use App\Models\ServiceSubscription;
use App\Models\TableOrder;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    
    /**
     *  webhook function
     */
    public function webhook(Request $request)
    {
        app('log')->debug('RECEIVED CALL BACK FROM Foodics');

        // get all data passed by foodics
        $d = json_decode(json_encode($request->all()),true);
        $dd = $request->all();
        file_put_contents(storage_path('app/foodics_change_status.txt'), 'new_order_status' . date('Y-m-d h:i A') . '  '.  json_encode($request->all()) , FILE_APPEND);
        if(is_array($dd)  and isset($dd['order']) and isset($dd['order']['id'])){
            
            $code = $dd['order']['id'];
            // file_put_contents('log.txt', $code, FILE_APPEND);
            
            // get order
            $order = Order::where('foodics_order_id',$code)->first();
           
            if($order and false) // check if there any gold order exist with the same id
            {
                $restaurant = $order->restaurant;
                $branch = $order->branch;
                $orderStatus = null ; 
                if(in_array($dd['order']['status'] , [2 ,6])) $orderStatus = 'active';
                elseif(in_array($dd['order']['status'] , [4])) $orderStatus = 'completed';
                elseif(in_array($dd['order']['status'] , [3 , 5, 7])) $orderStatus = 'canceled';
                // return $orderStatus;
                $order->update([
                    'foodics_status' => $dd['order']['status'] , 
                    'status' => empty($orderStatus) ? $order->status : $orderStatus , 
                ]);
                // check loyalty_points system 
                $loyaltySubscription =  ServiceSubscription::whereRestaurantId($restaurant->id)->whereHas('service' , function($query){
                    $query->where('id' , 11);
                })
                    ->whereIn('status' , ['active' , 'tentative'])
                    ->where('branch_id' , $branch->id)
                    ->first();
                if(  $restaurant->enable_loyalty_point == 'true' and isset($loyaltySubscription->id) ){
                    $points = 0;
                    foreach($order->order_items as $t):

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
                    endif; // if there any points 
                } // end loyalty points system

            } // end gold order
            else{ // check if foodics id is exists in table_orders table
                
                $order = TableOrder::where('foodics_order_id',$code)->first();
               
                if($order) // check if there any table order exist with the same id
                {
                    $restaurant = $order->restaurant;
                    $branch = $order->branch;
                    $orderStatus = null ; 
                    if(in_array($dd['order']['status'] , [2 ,6])) $orderStatus = 'active';
                    elseif(in_array($dd['order']['status'] , [4])) $orderStatus = 'completed';
                    elseif(in_array($dd['order']['status'] , [3 , 5, 7])) $orderStatus = 'canceled';
                    // return $orderStatus;
                    $order->update([
                        'foodics_status' => $dd['order']['status'] , 
                        'status' => empty($orderStatus) ? $order->status : $orderStatus , 
                    ]);
                }

            }
        }
        return 0;
    }
    
}

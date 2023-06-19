<?php

namespace App\Http\Controllers\websiteController\Silver;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\FoodicsDiscount;
use App\Models\LoyaltyPoint;
use App\Models\Option;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemOption;
use App\Models\Product;
use App\Models\ProductModifier;
use App\Models\ProductOption;
use App\Models\ProductSize;
use App\Models\Restaurant;
use App\Models\RestaurantBranch;
use App\Models\RestaurantFoodicsBranch;
use App\Models\RestaurantOrderPeriod;
use App\Models\RestaurantOrderSellerCode;
use App\Models\RestaurantOrderSetting;
use App\Models\RestaurantUser;
use App\Models\ServiceSubscription;
use App\Models\Setting;
use App\Models\SilverOrder;
use App\Models\SilverOrderOption;
use App\Models\Table;
use App\Models\TableOrder;
use App\Models\TableOrderItem;
use App\Models\TableOrderItemOption;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function add_to_cart(Request $request)
    {
        $request->headers->set('Accept', 'application/json');
        $user = Auth::guard('web')->user();
        $meal = Product::findOrFail($request->mealId);
        $restaurant = $meal->restaurant;
        $this->checkTheme($restaurant);

        $branch = $meal->branch;
        if (!auth('web')->check()):
            session()->put('last_order', $request->all());
            return response([
                'status' => 2,
                'login_link' => route('showUserLogin', $restaurant->id),
            ]);
        else:
            session()->forget('last_order');
        endif;
        SilverOrder::with('product')
            ->whereHas('product', function ($q) use ($branch) {
                $q->where('branch_id', $branch->id);
            })
            ->where('status', 'sent')
            ->where('user_id', $user->id)
            ->delete();
        $totalCount = 'total' . $meal->id;
        if (!auth('web')->check()) {
            return redirect(route('showUserLogin' . $restaurant->id));
        }
        
        $checkOrderService = ServiceSubscription::whereRestaurantId($restaurant->id)
            ->whereIn('service_id', [ 9, 10])
            ->whereIn('status', ['active' , 'tentative'])
            ->first();
        if ($branch->foodics_status == 'true' or $checkOrderService == null) {

            if (empty($request->size_price_id) or count($request->size_price_id) == 0) {
                if (empty($request->options)) {
                    // check if the options is required
                    $check_required_options = ProductOption::whereProductId($meal->id)
                        ->where('min', '>=', 1)
                        ->first();
                    if ($check_required_options) {
                        
                        return response([
                            'status' => false , 
                            'message' => 'الإضافات مطلوبة'
                        ]);
                    }
                } else {
                    $option_count = 0;
                    foreach ($request->options as $options_id) {
                        $option = Option::find($options_id);
                        $modifier = $option->modifier;
                        $option_count++;
                        if ($modifier->choose == 'one' and $option_count > 1) {
                            
                            return response([
                                'status' => false , 
                                'message' =>'لا يمكنك طلب أكثر من أضافة'
                            ]);
                        }
                    }
                }
                // create order
                $order = SilverOrder::create([
                    'user_id' => $user->id,
                    'product_id' => $meal->id,
                    'notes' => $request->notes == null ? null : $request->notes,
                    'product_count' => $request->$totalCount,
                ]);
                // create options
                $options_price = 0;
                $totalName = 'total' . $meal->id;
                $mod_count = 0;
                if ($request->options_ids != null && $request->options != null) {
                    foreach ($request->options as $options_id) {
                        $option = Option::find($options_id);
                        $check_mod = ProductModifier::whereProduct_id($meal->id)
                            ->where('modifier_id', '!=', $option->modifier_id)
                            ->first();
                        if ($check_mod) {
                            $mod_count++;
                        }
                        $opName = 'qty' . $options_id . $meal->id;
                        // create order options
                        SilverOrderOption::create([
                            'silver_order_id' => $order->id,
                            'option_id' => $options_id,
                            'quantity' => $request->$opName,
                            'price' => Option::find($options_id)->price,
                        ]);
                        $options_price += (Option::find($options_id)->price * $request->$opName) * $request->$totalName;
                    }
                }
                if (ProductModifier::whereProductId($meal->id)->count() > 1 && ProductModifier::whereProductId($meal->id)->count() > $mod_count) {
                    $order->delete();
                                                
                    return response([
                        'status' => false , 
                        'message' =>'الإضافات مطلوبة'
                    ]);
                }
                $order_price = ($meal->price * $request->$totalName) + $options_price;
                $tax = 0;
                if ($meal->branch->tax == 'true') {
                    $tax = ($meal->branch->tax_value * $order_price) / 100;
                }
                $totalPrice = $order_price + $tax;
                $order->update([
                    'order_price' => $order_price,
                    'tax' => $tax,
                    'total_price' => $totalPrice,
                    'product_price' => $meal->price , 
                ]);

            } else {
                if (!empty($request->size_price_id)):
                    if (empty($request->options)) {
                        // check if the options is required
                        $check_required_options = ProductOption::whereProductId($meal->id)
                            ->where('min', '>=', 1)
                            ->first();
                        if ($check_required_options) {
                       
                            return response([
                                'status' => false , 
                                'message' =>'الإضافات مطلوبة'
                            ]);
                        }
                    }
                    foreach ($request->size_price_id as $sizeId):
                        // create order
                        $size = ProductSize::whereProductId($meal->id)
                            ->where('id', $sizeId)
                            ->firstOrFail();
                        $order = SilverOrder::create([
                            'user_id' => $user->id,
                            'product_id' => $meal->id,
                            'notes' => $request->notes == null ? null : $request->notes,
                            'product_size_id' => $size->id,
                            'product_count' => $request->$totalCount,
                        ]);
                        // create options
                        $options_price = 0;
                        $totalName = 'total' . $meal->id;
                        $mod_count = 0;
                        if ($request->options_ids != null && $request->options != null) {
                            foreach ($request->options as $options_id) {
                                $option = Option::find($options_id);
                                $check_mod = ProductModifier::whereProduct_id($meal->id)
                                    ->where('modifier_id', '!=', $option->modifier_id)
                                    ->first();
                                if ($check_mod) {
                                    $mod_count++;
                                }
                                $opName = 'qty' . $options_id . $meal->id;
                                // create order options
                                SilverOrderOption::create([
                                    'silver_order_id' => $order->id,
                                    'option_id' => $options_id,
                                    'quantity' => $request->$opName,
                                    'price' => Option::find($options_id)->price,
                                ]);
                                $options_price += (Option::find($options_id)->price * $request->$opName) * $request->$totalName;
                            }
                        }
                        if (ProductModifier::whereProductId($meal->id)->count() > 1 && ProductModifier::whereProductId($meal->id)->count() > $mod_count) {
                            $order->delete();
                            
                            return response([
                                'status' => false , 
                                'message' =>'الإضافات مطلوبة'
                            ]);
                        }
                        $order_price = ($size->price * $request->$totalName) + $options_price;
                        $tax = 0;
                        if ($meal->branch->tax == 'true') {
                            $tax = ($meal->branch->tax_value * $order_price) / 100;
                        }
                        $totalPrice = $order_price + $tax;
                        $order->update([
                            'order_price' => $order_price,
                            'tax' => $tax,
                            'total_price' => $totalPrice,
                            'product_price' => $size->price , 
                        ]);
                    endforeach;
                endif;

            }
        } 
        elseif ($checkOrderService and $branch->foodics_status == 'false') {
            if (empty($request->options)) {
                // check if the options is required
                $check_required_options = ProductOption::whereProductId($meal->id)
                    ->where('min', '>=', 1)
                    ->first();
                if ($check_required_options) {
                    
                    return response([
                        'status' => false , 
                        'message' =>'الإضافات مطلوبة'
                    ]);
                }
            } else {
                $option_count = 0;
                $oldOption = [];
                foreach ($request->options as $options_id) {
                    $option = Option::find($options_id);
                    $modifier = $option->modifier;
                    $option_count++;
                    if ($modifier->choose == 'one' and $option_count > 1 and (isset($oldOption[$option->modifier_id]) and count($oldOption[$option->modifier_id]) > 0 )) {
                        return response([
                            'status' => false , 
                            'message' =>'لا يمكنك طلب أكثر من أضافة'
                        ]);
                    }
                    $oldOption[$option->modifier_id][] = $option->id;
                }
            }
            $checkOrder = Order::whereUserId($user->id)
                ->where('restaurant_id', $restaurant->id)
                ->where('status', 'in_reservation')
                //    ->whereRaw('(type != "previous" or type is null)')
                ->where('branch_id', $meal->branch->id)
                ->first();
            if ($checkOrder) {
                $order = $checkOrder;
            } else {
                $order = Order::create([
                    'restaurant_id' => $restaurant->id,
                    'branch_id' => $meal->branch->id,
                    'user_id' => $user->id,
                    'status' => 'in_reservation',
                    'notes' => $request->notes == null ? null : $request->notes,
                ]);
            }
            if ((empty($request->size_id) or count($request->size_id) == 0)) {
                // create order
                $loyaltySubscription =  ServiceSubscription::whereRestaurantId($restaurant->id)->whereHas('service' , function($query){
                    $query->where('id' , 11);
                   })
                    ->whereIn('status' , ['active' , 'tentative'])
                    ->first();
                if(  $restaurant->enable_loyalty_point == 'true' and isset($loyaltySubscription->id)){
                    $points = $meal->loyalty_points;
                }else{
                    $points = null;
                }
                $item = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $meal->id,
                    'product_count' => $request->$totalCount,
                    'price' => $meal->price,
                    'loyalty_points' => $points , 
                ]);
                // create options
                $options_price = 0;
                $totalName = 'total' . $meal->id;
                if ($request->options_ids != null && $request->options != null) {
                    foreach ($request->options as $options_id) {
                        $opName = 'qty' . $options_id . $meal->id;
                        // create order options
                        OrderItemOption::create([
                            'order_item_id' => $item->id,
                            'option_id' => $options_id,
                            'option_count' => $request->$opName,
                        ]);
                        $options_price += (Option::find($options_id)->price * $request->$opName) * $request->$totalName;
                    }
                }
                $order_price = ($meal->price * $request->$totalName) + $options_price;
                $tax = 0;
                if ($meal->branch->tax == 'true') {
                    $tax = ($meal->branch->tax_value * $order_price) / 100;
                }
                $totalPrice = $order_price + $tax;
                if ($checkOrder == null) {
                    $order->update([
                        'order_price' => $order_price,
                        'tax' => $tax,
                        'total_price' => $totalPrice,
                        'product_price' => $meal->price , 
                    ]);
                } else {
                    $order->update([
                        'order_price' => ($order_price + $checkOrder->order_price),
                        'tax' => ($tax + $checkOrder->tax),
                        'total_price' => ($totalPrice + $checkOrder->total_price),
                        'product_price' => $meal->price , 
                    ]);
                }
            } else {

                foreach ($request->size_price_id as $sizeId):
                    // create order
                    $size = ProductSize::whereProductId($meal->id)
                        ->where('id', $sizeId)
                        ->firstOrFail();
                    // create order
                    $loyaltySubscription =  ServiceSubscription::whereRestaurantId($restaurant->id)->whereHas('service' , function($query){
                        $query->where('id' , 11);
                       })
                        ->whereIn('status' , ['active' , 'tentative'])
                        ->first();
                    if(  $restaurant->enable_loyalty_point == 'true' and isset($loyaltySubscription->id)){
                        $points = $meal->loyalty_points;
                    }else{
                        $points = null;
                    }
                    $item = OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $meal->id,
                        'product_count' => $request->$totalCount,
                        'size_id' => $size->id,
                        'price' => $size->price,
                        'loyalty_points' => $points
                    ]);
                    // create options
                    $options_price = 0;
                    $totalName = 'total' . $meal->id;
                    if ($request->options_ids != null && $request->options != null) {
                        foreach ($request->options as $options_id) {
                            $opName = 'qty' . $options_id . $meal->id;
                            // create order options
                            OrderItemOption::create([
                                'order_item_id' => $item->id,
                                'option_id' => $options_id,
                                'option_count' => $request->$opName,
                            ]);
                            $options_price += (Option::find($options_id)->price * $request->$opName) * $request->$totalName;
                        }
                    }
                    $order_price = ($size->price * $request->$totalName) + $options_price;
                    $tax = 0;
                    if ($meal->branch->tax == 'true') {
                        $tax = ($meal->branch->tax_value * $order_price) / 100;
                    }
                    $totalPrice = $order_price + $tax;
                    if ($checkOrder == null) {
                        $order->update([
                            'order_price' => $order_price,
                            'tax' => $tax,
                            'total_price' => $totalPrice,
                            'product_price' => $size->price , 
                        ]);
                    } else {
                        $order->update([
                            'order_price' => ($order_price + $checkOrder->order_price),
                            'tax' => ($tax + $checkOrder->tax),
                            'total_price' => ($totalPrice + $checkOrder->total_price),
                            'product_price' => $meal->price , 
                        ]);
                    }
                endforeach; // end size

            }
        }
        $user->update([
            'city_id' => $meal->branch->city->id,
        ]);
        
        // check restaurant user
        $checkRestaurantUser = RestaurantUser::whereRestaurantId($restaurant->id)
            ->where('user_id', $user->id)
            ->first();
        if ($checkRestaurantUser == null) {
            // create restaurant user
            RestaurantUser::create([
                'restaurant_id' => $restaurant->id,
                'user_id' => $user->id,
            ]);
        }
        $branch = $meal->branch;
        if ($branch->foodics_status == 'true' || $checkOrderService == null):
            $cartCount = SilverOrder::with('product')
                ->whereHas('product', function ($q) use ($branch) {
                    $q->where('branch_id', $branch->id);
                })
                ->whereUserId(\Illuminate\Support\Facades\Auth::guard('web')->user()->id)
                ->count();
        elseif ($checkOrderService and $branch->foodics_status == 'false'):

            $cartCount = OrderItem::with('product', 'order')
                ->whereHas('product', function ($q) use ($branch) {
                    $q->where('branch_id', $branch->id);
                })
                ->whereHas('order', function ($q) {
                    $q->where('user_id', Auth::guard('web')->user()->id);
                    $q->where('status', 'in_reservation');
                })->count();
        endif;


        if ($request->wantsJson()):
            if (!isset($cartCount) and empty($cartCount)):
                $cartCount = 0;
            endif;
            if(session()->has('redirect_to')):
                $url = session('redirect_to');
                session()->forget('redirect_to');
            else:
                $url = route('sliverHome' , $restaurant->name_barcode);
            endif;
            $message = session('come_from_login') ? trans('messages.login_success') : trans('messages.saved_cart_success');

            return response([
                'status' => true,
                'message' => $message , 
                'redirect_to' => $url,  
                'msg' => trans('messages.login_success') ,
                'data' => [
                    'cart_count' => $cartCount,
                ],
                // 'request'=> $request->all()
            ], 200);
        endif;

        session()->flash('addToCart', true);
        if (isset($branch->id)):
            if ($branch->main == 'true'):
                return redirect(route('sliverHome', [$branch->restaurant->name_barcode, $meal->menu_category_id]));
            else:
                return redirect()->route('sliverHomeBranch', [$restaurant->name_barcode, $branch->name_barcode, $meal->menu_category_id]);
            endif;
        endif;
        return redirect()->to(url()->previous());
    }

    public function get_cart($id)
    {
        /**
         *  for foodics orders
         */
        $branch = Branch::findOrFail($id);
        // return $branch;
        if (!auth('web')->check()) {
            return redirect(route('showUserLogin', (isset($branch->id) ? $branch->restaurant_id : 276)));
        }

        // return session()->all();
        $user = Auth::guard('web')->user();
        $orders = SilverOrder::with('product', 'product_size')
            ->whereHas('product', function ($q) use ($branch) {
                $q->where('branch_id', $branch->id);
            })
            ->where('status', 'in_cart')
            ->whereUserId($user->id)
            ->get();
            // return $orders;
        $restaurant = $branch->restaurant;
        $this->checkTheme($restaurant);
        return view('website.' . session('theme_path') . 'silver.accessories.cart', compact('user', 'orders', 'branch', 'restaurant'));
    }

    public function get_gold_cart($id)
    {
        /**
         *  for restaurant orders
         */
        $branch = Branch::findOrFail($id);
        if (!auth('web')->check()) {
            return redirect(route('showUserLogin', (isset($branch->id) ? $branch->restaurant_id : 276)));
        }
        // $packageId = restaurantPackageId($branch);
        $packageId = $branch->subscription->package_id;
        $user = Auth::guard('web')->user();
        $order = Order::whereUserId($user->id)
            ->where('status', 'in_reservation')
            ->where('branch_id', $branch->id)
            ->first();
        if ($order) {
            $items = OrderItem::with('order')
                ->whereHas('order', function ($q) {
                    $q->where('status', 'in_reservation');
                })
                ->where('order_id', $order->id)
                ->get();
        } else {
            $items = OrderItem::limit(0);
        }

        $restaurant = $branch->restaurant;
        $this->checkTheme($restaurant);
        return view('website.' . session('theme_path') . 'gold.accessories.cart', compact('user', 'order', 'items', 'branch', 'restaurant', 'packageId'));
    }


    public function get_family_cart($id)
    {
        $branch = Branch::findOrFail($id);
        if (!auth('web')->check()) {
            return redirect(route('showUserLogin', (isset($branch->id) ? $branch->restaurant_id : 276)));
        }
        $packageId = restaurantPackageId($branch);
        $user = Auth::guard('web')->user();
        $order = Order::whereUserId($user->id)
            ->where('status', 'in_reservation')
            ->where('branch_id', $branch->id)
            ->first();
        if ($order) {
            $items = OrderItem::with('order')
                ->whereHas('order', function ($q) {
                    $q->where('status', 'in_reservation');
                })
                ->where('order_id', $order->id)
                ->get();
        } else {
            $items = OrderItem::limit(0);
        }

        $restaurant = $branch->restaurant;
        $this->checkTheme($restaurant);
        return view('website.' . session('theme_path') . 'family.accessories.cart', compact('user', 'order', 'items', 'branch', 'restaurant', 'packageId'));
    }

    public function removeSilverCartOrder($id)
    {
        $order = SilverOrder::findOrFail($id);
        $order->delete();
        return redirect()->back();
    }

    public function FoodicsOrder(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);
        $this->validate($request, [
            'branch_id' => 'required',
            'order_type' => 'required',
            'payment_method' => 'required',
            'online_type' => 'sometimes',
            'discount_name' => 'sometimes',
            'previous_order_type' => 'required_if:order_type,previous',
            'period_id' => 'required_if:order_type,previous',
            'day_id' => 'required_if:order_type,previous',
        ]);
        if (($request->order_type == 'delivery' && $request->latitude == null && $request->longitude == null) || ($request->previous_order_type == 'delivery' && $request->latitude == null && $request->longitude == null)) {
            flash('يجب تحديد موقعك ')->error();
            return redirect()->back();
        }
        $user = $request->user();
        $branch_id = RestaurantFoodicsBranch::findOrFail($request->branch_id)->foodics_id;
        $branch_foodics = RestaurantFoodicsBranch::findOrFail($request->branch_id);
        if ($branch->takeaway_distance != null) {
            $distance = distanceBetweenTowPlaces($request->latitude, $request->longitude, $branch_foodics->latitude, $branch_foodics->longitude);
            if ($branch->takeaway_distance <= $distance) {
                flash('عفوا أنت خارج نطاق الخدمات')->error();
                return redirect()->back();
            }
        } elseif ($branch->delivery_distance != null) {
            $distance = distanceBetweenTowPlaces($request->latitude, $request->longitude, $branch_foodics->latitude, $branch_foodics->longitude);
            if ($branch->delivery_distance <= $distance) {
                flash('عفوا أنت خارج نطاق الخدمات')->error();
                return redirect()->back();
            }
        }
        $orders = SilverOrder::with('product')
            ->whereHas('product', function ($q) use ($branch) {
                $q->where('branch_id', $branch->id);
            })
            ->where('status', 'in_cart')
            ->where('user_id', $user->id)
            ->get();
        $count = $branch->restaurant->foodics_orders + 1;
        $branch->restaurant->update([
            'foodics_orders' => $count,
        ]);
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        if ($request->payment_method == 'EasyMenu-online') {
            foreach ($orders as $order) {
                if ($request->discount_name != null) {
                    $discount = FoodicsDiscount::whereBranchId($branch->id)
                        ->whereNameEn($request->discount_name)
                        ->first();
                    if ($discount) {
                        checkProductDiscount($order->id, $discount->id);
                    }
                }
                $order->update([
                    'payment_type' => $request->payment_method == 'EasyMenu-online' ? 'online' : 'cash',
                    'order_type' => $request->order_type,
                    'foodics_branch_id' => RestaurantFoodicsBranch::findOrFail($request->branch_id)->id,
                    'previous_order_type' => $request->previous_order_type,
                    'period_id' => $request->period_id,
                    'day_id' => $request->day_id,
                ]);
            }
            $amount = SilverOrder::with('product')
                ->whereHas('product', function ($q) use ($branch) {
                    $q->where('branch_id', $branch->id);
                })
                ->where('status', 'in_cart')
                ->sum('order_price');
            $discount_value = SilverOrder::with('product')
                ->whereHas('product', function ($q) use ($branch) {
                    $q->where('branch_id', $branch->id);
                })
                ->where('status', 'in_cart')
                ->sum('discount_value');
            if ($request->order_type == 'delivery' || ($request->order_type == 'previous' && $request->previous_order_type == 'delivery')) {
                $amount += $branch->restaurant->delivery_price;
            }
            if ($branch->tax == 'true') {
                $tax = ($amount * $branch->tax_value) / 100;
                $amount = $amount + $tax;
            }
            $amount = $amount - $discount_value;
            /**
             * @check branch payment company
             */
            $order = SilverOrder::with('product')
                ->whereHas('product', function ($q) use ($branch) {
                    $q->where('branch_id', $branch->id);
                })
                ->where('status', 'in_cart')
                ->where('user_id', $user->id)
                ->first();
            if ($branch->payment_company == 'tap') {
                $user->update([
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ]);
                return redirect()->to(tap_payment($branch->online_token, $amount, $user->name, $user->email, $user->country->code, $user->phone_number, 'foodics_tap_payment_status', $order->id));
            } elseif ($branch->payment_company == 'express') {
                $user->update([
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                ]);
                $amount = number_format((float)$amount, 2, '.', '');
                return redirect()->to(express_payment($branch->merchant_key,$branch->express_password, $amount,'foodics_express_payment_status', $order->id,$user->name, $user->email));
            } elseif ($branch->payment_company == 'myFatoourah') {
                if ($request->online_type == 'visa') {
                    $charge = 2;
                } elseif ($request->online_type == 'mada') {
                    $charge = 6;
                } elseif ($request->online_type == 'apple_pay') {
                    $charge = 11;
                } else {
                    $charge = 2;
                }
                $name = $user->phone_number;
                $token = $branch->online_token;
                $data = array(
                    'PaymentMethodId' => $charge,
                    'CustomerName' => $name,
                    'DisplayCurrencyIso' => 'SAR',
                    'MobileCountryCode' => $user->country->code,
                    'CustomerMobile' => $user->phone_number,
                    'CustomerEmail' => $user->email,
                    'InvoiceValue' => $amount,
                    'CallBackUrl' => route('checkOrderFoodicsStatus', $order->id),
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
                        $user->update([
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                        ]);
                        $order->update([
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
        foreach ($orders as $order) {
            $order->update([
                'payment_type' => $request->payment_method == 'EasyMenu-online' ? 'online' : 'cash',
                'order_type' => $request->order_type,
                'foodics_branch_id' => RestaurantFoodicsBranch::findOrFail($request->branch_id)->id,
                'status' => 'sent',
                'previous_order_type' => $request->previous_order_type,
                'period_id' => $request->period_id,
                'day_id' => $request->day_id,
            ]);
            if ($request->discount_name != null) {
                $discount = FoodicsDiscount::whereBranchId($branch->id)
                    ->whereNameEn($request->discount_name)
                    ->first();
                if ($discount) {
                    checkProductDiscount($order->id, $discount->id);
                }
            }
        }
        $period = null;
        $day_id = null;
        $previous_type = null;
        if ($request->period_id and $request->day_id and $request->previous_order_type) {
            $period = RestaurantOrderPeriod::find($request->period_id)->start_at;
            $day_id = $request->day_id;
            $previous_type = $request->previous_order_type;
        }
        create_foodics_order($branch->restaurant->id, $branch_id, $orders, $user, $request->order_type, $request->payment_method, $latitude, $longitude, $period, $day_id, $previous_type);
        return redirect()->route('cart_details', $branch->id);
    }

    public function cart_details($id)
    {
        $branch = Branch::findOrFail($id);
        $restaurant = $branch->restaurant;
        $this->checkTheme($restaurant);
        $user = auth()->guard('web')->user();
        $orders = SilverOrder::with('product')
            ->whereHas('product', function ($q) use ($branch) {
                $q->where('branch_id', $branch->id);
            })
            ->where('status', 'sent')
            ->where('user_id', $user->id)
            ->get();
        return view('website.' . session('theme_path') . 'silver.foodics_order', compact('restaurant', 'branch', 'orders'));
    }

    public function emptySilverCart()
    {
        $user = Auth::guard('web')->user();
        $user->silver_orders()->delete();
        return redirect()->back();
    }

    public function apply_order_seller_code(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $this->validate($request, [
            'seller_code' => 'required|exists:restaurant_order_seller_codes,seller_code',
        ]);
        $seller_code = RestaurantOrderSellerCode::whereSellerCode($request->seller_code)
            ->where('restaurant_id', $order->restaurant_id)
            ->whereDate('start_at', '<=', Carbon::now())
            ->whereDate('end_at', '>', Carbon::now())
            ->first();
        if ($seller_code != null) {
            if ($order->seller_code_id == null) {
                $code_discount = ($order->order_price * $seller_code->discount_percentage) / 100;
                $order->update([
//                    'order_price' => $order->order_price - $code_discount,
                    'total_price' => $order->total_price - $code_discount,
                    'seller_code_id' => $seller_code->id,
                    'discount_value' => $code_discount,
                    'order_price' => $order->order_price - $code_discount,
                    
                ]);
                flash(trans('messages.seller_code_worked'))->success();
                return redirect()->back();
            } else {
                flash(trans('messages.seller_code_applied_before'))->error();
                return redirect()->back();
            }
        } else {
            flash(trans('messages.seller_code_not_working'))->error();
            return redirect()->back();
        }
    }

    public function check_order_foodics_status(Request $request, $order_id)
    {
        $order = SilverOrder::find($order_id);
        $branch = $order->product->branch;
        $token = $branch->online_token;
        $PaymentId = \Request::query('paymentId');
        $resData = MyFatoorahStatus($token, $PaymentId);
        $result = json_decode($resData);
        if ($result->IsSuccess === true && $result->Data->InvoiceStatus === "Paid") {
            $order->update([
                'invoice_id' => null,
            ]);
            $user = $order->user;
            $branch_id = $order->foodics_branch->foodics_id;
            $orders = SilverOrder::with('product')
                ->whereHas('product', function ($q) use ($branch) {
                    $q->where('branch_id', $branch->id);
                })
                ->where('status', 'in_cart')
                ->where('user_id', $order->user_id)
                ->get();
            foreach ($orders as $order) {
                $order->update([
                    'status' => 'sent',
                ]);
            }
            $payment_type = $order->payment_type == 'online' ? 'EasyMenu-online' : 'EasyMenu-cash';
            $period = null;
            $day_id = null;
            $previous_type = null;
            if ($order->period_id and $order->day_id and $order->previous_order_type) {
                $period = RestaurantOrderPeriod::find($order->period_id)->start_at;
                $day_id = $order->day_id;
                $previous_type = $order->previous_order_type;
            }
            create_foodics_order($branch->restaurant->id, $branch_id, $orders, $user, $order->order_type, $payment_type, $user->latitude, $user->longitude, $period, $day_id, $previous_type);
            return redirect()->route('cart_details', $branch->id);
        }
    }

    public function foodics_tap_payment_status(Request $request, $order_id)
    {
        $input = $request->all();
        $tap_id = $input['tap_id'];
        $basURL = "https://api.tap.company/v2/charges/" . $tap_id;
        $order = SilverOrder::find($order_id);
        $user = $order->user;
        $branch = $order->product->branch;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $basURL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "{}",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . $branch->online_token
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response);
            if ($response->response->code == '000') {
                $branch_id = $order->foodics_branch->foodics_id;
                $orders = SilverOrder::with('product')
                    ->whereHas('product', function ($q) use ($branch) {
                        $q->where('branch_id', $branch->id);
                    })
                    ->where('status', 'in_cart')
                    ->where('user_id', $order->user_id)
                    ->get();
                foreach ($orders as $order) {
                    $order->update([
                        'status' => 'sent',
                    ]);
                }
                $payment_type = $order->payment_type == 'online' ? 'EasyMenu-online' : 'EasyMenu-cash';
                $period = null;
                $day_id = null;
                $previous_type = null;
                if ($order->period_id and $order->day_id and $order->previous_order_type) {
                    $period = RestaurantOrderPeriod::find($order->period_id)->start_at;
                    $day_id = $order->day_id;
                    $previous_type = $order->previous_order_type;
                }
                create_foodics_order($branch->restaurant->id, $branch_id, $orders, $user, $order->order_type, $payment_type, $user->latitude, $user->longitude, $period, $day_id, $previous_type);
                return redirect()->route('cart_details', $branch->id);
            }
        }
    }
    public function foodics_express_payment_status(Request $request, $order_id)
    {
        $order = SilverOrder::find($order_id);
        $user = $order->user;
        $branch = $order->product->branch;
        $branch_id = $order->foodics_branch->foodics_id;
        $orders = SilverOrder::with('product')
            ->whereHas('product', function ($q) use ($branch) {
                $q->where('branch_id', $branch->id);
            })
            ->where('status', 'in_cart')
            ->where('user_id', $order->user_id)
            ->get();
        foreach ($orders as $order) {
            $order->update([
                'status' => 'sent',
            ]);
        }
        $payment_type = $order->payment_type == 'online' ? 'EasyMenu-online' : 'EasyMenu-cash';
        $period = null;
        $day_id = null;
        $previous_type = null;
        if ($order->period_id and $order->day_id and $order->previous_order_type) {
            $period = RestaurantOrderPeriod::find($order->period_id)->start_at;
            $day_id = $order->day_id;
            $previous_type = $order->previous_order_type;
        }
        create_foodics_order($branch->restaurant->id, $branch_id, $orders, $user, $order->order_type, $payment_type, $user->latitude, $user->longitude, $period, $day_id, $previous_type);
        return redirect()->route('cart_details', $branch->id);
    }

    public function show_position($id, $lat, $long, $type)
    {
        $order = Order::findOrFail($id);
        $order_setting = RestaurantOrderSetting::whereRestaurantId($order->restaurant_id)
            ->where('branch_id', $order->branch_id)
            ->whereIn('order_type', ['easymenu' , 'whatsapp'])
            ->first();
        $distance = distanceBetweenTowPlaces($lat, $long, $order->branch->latitude, $order->branch->longitude);
        switch ($type){
            case "delivery":
                $allowed_distance = $order_setting->distance;
                break;
            case "takeaway":
                $allowed_distance = $order_setting->takeaway_distance;
                break;
            case "previous":
                $allowed_distance = $order_setting->previous_distance;
                break;
            default:
                $allowed_distance = $order_setting->distance;
        }

        if ($allowed_distance >= $distance) {
            $message = 'انت داخل نطاق الطلبات';
            return response()->json(array('success' => true, 'data' => $message, 'status' => true));
        } else {
            $message = 'عفوأ أنت خارج نطاق الخدمات';
            return response()->json(array('success' => true, 'data' => $message, 'status' => false));
        }
    }

    public function foodics_show_position($id, $lat, $long, $type)
    {
        $branch = Branch::findOrFail($id);
        $allowed = $type == 'takeaway' ? $branch->takeaway_distance : $branch->delivery_distance;
        $distance = distanceBetweenTowPlaces($lat, $long, $branch->latitude, $branch->longitude);
        if ($allowed >= $distance) {
            $message = 'انت داخل نطاق الطلبات';
            return response()->json(array('success' => true, 'data' => $message, 'status' => true));
        } else {
            $message = 'عفوأ أنت خارج نطاق الخدمات';
            return response()->json(array('success' => true, 'data' => $message, 'status' => false));
        }
    }
    public function get_order_payment_types($id , $type)
    {
        $order = Order::findOrFail($id);
        $restaurant = $order->restaurant;
        $order_setting = RestaurantOrderSetting::whereRestaurantId($order->restaurant_id)
            ->where('branch_id', $order->branch_id)
            ->whereIn('order_type', ['easymenu' , 'whatsapp'])
            ->first();
        switch ($type){
            case "takeaway":
                $payment_method = $order_setting->takeaway_payment;
                break;
            case "delivery":
                $payment_method = $order_setting->delivery_payment;
                break;
            case "previous":
                $payment_method = $order_setting->previous_payment;
                break;
            default:
                $payment_method = $order_setting->delivery_payment;
        }
        $isLoyaltyPoint = 'false';
        $loyaltyBalance = 0;
        $loyaltySubscription =  \App\Models\ServiceSubscription::whereRestaurantId($restaurant->id)->whereHas('service' , function($query){
            $query->where('id' , 11);
           })
            ->whereIn('status' , ['active' , 'tentative'])
            ->first();
        if(  $restaurant->enable_loyalty_point == 'true' and $restaurant->enable_loyalty_point_paymet_method == 'true' and isset($loyaltySubscription->id) ){
            $isLoyaltyPoint = 'true';
            if($lp = LoyaltyPoint::where('type' , 'balance')->where('restaurant_id' , $restaurant->id)->where('user_id' , $order->user_id)->first()){
                $loyaltyBalance = $lp->amount;
            }
        }

        
        return response()->json(array('success' => true, 'data' => $payment_method , 'receipt' => $order_setting->receipt_payment , 'online' => $order_setting->online_payment , 'bank' => $order_setting->bank_transfer , 'loyalty_point' => $isLoyaltyPoint , 'loyaltyBalance' => $loyaltyBalance));
    }
}


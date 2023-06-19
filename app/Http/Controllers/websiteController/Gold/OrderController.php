<?php

namespace App\Http\Controllers\websiteController\Gold;

use App\Http\Controllers\Controller;
use App\Models\Day;
use App\Models\LoyaltyPoint;
use App\Models\LoyaltyPointHistory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RestaurantOrderPeriod;
use App\Models\RestaurantOrderSetting;
use App\Models\ServiceSubscription;
use App\Models\WhatsappBranch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use League\CommonMark\Util\UrlEncoder;

class OrderController extends Controller
{
    /**
     * @complete_order
     *
     */
    private function createLoyaltyPointOrder($order){
        $loyaltySubscription =  ServiceSubscription::whereRestaurantId($order->restaurant->id)->whereHas('service' , function($query){
            $query->where('id' , 11);
           })
            ->whereIn('status' , ['active' , 'tentative'])
            ->first();
        if(  $order->restaurant->enable_loyalty_point == 'true' and isset($loyaltySubscription->id) and !LoyaltyPointHistory::where('order_id' , $order->id)->first()):
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
    }
    public function complete_order(Request $request)
    {

        $this->validate($request , [
            'order_type'  => 'required|in:delivery,takeaway,previous,whatsapp,easymenu',
            'payment_method' => 'required|in:receipt_payment,online_payment,bank_transfer,loyalty_point',
            'previous_type' => 'required_if:order_type,previous',
            'period_id' => 'required_if:order_type,previous',
            'day_id' => 'required_if:order_type,previous',
            'notes' => 'sometimes',
        ]);

        $order = Order::find($request->order_id);
        if ($request->notes):
            $order->update([
                'notes' => $request->notes,
            ]);
        endif;
        $restaurant = $order->restaurant;
        $this->checkTheme($restaurant);
        if ($request->latitude == null || $request->longitude == null)
        {
            flash(trans('messages.mustDetermineLocation'))->error();
            return redirect()->back();
        }
        // check the orders distance for branch
        $order_setting = RestaurantOrderSetting::whereRestaurantId($order->restaurant_id)
            ->where('branch_id' , $order->branch_id)
            ->where('order_type' , $request->order_type)
            ->first();
//        if ($order_setting->payment_company == 'myFatoourah')
//        {
//            $this->validate($request , [
//                'payment_type' => 'required_if:payment_method,online_payment',
//            ]);
//        }
//        $distance = distanceBetweenTowPlaces($request->latitude , $request->longitude , $order->branch->latitude , $order->branch->longitude);

        if ($request->order_type == 'whatsapp')
        {
            if(WhatsappBranch::count() > 0):
                $request->validate([
                    'branch_id' => 'required|integer|exists:whatsapp_branches,id'  , 
                ] , [
                    'branch_id.*' => 'يرجي اختيار فرع اولا ..' ,
                ]);
                if($whatsappBranch = WhatsappBranch::find($request->branch_id)){
                    $order->update([
                        'whatsapp_branch_id' => $whatsappBranch->id , 
                        'whatsapp_number' => $whatsappBranch->phone , 
                    ]);
                }
            endif;

            $branch = $order->branch;
            $orderPrice = $order->order_price;
            $deliveryPrice = $request->previous_type == 'delivery' ? $order_setting->delivery_value : ($request->previous_type_method == 'delivery' ? $order_setting->delivery_value : 0 );
            $taxPrice = ($branch->tax == 'true' and $branch->tax_value > 0) ? (($branch->tax_value * $orderPrice) / 100) : 0;
            $count = $branch->restaurant->whatsapp_orders + 1;
            $branch->restaurant->update([
                'whatsapp_orders' => $count,
                
            ]);

            
            // check for payment method
            if ($request->payment_method == 'receipt_payment' or $request->payment_method == 'bank_transfer' or $request->payment_method == 'loyalty_point')
            {
                $total_price =  ($orderPrice + $deliveryPrice + $taxPrice);
                $status = 'new';
                if($request->payment_method == 'loyalty_point' and $loyaltyBalance = LoyaltyPoint::where('restaurant_id' , $order->restaurant_id)->where('user_id' , $order->user_id)->where('type' , 'balance')->first()):
                    if($total_price > $loyaltyBalance->amount){
                        throw ValidationException::withMessages([
                            'payment_method' => trans('messages.empty_balance')
                        ]);
                    }
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
                    $loyaltyBalance->update([
                        'amount' => $loyaltyBalance->amount - $total_price
                    ]);
                    $status  = 'completed';

                endif;
                // receipt_payment
                $order->update([
                    'type'      => $request->order_type,
                    'status'    => $status,
                    'latitude'  => $request->latitude,
                    'longitude' => $request->longitude,
                    'payment_method' => $request->payment_method,
                    'delivery_value' => $deliveryPrice,
                    'total_price' => $orderPrice + $deliveryPrice + $taxPrice ,
                    'tax' => $taxPrice ,
                    'previous_type' => $request->previous_type == 'previous' ? $request->previous_type_method : $request->previous_type,
                ]);
                $phone  = isset($whatsappBranch->id) ? $whatsappBranch->phone : $order_setting->whatsapp_number;
                // send to whatsapp
                $url = 'https://api.whatsapp.com/send?phone='.$phone.'&text=' ;
                $items = $order->order_items()->with('size' , 'product' , 'order_item_options.option')->get();
                $content = '' ;
                foreach($items as $index => $item):
                    $content .= 'رقم الطلب : ' . $order->id  . ' %0a';
                    $content .= '*' .($index + 1) . '-  '.$item->product->name.' '  . $item->product_count . 'x* %0a';
                    $content .= 'السعر : ' . $item->price  . ' %0a';
                    if(isset($item->size->id)):
                        $content .= 'الحجم : ' . $item->size->name ;
                    endif;
                    if($item->order_item_options->count() > 0){
                        $content .= '%0a_الإضافات_%0a' ;
                    }
                    foreach($item->order_item_options as $op):
                        $content .= $op->option->name . ' ' . $op->option_count . 'x '  . '%0a' ;
                    endforeach;

                    // $content .= '%0a';
                endforeach;

                $content .= '%0aرقم الجوال العميل : ' . $order->user->phone_number . '  %0a';

                $content .= '%0aطريقة التسليم  : ' .trans('messages.'.$request->previous_type);
                if($order->previous_type == 'delivery'):
                    $content .= '%0aلوكيشن العميل : '.urlencode('https://www.google.com/maps/search/?api=1&query=' .$request->latitude. ','.$request->longitude) . '';
                elseif($order->previous_type == 'takeaway'):
                    $content .= '%0aاسم الفرع : ' . $branch->name;
                endif;
                $content .= '%0aطريقة الدفع: ' . trans('messages.' . $order->payment_method);

                if($order->discount_value > 0)
                    $content .= '%0aالخصم : ' .$order->discount_value;
                // $content .= '%0aسعر الوجبات: ' .$order->order_price;
                $content .= '%0aقيمة الطلب : ' .$order->order_price;
                if($order->previous_type == 'delivery'):
                    $content .= '%0aسعر التوصيل: ' .$order->delivery_value;
                endif;
                if($taxPrice > 0):
                    $content .= '%0aالضريبة: ' .$order->tax;
                endif;
                $content .= '%0aإجمالي السعر: ' .$order->total_price;
                if ($order->notes != null)
                {
                    $content .= '%0a الملاحظات علي الطلب: ' .$order->notes;
                }
                if ($request->period_id != null)
                {
                    $period = RestaurantOrderPeriod::find($request->period_id);
                    if ($request->day_id != null)
                    {
                        $day = Day::find($request->day_id);
                    }
                    if ($period and $day)
                    {
                        $content .= '%0a ميعاد التسليم: ' .$period->start_at;
                        $content .= '%0a يوم التسليم: ' .$day->name_ar;
                        $content .= '%0a  نوع الطلب: ' .$request->previous_type_method;
                    }
                }
                return redirect($url . $content);
            }
            elseif ($request->payment_method == 'online_payment')
            {
                // online_payment
                if ($request->previous_type == 'delivery')
                {
                    $amount = $order->total_price + $order_setting->delivery_value;
                }else{
                    $amount = $order->total_price;
                }
                $amount = round($orderPrice + $deliveryPrice + $taxPrice);
                // check the payment company
                if ($order_setting->payment_company == 'tap')
                {
                    $order->update([
                        'type'       => $request->order_type,
                        'latitude'   => $request->latitude,
                        'longitude'  => $request->longitude,
                        'previous_type' => $request->previous_type == 'previous' ? $request->previous_type_method : $request->previous_type,
                        'period_id'    => $request->period_id,
                        'day_id'       => $request->day_id,
                        'delivery_value' => $deliveryPrice,
                        'tax' => $taxPrice,
                        'total_price' => $deliveryPrice + $order->order_price + $taxPrice,
                    ]);
                    return redirect()->to(tap_payment($order_setting->online_token , $amount , $order->user->name , $order->user->email , $order->user->country->code , $order->user->phone_number , 'tapRedirectBackGoldOrder' , $order->id));
                }elseif ($order_setting->payment_company == 'express')
                {
                    $order->update([
                        'type'       => $request->order_type,
                        'latitude'   => $request->latitude,
                        'longitude'  => $request->longitude,
                        'previous_type' => $request->previous_type == 'previous' ? $request->previous_type_method : $request->previous_type,
                        'period_id'    => $request->period_id,
                        'day_id'       => $request->day_id,
                        'delivery_value' => $deliveryPrice,
                        'tax' => $taxPrice,
                        'total_price' => $deliveryPrice + $order->order_price + $taxPrice,
                    ]);
                    $amount = number_format((float)$amount, 2, '.', '');
                    return redirect()->to(express_payment($order_setting->merchant_key , $order_setting->express_password , $amount,'express_success' , $order->id , $order->user->name , $order->user->email));
                }
                elseif ($order_setting->payment_company == 'myFatoourah')
                {
                    if ($request->payment_type == 'visa') {
                        $charge = 2;
                    } elseif ($request->payment_type == 'mada') {
                        $charge = 6;
                    } elseif ($request->payment_type == 'apple_pay') {
                        $charge = 11;
                    } else {
                        $charge = 2;
                    }
                    $name = $order->user->name;
                    $token = $order_setting->online_token;
                    $data = array(
                        'PaymentMethodId' => $charge,
                        'CustomerName' => $name,
                        'DisplayCurrencyIso' => 'SAR',
                        'MobileCountryCode' => $order->user->country->code,
                        'CustomerMobile' => $order->user->phone_number,
                        'CustomerEmail' => $order->user->email,
                        'InvoiceValue' => $amount,
                        'CallBackUrl' => route('checkUserOrderStatus' , $order_setting->id),
                        'ErrorUrl' => url('/error'),
                        'Language'=> app()->getLocale(),
                        'CustomerReference'=>'ref 1',
                        'CustomerCivilId'=>'12345678',
                        'UserDefinedField'=>'Custom field',
                        'ExpireDate'=>'',
                        'CustomerAddress' =>array(
                            'Block' =>'',
                            'Street' =>'',
                            'HouseBuildingNo' =>'',
                            'Address' =>'',
                            'AddressInstructions' =>'',
                        ),
                        'InvoiceItems'=>[array(
                            'ItemName' => $order->user->phone_number,
                            'Quantity' => '1',
                            'UnitPrice' => $amount,
                        )],
                    );
                    $data = json_encode($data);
                    //               dd($token , $data);

                    $fatooraRes = MyFatoorah($token, $data);
                    $result = json_decode($fatooraRes);
                    // return $result;
                    if ($result->IsSuccess === true) {
                        $order->update([
                            'invoice_id' => $result->Data->InvoiceId,
                            'type'       => $request->order_type,
                            'latitude'   => $request->latitude,
                            'longitude'  => $request->longitude,
                            'previous_type' => $request->previous_type == 'previous' ? $request->previous_type_method : $request->previous_type,
                            'period_id'    => $request->period_id,
                            'day_id'       => $request->day_id,
                            'delivery_value' => $deliveryPrice,
                            'tax' => $taxPrice,
                            'total_price' => $deliveryPrice + $order->order_price + $taxPrice,
                        ]);
                        return redirect()->to($result->Data->PaymentURL);
                    }
                    else {
                        return redirect()->to(url('/error'));
                    }
                }
            }
        }
        elseif ($request->order_type == 'easymenu')
        {
            $orders = $order->restaurant->orders + 1;
            $order->restaurant->update([
                'orders' => $orders,
            ]);
            if ($request->previous_type == 'delivery' || $request->previous_type == 'takeaway')
            {
                
                // check for payment method
                if ($request->payment_method == 'loyalty_point')
                {
                    if ($request->previous_type == 'delivery')
                    {
                        $total_price = $order->total_price + $order_setting->delivery_value;
                    }else{
                        $total_price = $order->total_price;
                    }
                    if($request->payment_method == 'loyalty_point' and $loyaltyBalance = LoyaltyPoint::where('restaurant_id' , $order->restaurant_id)->where('user_id' , $order->user_id)->where('type' , 'balance')->first()):
                        if($total_price > $loyaltyBalance->amount){
                            throw ValidationException::withMessages([
                                'payment_method' => trans('messages.empty_balance')
                            ]);
                        }
                        $this->createLoyaltyPointOrder($order);
                        $loyaltyBalance->update([
                            'amount' => $loyaltyBalance->amount - $total_price
                        ]);
                    endif;
                    // loyalty_point
                    $order->update([
                        'type'      => $request->previous_type,
                        'status'    => 'completed',
                        'latitude'  => $request->latitude,
                        'longitude' => $request->longitude,
                        'payment_method' => 'loyalty_point',
                        'delivery_value' => $request->previous_type == 'delivery' ? $order_setting->delivery_value : null,
                        // 'total_price' =>  $total_price , 
                    ]);
                    flash(trans('messages.order_received_successfully'))->success();
                    return redirect()->route('GoldReceivedOrder' , $order->id);
                }
                elseif ($request->payment_method == 'receipt_payment')
                {
                    // receipt_payment
                    $order->update([
                        'type'      => $request->previous_type,
                        'status'    => 'new',
                        'latitude'  => $request->latitude,
                        'longitude' => $request->longitude,
                        'payment_method' => 'receipt_payment',
                        'delivery_value' => $request->previous_type == 'delivery' ? $order_setting->delivery_value : null,
                    ]);
                    flash(trans('messages.order_received_successfully'))->success();
                    return redirect()->route('GoldReceivedOrder' , $order->id);
                }
                elseif ($request->payment_method == 'online_payment')
                {
                    // online_payment
                    /**
                     * @check setting payment type
                     */
                    if ($request->previous_type == 'delivery')
                    {
                        $amount = $order->total_price + $order_setting->delivery_value;
                    }else{
                        $amount = $order->total_price;
                    }
                    if ($order_setting->payment_company == 'tap')
                    {
                        $order->update([
                            'type'       => $request->previous_type,
                            'latitude'   => $request->latitude,
                            'longitude'  => $request->longitude,
                            'delivery_value' => $request->previous_type == 'delivery' ? $order_setting->delivery_value : null,
                        ]);
                        return redirect()->to(tap_payment($order_setting->online_token , $amount , $order->user->name , $order->user->email , $order->user->country->code , $order->user->phone_number , 'tapRedirectBackGoldOrder' , $order->id));
                    }elseif ($order_setting->payment_company == 'express')
                    {
                        $order->update([
                            'type'       => $request->previous_type,
                            'latitude'   => $request->latitude,
                            'longitude'  => $request->longitude,
                            'delivery_value' => $request->previous_type == 'delivery' ? $order_setting->delivery_value : null,
                        ]);
                        $amount = number_format((float)$amount, 2, '.', '');
                        return redirect()->to(express_payment($order_setting->merchant_key , $order_setting->express_password , $amount,'express_success' , $order->id , $order->user->name , $order->user->email));
                    }
                    elseif ($order_setting->payment_company == 'myFatoourah')
                    {
                        if ($request->payment_type == 'visa') {
                            $charge = 2;
                        } elseif ($request->payment_type == 'mada') {
                            $charge = 6;
                        } elseif ($request->payment_type == 'apple_pay') {
                            $charge = 11;
                        } else {
                            $charge = 2;
                        }
                        $name = $order->user->name;
                        $token = $order_setting->online_token;
                        $data = array(
                            'PaymentMethodId' => $charge,
                            'CustomerName' => $name,
                            'DisplayCurrencyIso' => 'SAR',
                            'MobileCountryCode' => $order->user->country->code,
                            'CustomerMobile' => $order->user->phone_number,
                            'CustomerEmail' => $order->user->email,
                            'InvoiceValue' => $amount,
                            'CallBackUrl' => route('checkUserOrderStatus' , $order_setting->id),
                            'ErrorUrl' => url('/error'),
                            'Language'=> app()->getLocale(),
                            'CustomerReference'=>'ref 1',
                            'CustomerCivilId'=>'12345678',
                            'UserDefinedField'=>'Custom field',
                            'ExpireDate'=>'',
                            'CustomerAddress' =>array(
                                'Block' =>'',
                                'Street' =>'',
                                'HouseBuildingNo' =>'',
                                'Address' =>'',
                                'AddressInstructions' =>'',
                            ),
                            'InvoiceItems'=>[array(
                                'ItemName' => $order->user->phone_number,
                                'Quantity' => '1',
                                'UnitPrice' => $amount,
                            )],
                        );
                        $data = json_encode($data);
                        //   dd($token , $data);
                        $fatooraRes = MyFatoorah($token, $data);
                        $result = json_decode($fatooraRes);
                        if ($result->IsSuccess === true) {
                            $order->update([
                                'invoice_id' => $result->Data->InvoiceId,
                                'type'       => $request->previous_type,
                                'latitude'   => $request->latitude,
                                'longitude'  => $request->longitude,
                                'delivery_value' => $request->previous_type == 'delivery' ? $order_setting->delivery_value : null,
                            ]);
                            return redirect()->to($result->Data->PaymentURL);
                        }
                        else {
                            return redirect()->to(url('/error'));
                        }
                    }
                }
            }
            elseif ($request->previous_type == 'previous')
            {
                // check for payment method
                if ($request->payment_method == 'receipt_payment')
                {
                    // receipt_payment
                    $order->update([
                        'type'      => $request->previous_type,
                        'status'    => 'new',
                        'latitude'  => $request->latitude,
                        'longitude' => $request->longitude,
                        'payment_method' => 'receipt_payment',
                        'previous_type' => $request->previous_type_method,
                        'period_id'    => $request->period_id,
                        'day_id'       => $request->day_id,
                        'delivery_value' => $request->previous_type_method == 'delivery' ? $order_setting->delivery_value : null,
                    ]);
                    flash(trans('messages.order_received_successfully'))->success();
                    return redirect()->route('GoldReceivedOrder' , $order->id);
                }
                elseif ($request->payment_method == 'loyalty_point')
                {
                    if ($request->previous_type_method == 'delivery')
                    {
                        $total_price = $order->total_price + $order_setting->delivery_value;
                    }else{
                        $total_price = $order->total_price;
                    }
                    if($request->payment_method == 'loyalty_point' and $loyaltyBalance = LoyaltyPoint::where('restaurant_id' , $order->restaurant_id)->where('user_id' , $order->user_id)->where('type' , 'balance')->first()):
                        if($total_price > $loyaltyBalance->amount){
                            throw ValidationException::withMessages([
                                'payment_method' => trans('messages.empty_balance')
                            ]);
                        }
                        $this->createLoyaltyPointOrder($order);
                        $loyaltyBalance->update([
                            'amount' => $loyaltyBalance->amount - $total_price
                        ]);
                    endif;
                    // loyalty_point
                    $order->update([
                        'type'      => $request->previous_type,
                        'status'    => 'completed',
                        'latitude'  => $request->latitude,
                        'longitude' => $request->longitude,
                        'payment_method' => 'loyalty_point',
                        'delivery_value' => $request->previous_type == 'delivery' ? $order_setting->delivery_value : null,
                        'total_price' =>  $total_price , 
                    ]);
                    flash(trans('messages.order_received_successfully'))->success();
                    return redirect()->route('GoldReceivedOrder' , $order->id);
                }
                elseif ($request->payment_method == 'online_payment')
                {
                    // online_payment
                    if ($request->previous_type_method == 'delivery')
                    {
                        $amount = $order->total_price + $order_setting->delivery_value;
                    }else{
                        $amount = $order->total_price;
                    }
                    if ($order_setting->payment_company == 'tap')
                    {
                        $order->update([
                            'type'       => $request->previous_type,
                            'latitude'   => $request->latitude,
                            'longitude'  => $request->longitude,
                            'previous_type' => $request->previous_type_method,
                            'period_id'    => $request->period_id,
                            'day_id'       => $request->day_id,
                            'delivery_value' => $request->previous_type_method == 'delivery' ? $order_setting->delivery_value : null,
                        ]);
                        return redirect()->to(tap_payment($order_setting->online_token , $amount , $order->user->name , $order->user->email , $order->user->country->code , $order->user->phone_number , 'tapRedirectBackGoldOrder' , $order->id));
                    }
                    elseif ($order_setting->payment_company == 'myFatoourah')
                    {
                        if ($request->payment_type == 'visa') {
                            $charge = 2;
                        } elseif ($request->payment_type == 'mada') {
                            $charge = 6;
                        } elseif ($request->payment_type == 'apple_pay') {
                            $charge = 11;
                        } else {
                            $charge = 2;
                        }
                        $name = $order->user->name;
                        $token = $order_setting->online_token;
                        $data = array(
                            'PaymentMethodId' => $charge,
                            'CustomerName' => $name,
                            'DisplayCurrencyIso' => 'SAR',
                            'MobileCountryCode' => $order->user->country->code,
                            'CustomerMobile' => $order->user->phone_number,
                            'CustomerEmail' => $order->user->email,
                            'InvoiceValue' => $amount,
                            'CallBackUrl' => route('checkUserOrderStatus' , $order_setting->id),
                            'ErrorUrl' => url('/error'),
                            'Language'=> app()->getLocale(),
                            'CustomerReference'=>'ref 1',
                            'CustomerCivilId'=>'12345678',
                            'UserDefinedField'=>'Custom field',
                            'ExpireDate'=>'',
                            'CustomerAddress' =>array(
                                'Block' =>'',
                                'Street' =>'',
                                'HouseBuildingNo' =>'',
                                'Address' =>'',
                                'AddressInstructions' =>'',
                            ),
                            'InvoiceItems'=>[array(
                                'ItemName' => $order->user->phone_number,
                                'Quantity' => '1',
                                'UnitPrice' => $amount,
                            )],
                        );
                        $data = json_encode($data);
                        //               dd($token , $data);
                        $fatooraRes = MyFatoorah($token, $data);
                        $result = json_decode($fatooraRes);
                        if ($result->IsSuccess === true) {
                            $order->update([
                                'invoice_id' => $result->Data->InvoiceId,
                                'type'       => $request->previous_type,
                                'latitude'   => $request->latitude,
                                'longitude'  => $request->longitude,
                                'previous_type' => $request->previous_type_method,
                                'period_id'    => $request->period_id,
                                'day_id'       => $request->day_id,
                                'delivery_value' => $request->previous_type_method == 'delivery' ? $order_setting->delivery_value : null,
                            ]);
                            return redirect()->to($result->Data->PaymentURL);
                        }
                        else {
                            return redirect()->to(url('/error'));
                        }
                    }
                }
            }
        }
    }

    public function check_status(Request $request , $id)
    {
        $setting = RestaurantOrderSetting::find($id);
        $token = $setting->online_token;
        $PaymentId = \Request::query('paymentId');

        $resData = MyFatoorahStatus($token, $PaymentId);

        $result = json_decode($resData);

        if (isset($result->IsSuccess) and $result->IsSuccess === true && $result->Data->InvoiceStatus === "Paid" ) {
            $InvoiceId = $result->Data->InvoiceId;
            // $InvoiceId = 13274509;
            $order = Order::where('invoice_id', $InvoiceId)->firstOrFail();

            $order->update([
                'invoice_id' => null,
                'status'  => $order->type == 'whastapp' ? 'completed': 'new',
                'payment_method'  => 'online_payment',

            ]);
            $branch  = $order->branch;
            if($order->type == 'whatsapp'):
                // send to whatsapp
                $order_setting = RestaurantOrderSetting::whereRestaurantId($order->restaurant_id)
                    ->where('branch_id' , $order->branch_id)
                    ->where('order_type' , $order->type)
                    ->firstOrFail();
                $whatsappPhone = $order_setting->whatsapp_number;
                if($whatsappBranch = $order->whatsappBranch->id):
                    $whatsappPhone = $whatsappBranch->phone;
                  
                endif;
                $order->update([
                    'whatsapp_number' => $whatsappPhone
                ]);
                $url = 'https://api.whatsapp.com/send?phone='.$whatsappPhone.'&text=' ;
                $items = $order->order_items()->with('size' , 'product' , 'order_item_options.option')->get();
                $content = '' ;
                foreach($items as $index => $item):
                    $content .= '*' .($index + 1) . '-  '.$item->product->name.' '  . $item->product_count . 'x* %0a';
                    $content .= 'السعر : ' . $item->price  . ' %0a';
                    if(isset($item->size->id)):
                        $content .= 'الحجم : ' . $item->size->name ;
                    endif;
                    if($item->order_item_options->count() > 0){
                        $content .= '%0a_الإضافات_%0a' ;
                    }
                    foreach($item->order_item_options as $op):
                        $content .= $op->option->name . ' ' . $op->option_count . 'x '  . '%0a' ;
                    endforeach;

                    // $content .= '%0a';
                endforeach;

                $content .= '%0aرقم الجوال العميل : ' . $order->user->phone_number . '  %0a';

                $content .= '%0aطريقة التسليم  : ' .trans('messages.'.$order->previous_type);
                if($order->previous_type == 'delivery'):
                    $content .= '%0aلوكيشن العميل : '.urlencode('https://www.google.com/maps/search/?api=1&query=' .$order->latitude. ','.$order->longitude) . '';
                elseif($order->previous_type == 'takeaway'):
                    $content .= '%0aاسم الفرع : ' . $branch->name;
                endif;
                $content .= '%0aطريقة الدفع: ' . trans('messages.' . $order->payment_method)  . ' , رقم العملية : ' .$InvoiceId;
                $content .= '%0aحالة الدفع: مدفوع' ;

                if($order->discount_value > 0)
                    $content .= '%0aالخصم : ' .$order->discount_value;
                // $content .= '%0aسعر الوجبات: ' .$order->order_price;
                $content .= '%0aقيمة الطلب : ' .$order->order_price;
                if($order->previous_type == 'delivery'):
                    $content .= '%0aسعر التوصيل: ' .$order->delivery_value;
                endif;
                if($order->tax > 0):
                    $content .= '%0aالضريبة: ' .$order->tax;
                endif;
                $content .= '%0aإجمالي السعر: ' .$order->total_price;



                return redirect($url . $content);
            endif;

            flash(trans('messages.order_received_successfully'))->success();
            return redirect()->route('GoldReceivedOrder' , $order->id);
        }
    }
    /**
     * @tap payment callback url
     */
    public function gold_order_tap(Request $request , $order_id , $token = null)
    {
        $input = $request->all();
        $tap_id = $input['tap_id'];
        $basURL = "https://api.tap.company/v2/charges/" . $tap_id;

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
                "authorization: Bearer " . $token
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $order = Order::find($order_id);
            $response = json_decode($response);
            if ($response->response->code == '000')
            {
                $order->update([
                    'invoice_id' => null,
                    'status'  => $order->type == 'whastapp' ? 'completed': 'new',
                    'payment_method'  => 'online_payment',

                ]);
                $branch  = $order->branch;
                if($order->type == 'whatsapp'):
                    // send to whatsapp
                    $order_setting = RestaurantOrderSetting::whereRestaurantId($order->restaurant_id)
                        ->where('branch_id' , $order->branch_id)
                        ->where('order_type' , $order->type)
                        ->firstOrFail();
                    $whatsappPhone = $order_setting->whatsapp_number;
                    if($whatsappBranch = $order->whatsappBranch->id):
                        $whatsappPhone = $whatsappBranch->phone;

                    endif;
                    $order->update([
                        'whatsapp_number' => $whatsappPhone
                    ]);
                    $url = 'https://api.whatsapp.com/send?phone='.$whatsappPhone.'&text=' ;
                    $items = $order->order_items()->with('size' , 'product' , 'order_item_options.option')->get();
                    $content = '' ;
                    foreach($items as $index => $item):
                        $content .= '*' .($index + 1) . '-  '.$item->product->name.' '  . $item->product_count . 'x* %0a';
                        $content .= 'السعر : ' . $item->price  . ' %0a';
                        if(isset($item->size->id)):
                            $content .= 'الحجم : ' . $item->size->name ;
                        endif;
                        if($item->order_item_options->count() > 0){
                            $content .= '%0a_الإضافات_%0a' ;
                        }
                        foreach($item->order_item_options as $op):
                            $content .= $op->option->name . ' ' . $op->option_count . 'x '  . '%0a' ;
                        endforeach;

                        // $content .= '%0a';
                    endforeach;

                    $content .= '%0aرقم الجوال العميل : ' . $order->user->phone_number . '  %0a';

                    $content .= '%0aطريقة التسليم  : ' .trans('messages.'.$order->previous_type);
                    if($order->previous_type == 'delivery'):
                        $content .= '%0aلوكيشن العميل : '.urlencode('https://www.google.com/maps/search/?api=1&query=' .$order->latitude. ','.$order->longitude) . '';
                    elseif($order->previous_type == 'takeaway'):
                        $content .= '%0aاسم الفرع : ' . $branch->name;
                    endif;
                    $content .= '%0aطريقة الدفع: ' . trans('messages.' . $order->payment_method);
                    $content .= '%0aحالة الدفع: مدفوع' ;

                    if($order->discount_value > 0)
                        $content .= '%0aالخصم : ' .$order->discount_value;
                    // $content .= '%0aسعر الوجبات: ' .$order->order_price;
                    $content .= '%0aقيمة الطلب : ' .$order->order_price;
                    if($order->previous_type == 'delivery'):
                        $content .= '%0aسعر التوصيل: ' .$order->delivery_value;
                    endif;
                    if($order->tax > 0):
                        $content .= '%0aالضريبة: ' .$order->tax;
                    endif;
                    $content .= '%0aإجمالي السعر: ' .$order->total_price;

                    return redirect($url . $content);
                endif;
                flash(trans('messages.order_received_successfully'))->success();
                return redirect()->route('GoldReceivedOrder' , $order->id);
            }
        }
    }

    public function express_success($order_id)
    {
        $order = Order::find($order_id);
        $order->update([
            'invoice_id' => null,
            'status'  => $order->type == 'whastapp' ? 'completed': 'new',
            'payment_method'  => 'online_payment',

        ]);
        $branch  = $order->branch;
        if($order->type == 'whatsapp'):
            // send to whatsapp
            $order_setting = RestaurantOrderSetting::whereRestaurantId($order->restaurant_id)
                ->where('branch_id' , $order->branch_id)
                ->where('order_type' , $order->type)
                ->firstOrFail();
            $whatsappPhone = $order_setting->whatsapp_number;
            if($whatsappBranch = $order->whatsappBranch->id):
                $whatsappPhone = $whatsappBranch->phone;
                
            endif;
            $order->update([
                'whatsapp_number' => $whatsappPhone
            ]);
            $url = 'https://api.whatsapp.com/send?phone='.$whatsappPhone.'&text=' ;
            $items = $order->order_items()->with('size' , 'product' , 'order_item_options.option')->get();
            $content = '' ;
            foreach($items as $index => $item):
                $content .= '*' .($index + 1) . '-  '.$item->product->name.' '  . $item->product_count . 'x* %0a';
                $content .= 'السعر : ' . $item->price  . ' %0a';
                if(isset($item->size->id)):
                    $content .= 'الحجم : ' . $item->size->name ;
                endif;
                if($item->order_item_options->count() > 0){
                    $content .= '%0a_الإضافات_%0a' ;
                }
                foreach($item->order_item_options as $op):
                    $content .= $op->option->name . ' ' . $op->option_count . 'x '  . '%0a' ;
                endforeach;

                // $content .= '%0a';
            endforeach;

            $content .= '%0aرقم الجوال العميل : ' . $order->user->phone_number . '  %0a';

            $content .= '%0aطريقة التسليم  : ' .trans('messages.'.$order->previous_type);
            if($order->previous_type == 'delivery'):
                $content .= '%0aلوكيشن العميل : '.urlencode('https://www.google.com/maps/search/?api=1&query=' .$order->latitude. ','.$order->longitude) . '';
            elseif($order->previous_type == 'takeaway'):
                $content .= '%0aاسم الفرع : ' . $branch->name;
            endif;
            $content .= '%0aطريقة الدفع: ' . trans('messages.' . $order->payment_method);
            $content .= '%0aحالة الدفع: مدفوع' ;

            if($order->discount_value > 0)
                $content .= '%0aالخصم : ' .$order->discount_value;
            // $content .= '%0aسعر الوجبات: ' .$order->order_price;
            $content .= '%0aقيمة الطلب : ' .$order->order_price;
            if($order->previous_type == 'delivery'):
                $content .= '%0aسعر التوصيل: ' .$order->delivery_value;
            endif;
            if($order->tax > 0):
                $content .= '%0aالضريبة: ' .$order->tax;
            endif;
            $content .= '%0aإجمالي السعر: ' .$order->total_price;

            return redirect($url . $content);
        endif;
        flash(trans('messages.order_received_successfully'))->success();
        return redirect()->route('GoldReceivedOrder' , $order->id);
    }

    public function express_error()
    {
        flash('حدث خطأ ما الرجاء المحاولة في وقت لاحق')->error();
        return redirect()->back();
    }

    public function received_order($id)
    {
        $order = Order::findOrFail($id);
        $restaurant = $order->restaurant;
        $this->checkTheme($restaurant);
        $branch = $order->branch;
        $items = $order->order_items;
        return view('website.'.session('theme_path').'gold.accessories.received' , compact('order' ,'items', 'restaurant' , 'branch'));
    }
    public function empty_cart($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->back();
    }
    public function deleteOrderItem($id)
    {
        $item = OrderItem::findOrFail($id);
        $order = $item->order;
        /**
         * @calculate item price
         * 1- @check if order have options
         * 2- @calculate options and item price
         * 3- @decrease price from order
         * 4- @calcuate tax and decrease it
         */
        $options_price = 0;
        $tax = 0;
        if($item->order_item_options)
        {
            foreach ($item->order_item_options as $option)
            {
                $options_price += $option->option_count * $option->option->price;
            }
        }
        $item_price = ($item->product_count * $item->price) + $options_price;
        if ($order->branch->tax == 'true' and $order->branch->tax_value > 0)
        {
            $tax = ($item_price * $order->branch->tax_value) / 100;
        }
        $order_price = $item->order->order_price - $item_price;
        $total_price = $item->order->total_price - ($item_price + $tax);
        $tax_value = $item->order->tax - $tax;
        $order->update([
            'order_price' => $order_price,
            'total_price' => $total_price,
            'tax'         => $tax_value,
        ]);
        $item->delete();
        if ($order->order_items->count() == 0)
        {
            $order->delete();
        }
        flash(trans('messages.deleted'))->success();
        return redirect()->back();
    }
}

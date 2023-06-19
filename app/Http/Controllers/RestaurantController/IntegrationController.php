<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceSubscription;


class IntegrationController extends Controller
{
    public function index()
    {
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $subscriptionServices = ServiceSubscription::where('restaurant_id' , $restaurant->id)->whereHas('service' , function($query){
            $query->whereNotIn('type' , ['bank' ,'my_fatoora']);
           })
        ->whereIn('status' , ['active'])->with('service' , 'branch')->orderBy('end_at')->get();
        return view('restaurant.integrations.index' , compact('restaurant' , 'subscriptionServices'));
    }
    public function tentative_services()
    {
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $subscriptionServices = ServiceSubscription::where('restaurant_id' , $restaurant->id)->whereHas('service' , function($query){
            $query->whereNotIn('type' , ['bank' ,'my_fatoora']);
           })->whereIn('status' , ['tentative' , 'tentative_finished'])->with('service' , 'branch')->orderBy('end_at')->get();
        
        return view('restaurant.integrations.tentative_services' , compact('restaurant' , 'subscriptionServices'));
    }

    public function redirect_code(Request $request)
    {
        app('log')->debug('RECEIVED CALL BACK FROM Foodics');

        // get all data passed by foodics
        $d = json_decode(json_encode($request->all()),true);

        // $code = $d['order']['id'];
        file_put_contents('log.txt', $d['code'], FILE_APPEND);
        file_put_contents('log1.txt', $d['state'], FILE_APPEND);
        $code = $d['code'];
        $state = $d['state'];
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $user = Restaurant::where('id', $restaurant->id)->first()->id;
        OAuth2($code , $state , $user);
        // create_menu($user);
        return redirect()->route('RestaurantIntegration');
        // return $this->response->noContent();
    }
    public function foodics_subscription($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $branches   = Branch::whereRestaurantId($restaurant->id)->get();
        return view('restaurant.integrations.foodics_subscribe' , compact('restaurant' , 'branches'));
    }
    public function foodics_subscription_submit(Request $request)
    {
        $this->validate($request , [
            'branch_id'      => 'required|exists:branches,id',
            'payment_method' => 'required|in:bank,online',
            'transfer_photo' => 'required_if:payment_method,bank',
            'payment_type'   => 'required_if:payment_method,online|in:visa,mada,apple_pay',
        ]);
        $branch = Branch::find($request->branch_id);
        if ($request->payment_method == 'bank')
        {
            $branch->update([
                'foodics_resquest' => 'true',
                'transfer_photo' => $request->file('transfer_photo') == null ? null : UploadImage($request->file('transfer_photo') , 'photo' , '/uploads/transfers')
            ]);
            flash('تم إرسال صوره  التحويل البنكي الي الإدارة بنجاح')->success();
            return redirect()->route('RestaurantIntegration');
        }elseif ($request->payment_method == 'online')
        {
            $amount = 500;
            if ($request->payment_type == 'visa') {
                $charge = 2;
            } elseif ($request->payment_type == 'mada') {
                $charge = 6;
            } elseif ($request->payment_type == 'apple_pay') {
                $charge = 11;
            } else {
                $charge = 2;
            }
            $name = $branch->restaurant->name_en;
            $token = "1IzSLeOkLFQH1zljLzerCm2RpB8AjFZLf8MMhSy4d8rHb0h1uHqrBleBFlFv-M4SHnyeiWg2zWQraKYJGndFcFvaBIeCPDQNrNs1Zwo7O-4apFyAXXUVZOAKbYzncpn-1ay0BPxB1X5dNH0EuWQ9OTqzcnOI7c5Ola5Esxz0imTrbVuhmKZl7SWBrPCU_SOYt80BSDe5j2XY5skkK7e5TxDRbbibdZPM7S11aYmQ7xKmZvaSj916IhTNXuIZA73TYE4xxkXyL_8dhHobugeLHF58VJNBjMv2UvzEP0pSk0RqGs3-AeqYwD6S3BbVrOaGIbx4fwKlowd6SOoSqkMoD9tmFwgdbkMxzWKYGqxg7bcvB0r62jBqn4YXh0Ej8FO6mrTdWZo5bHviUDRPMitO2KQDvyXrlWnf74n9DxOfV7MbuutAr2K2L3hzCYkdfU0eqA3snq-3Xh1KFjRvd5QqofEt9ubK71Xd4TooKLyXD4hby5prqJTTEVi23bPsPmB1uZ0D1cawjjJB8eUTIwEiPTgdPOSIOTkVFm4OIrHEXT44NbJ9MyDInxmQK8-pGDr0rNeBeLo8J_uyHbfh_sVlpS7XT0d-ehaTDtENoyG0XMe7hgWDYWsNxIe1N3dXwwtyBXLgggAl6JvdDXBzY3wp13oFfTHdASeOtyO3d0zwvkF-9j0uF2WgHd-kqgEyQVV0_UifcbMafuYwTgbBod5iB1soNMoVcTfjlsmr8LV8CK7Nr8xq";
            $data = array(
                'PaymentMethodId' => $charge,
                'CustomerName' => $name,
                'DisplayCurrencyIso' => 'SAR',
                'MobileCountryCode' => $branch->restaurant->country->code,
                'CustomerMobile' => $branch->restaurant->phone_number,
                'CustomerEmail' => $branch->restaurant->email,
                'InvoiceValue' => $amount,
                'CallBackUrl' => route('checkRestaurantFoodicsStatus'),
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
                    $branch->update([
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
    public function check_status(Request $request)
    {

        $token = "1IzSLeOkLFQH1zljLzerCm2RpB8AjFZLf8MMhSy4d8rHb0h1uHqrBleBFlFv-M4SHnyeiWg2zWQraKYJGndFcFvaBIeCPDQNrNs1Zwo7O-4apFyAXXUVZOAKbYzncpn-1ay0BPxB1X5dNH0EuWQ9OTqzcnOI7c5Ola5Esxz0imTrbVuhmKZl7SWBrPCU_SOYt80BSDe5j2XY5skkK7e5TxDRbbibdZPM7S11aYmQ7xKmZvaSj916IhTNXuIZA73TYE4xxkXyL_8dhHobugeLHF58VJNBjMv2UvzEP0pSk0RqGs3-AeqYwD6S3BbVrOaGIbx4fwKlowd6SOoSqkMoD9tmFwgdbkMxzWKYGqxg7bcvB0r62jBqn4YXh0Ej8FO6mrTdWZo5bHviUDRPMitO2KQDvyXrlWnf74n9DxOfV7MbuutAr2K2L3hzCYkdfU0eqA3snq-3Xh1KFjRvd5QqofEt9ubK71Xd4TooKLyXD4hby5prqJTTEVi23bPsPmB1uZ0D1cawjjJB8eUTIwEiPTgdPOSIOTkVFm4OIrHEXT44NbJ9MyDInxmQK8-pGDr0rNeBeLo8J_uyHbfh_sVlpS7XT0d-ehaTDtENoyG0XMe7hgWDYWsNxIe1N3dXwwtyBXLgggAl6JvdDXBzY3wp13oFfTHdASeOtyO3d0zwvkF-9j0uF2WgHd-kqgEyQVV0_UifcbMafuYwTgbBod5iB1soNMoVcTfjlsmr8LV8CK7Nr8xq";
        $PaymentId = \Request::query('paymentId');
        $resData = MyFatoorahStatus($token, $PaymentId);
        $result = json_decode($resData);
        if ($result->IsSuccess === true && $result->Data->InvoiceStatus === "Paid") {
            $InvoiceId = $result->Data->InvoiceId;
            $branch = Branch::where('invoice_id', $InvoiceId)->first();
            $branch->update([
                'foodics_resquest' => 'false',
                'transfer_photo' => null,
                'invoice_id' => null,
                'foodics_status' => 'true',
            ]);
            $branch->restaurant->update([
                'foodics_status' => 'true',
            ]);
            flash('تمت عمليه الربط بنجاح ')->success();
            return redirect()->route('RestaurantIntegration');
        }
    }

    public function subscribe_foodics_service()
    {

    }
    public function foodics_integration()
    {
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $restaurant->update([
            'foodics_status' => 'true',
        ]);
//        create_menu($restaurant->id);
    }
    public function pull_menu($id)
    {
        create_menu($id);
        flash('تمت عمليه الربط وسحب المنيو بنجاح ')->success();
        return redirect()->route('RestaurantIntegration');
    }
    public function print_service_invoice($id)
    {
        $service = ServiceSubscription::find($id);
        return view('restaurant.integrations.invoice' , compact('service'));
    }
    public function remove_foodics_integration($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->update([
            'foodics_access_token' => null,
        ]);
        $branch = Branch::whereRestaurantId($restaurant->id)
            ->where('foodics_status' , 'true')
            ->first();
        // if ($branch):
        //     $branch->update([
        //         'foodics_status' => 'false',
        //     ]);
        // endif;
        // remove restaurant foodics menu
        \App\Models\Table::whereRestaurantId($restaurant->id)
            ->where('branch_id', $branch->id)
            ->delete();
        \App\Models\Modifier::whereRestaurantId($restaurant->id)
            ->where('foodics_id' , '!=' , null)
            ->delete();
        \App\Models\Option::whereRestaurantId($restaurant->id)
            ->where('foodics_id' , '!=' , null)
            ->delete();
        \App\Models\Product::whereRestaurantId($restaurant->id)
            ->where('branch_id', $branch->id)
            ->where('foodics_id' , '!=' , null)
            ->delete();
        \App\Models\MenuCategory::whereRestaurantId($restaurant->id)
            ->where('branch_id', $branch->id)
            ->where('foodics_id' , '!=' , null)
            ->delete();
        \App\Models\RestaurantFoodicsBranch::whereRestaurantId($restaurant->id)
            ->where('branch_id', $branch->id)
            ->where('foodics_id' , '!=' , null)
            ->delete();
        \App\Models\FoodicsDiscount::whereBranchId($branch->id)
            ->where('foodics_id' , '!=' , null)
            ->delete();
        // remove foodics service
        // $service = ServiceSubscription::whereRestaurantId($restaurant->id)
        //     ->whereServiceId(4)
        //     ->first();
        // if ($service):
        //     $service->delete();
        // endif;
        flash(trans('messages.deleted'))->success();
        return redirect()->back();
    }
}

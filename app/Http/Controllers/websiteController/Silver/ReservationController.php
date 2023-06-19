<?php

namespace App\Http\Controllers\websiteController\Silver;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\Reservation\ReservationBranch;
use App\Models\Reservation\ReservationOrder;
use App\Models\Reservation\ReservationPlace;
use App\Models\Reservation\ReservationTable;
use App\Models\Reservation\ReservationTableDate;
use App\Models\Reservation\ReservationTablePeriod;
use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:web');
    }

    public function getReservationData(Request $request, ReservationBranch $branch)
    {
        $restaurant = $branch->restaurant;
        $request->validate([
            // 'branch_id' => 'required|integer'  ,
            'date' => 'required|date',
        ]);

        $date = $request->date;
        $cTime = Carbon::createFromTimestamp(strtotime(date('H:i:s')))->addMinutes(15)->format('H:i:s');
        $places = ReservationPlace::where('status', 1)->whereHas('tables', function ($query) use ($restaurant, $branch, $request) {
            $query->where('restaurant_id', $restaurant->id)->where('status', 'available')->where('reservation_branch_id', $branch->id)->where('is_available', 1)->whereHas('periods')->whereHas('dates', function ($q) use ($request) {
                $q->where('date', '=', $request->date);
            });
        })->get()->map(function ($item) use ($restaurant, $branch, $date , $cTime) {

            $periods = ReservationTablePeriod::leftJoin('reservation_tables', 'reservation_tables.id', 'reservation_table_periods.reservation_table_id')

                ->where('reservation_place_id', $item->id)->where('reservation_table_periods.status', 'available')->whereNull('reservation_tables.deleted_at')->where('reservation_tables.status', 'available')->where('reservation_tables.is_available', 1)->where('reservation_tables.reservation_branch_id', $branch->id)

                 ->whereRaw('(reservation_table_dates.date != "'.date('Y-m-d').'" or reservation_table_periods.from > "'.$cTime . '")')
                ->leftJoin('reservation_branches', 'reservation_branches.id', 'reservation_tables.reservation_branch_id')
                ->leftJoin('reservation_table_dates', 'reservation_table_dates.reservation_table_id', 'reservation_tables.id')->where('reservation_table_dates.date', $date)
                ->orderBy('reservation_table_periods.from' , 'asc')
                ->select('reservation_table_periods.*',

                    'reservation_tables.title_' . app()->getLocale() . ' as title' ,
                    'reservation_tables.image as image' ,
                    DB::raw('if(is_available = -1 , "'.$date.'" , "'.$date.'" ) as date' ),
                    'reservation_branches.name_' . app()->getLocale() . ' as branch_name', 'reservation_tables.people_count','chair_min' , 'chair_max' , 'reservation_tables.type' ,
                    'reservation_tables.table_count', 'reservation_tables.price', DB::raw('(select count(period_id) from  reservation_orders as o where period_id = reservation_table_periods.id and date = "' . $date . '" and is_order = 1) as orders_count ,
                    (select if(sum(chairs) is null , 0 , sum(chairs))  from reservation_orders as ro where ro.reservation_table_id = reservation_tables.id and ro.period_id = reservation_table_periods.id and ro.is_order = 1 and date = "'.$date.'"   and reservation_tables.deleted_at is null ) as quantity'))->get();
            foreach($periods as $index => $period){

            }
            $item->periods= $periods;

            return $item;
        });

        return response([
            'places' => $places
        ]);
    }

    public function reservationPage1(Request $request, Restaurant $restaurant)
    {
        if (!auth('web')->check()):
            session()->put('redirect_to', route('reservation.page1', $restaurant->id));
            return redirect(route('showUserLogin', [$restaurant->id]));
        endif;
        $this->checkTheme($restaurant);
        if ($request->has('branch_id') and $branchx = $restaurant->reservationBranches()->where('status', 1)->find($request->branch_id)) $branch = $branchx;
        else $branch = $restaurant->reservationBranches()->where('status', 1)->orderBy('id')->first();
        $country = $restaurant->country;
        if ($request->method() == 'POST'):
            if (!auth('web')->check()):
                throw ValidationException::withMessages([
                    'name' => trans('messages.login_required')
                ]);
            endif;
            $user = auth('web')->user();

            $request->validate([
                'date' => 'required|date',
                'period_id' => 'required|integer|exists:reservation_table_periods,id',
                'branch_id' => 'required|integer' ,
            ], [
                'date.*' => trans('messages.error_select_date'),
                'period_id.*' => trans('messages.error_period_id'),
            ]);
            if (!$branch = ReservationBranch::where('status', 1)->find($request->branch_id) or $branch->status != 1):
                throw ValidationException::withMessages([
                    'branch_id' => trans('messages.error_branch_not_exist'),
                ]);
            endif;
            $period = ReservationTablePeriod::findOrFail($request->period_id);

            $table = $period->table()->withTrashed()->first();
            if($table->type == 'chair'):
                $request->validate([
                    'quantity' => 'required|integer|min:1' ,
                ]);
                // check quantity if out of stock or not
                $count = ReservationOrder::where('period_id' , $period->id)->where('is_order' , 1)->where('type'  , 'chair')->where('date' , $request->date)->sum('chairs');
                if($count >= $table->chair_max):
                    throw ValidationException::withMessages([
                        'period_id' => trans('messages.error_chair_max_count' , ['period' => date('h:i A' , strtotime($period->from))]),
                    ]);
                elseif($table->chair_max < ($count + $request->quantity)):
                    throw ValidationException::withMessages([
                        'period_id' => trans('messages.error_chair_max_only' , ['period' => date('h:i A' , strtotime($period->from) ), 'count' => ($table->chair_max - $count)]),
                    ]);
                endif;
            elseif($table->type == 'package'):
                $request->validate([
                    'quantity' => 'required|integer|min:1' ,
                ]);
                // check quantity if out of stock or not
                $count = ReservationOrder::where('period_id' , $period->id)->where('is_order' , 1)->where('date' , $request->date)->where('type'  , 'package')->sum('chairs');
                // if($count >= $table->chair_max):
                //     throw ValidationException::withMessages([
                //         'period_id' => trans('messages.error_chair_max_count' , ['period' => date('h:i A' , strtotime($period->from))]),
                //     ]);
                // elseif($table->chair_max < ($count + $request->quantity)):
                //     throw ValidationException::withMessages([
                //         'period_id' => trans('messages.error_chair_max_only' , ['period' => date('h:i A' , strtotime($period->from) ), 'count' => ($table->chair_max - $count)]),
                //     ]);
                // endif;


            endif;
            // return $table->dates()->get();
            if (!$date = $table->dates()->where('date', $request->date)->first()):
                throw ValidationException::withMessages([
                    'period_id' => trans('messages.error_select_date'),
                ]);
            endif;
            $tax = 0;
            if($table->type == 'chair') $price = $table->price * $request->quantity;
            elseif($table->type == 'package') $price = $table->price  * $request->quantity;
            else $price = $table->price;
            $totalPrice = $price;

            if ($restaurant->reservation_tax == 'true'):
                $tax = ($restaurant->reservation_tax_value * $price) / 100;
                $totalPrice += $tax;
            endif;

            $order = ReservationOrder::create([
                'restaurant_id' => $restaurant->id,
                'user_id' => $user->id,
                'date_id' => $date->id,
                'period_id' => $period->id,
                'people_count' => !empty($table->people_count) ? $table->people_count : 1 ,
                'reservation_table_id' => $table->id,
                'price' => $table->price,
                'chairs' => !empty($request->quantity) ? $request->quantity : 1 ,
                'type' => $table->type ,
                'tax' => $tax,
                'total_price' => $totalPrice,
                'time_from' => $period->from,
                'time_to' => $period->to,
                'is_order' => 0,
                'num' => ReservationOrder::createNum() ,
                'status' => 'not_paid',
                'date' => $date->date,
                'user_name' => $user->name,
                'user_phone' => $user->phone_number,
            ]);

            return redirect(route('reservation.page2', [$branch->id, $order->id]));
        endif;
        $branches = ReservationBranch::whereRestaurantId($restaurant->id)
            // ->where('id' , '!=' , $branch->id)
            ->where('status', 1)
            ->get();
        // return $branches;
        $tables = ReservationTable::whereRestaurantId($restaurant->id)
            ->orderBy('id', 'desc')
            ->get();
        $branchId = 0;
        $compact = ['restaurant', 'branches', 'tables', 'country', 'branchId'];
        if (isset($branch)):
            $dates = ReservationTableDate::leftJoin('reservation_tables', 'reservation_table_dates.reservation_table_id', 'reservation_tables.id')->where('reservation_tables.restaurant_id', $restaurant->id)->where('reservation_branch_id', $branch->id)
                ->whereRaw('exists (select * from reservation_table_periods as p where p.reservation_table_id = reservation_tables.id)')

                ->where('reservation_tables.status', 'available')
                ->where('reservation_tables.is_available', '1')->where('date' , '>=' , date('Y-m-d'))->whereHas('reservation_table.periods')->get()->pluck('date')->toArray();
            // return $dates;
            $canReservation = count($dates) > 0 ? true : false;
            $compact[] = 'dates';
            $compact[] = 'canReservation';
            $branchId = $branch->id;
            $compact[] = 'branch';
        endif;

        // return $dates;

        // return $country;
        return view('website.' . session('theme_path') . 'reservations.page1', compact($compact));
    }

    public function reservationPage2(Request $request, ReservationBranch $branch, ReservationOrder $order)
    {

        $restaurant = $branch->restaurant;
        if (!auth('web')->check()):
            session()->put('redirect_to', route('reservation.page1', $restaurant->id));
            return redirect(route('showUserLogin', [$restaurant->id]));
        endif;
        $this->checkTheme($restaurant);
        $reservation = $order;
        $country = $restaurant->country;
        if ($request->method() == 'POST'):
            $request->validate([
                'payment_type' => 'required|in:bank,online,cash',
                'note' => 'nullable|min:1|max:100000'
            ]);
            $order->update([
                'notes' => $request->note,
                'payment_type' => $request->payment_type,
            ]);
            return redirect(route('reservation.page3', [$branch->id, $reservation->id]));
        endif;
        return view('website.' . session('theme_path') . 'reservations.page2', compact('restaurant', 'branch', 'reservation', 'country'));
    }

    public function reservationPage3(Request $request, ReservationBranch $branch, ReservationOrder $order)
    {
        $restaurant = $branch->restaurant;
        if (!auth('web')->check()):
            session()->put('redirect_to', route('reservation.page1', $restaurant->id));
            return redirect(route('showUserLogin', [$restaurant->id]));
        endif;
        $this->checkTheme($restaurant);
        /**
         *  1- get request data from user (notes , payment_method)
         *  2- take the reservation_id from hidden input or path at url
         */
        $country = $restaurant->country;
        $reservation = $order; // static
        $reservation->update([
            'online_payment_fees' => null
        ]);
        if ($reservation->payment_type == 'bank') {
            // get the bank_id and transfer_photo from user
            // make the restaurant confirm the -> after confirmed transfer_photo will be null , status->paid
            $banks = Bank::where('restaurant_id', $restaurant->id)->get();
            $type = 'bank';
           
            return view('website.' . session('theme_path') . 'reservations.payment', compact('restaurant', 'branch', 'banks', 'reservation', 'type', 'country'));
        } elseif ($reservation->payment_type == 'online') {
            $type = 'online';
            $banks = Bank::where('restaurant_id', $restaurant->id)->get();
            if($restaurant->online_payment_fees > 0){
                $totalPrice = ( ($reservation->total_price * $restaurant->online_payment_fees) / 100 ) + $reservation->total_price;
                $reservation->update([
                    'online_payment_fees' => $restaurant->online_payment_fees
                ]);
            }else $totalPrice = $reservation->total_price;
            $totalPrice = round($totalPrice);
            return view('website.' . session('theme_path') . 'reservations.payment', compact('restaurant', 'banks', 'branch', 'reservation', 'type', 'country' , 'totalPrice'));
        }else{
            $type = $reservation->payment_type;
            $banks = Bank::where('restaurant_id', $restaurant->id)->get();
            return view('website.' . session('theme_path') . 'reservations.payment', compact('restaurant', 'banks', 'branch', 'reservation', 'type', 'country'));
        }
        $banks = Bank::where('restaurant_id', $restaurant->id)->get();

        return view('website.' . session('theme_path') . 'reservations.payment', compact('restaurant', 'branch', 'banks', 'reservation', 'country'));
    }

    public function reservationPage4(Request $request, ReservationBranch $branch, ReservationOrder $order)
    {
        $restaurant = $branch->restaurant;
        if (!auth('web')->check()):
            session()->put('redirect_to', route('reservation.page1', $restaurant->id));
            return redirect(route('showUserLogin', [$restaurant->id]));
        endif;
        $this->checkTheme($restaurant);
        // show the order details to user
        $reservation = $order;

        if ($request->method() == 'POST' and $reservation->is_order == 0):
            if ($reservation->payment_type == 'online'):
                if($restaurant->online_payment_fees > 0){
                    $totalPrice = ( ($reservation->total_price * $restaurant->online_payment_fees) / 100 ) + $reservation->total_price;
                }else $totalPrice = $reservation->total_price;
                $totalPrice = round($totalPrice);
                $amount = $totalPrice;
                // check restaurants payment company
                if ($restaurant->payment_company == 'tap') {
                    return redirect()->to(tap_payment($restaurant->online_token, $amount, $reservation->user->name, $reservation->user->email, $reservation->user->country->code, $reservation->user->phone_number, 'checkReservationTapStatus', $reservation->id));
                } elseif ($restaurant->payment_company == 'express') {
                    $amount = number_format((float)$amount, 2, '.', '');
                    return redirect()->to(express_payment($restaurant->merchant_key,$restaurant->express_password, $amount,'checkReservationExpressStatus',$reservation->id, $reservation->user->name, $reservation->user->email));
                } elseif ($restaurant->payment_company == 'myFatoourah') {
                    $request->validate([
                        'payment_method' => 'required|in:visa,mada,apple_pay'
                    ]);
                    // get the payment type from user
                    if ($request->payment_method == 'visa') {
                        $charge = 2;
                    } elseif ($request->payment_method == 'mada') {
                        $charge = 6;
                    } elseif ($request->payment_method == 'apple_pay') {
                        $charge = 11;
                    } else {
                        $charge = 2;
                    }
                    $name = !empty($reservation->user->name) ? $reservation->user->name : $restaurant->name;

                    $data = array(
                        'PaymentMethodId' => $charge,
                        'CustomerName' => $name,
                        'DisplayCurrencyIso' => $restaurant->country->currency_code,
                        'MobileCountryCode' => $restaurant->country->code,
                        'CustomerMobile' => $reservation->user->phone_number,
                        'CustomerEmail' => $reservation->user->email,
                        'InvoiceValue' => $amount,
                        'CallBackUrl' => route('checkReservationStatus' , $restaurant->id),
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
                    // $restaurant->online_token
                    $fatooraRes = MyFatoorah($restaurant->online_token, $data);
                    $result = json_decode($fatooraRes);
                    if ($result != null) {
                        if ($result->IsSuccess === true) {
                            if($restaurant->online_payment_fees > 0){
                                $totalPrice = ( ($reservation->total_price * $restaurant->online_payment_fees) / 100 ) + $reservation->total_price;
                            }else $totalPrice = $reservation->total_price;
                            $totalPrice = round($totalPrice);
                            $reservation->update([
                                'invoice_id' => $result->Data->InvoiceId,
                                'payment_type' => 'online',
                                'online_payment_type' => $request->payment_method , 
                                // 'total_price' => $totalPrice ,
                                'online_payment_fees' => $restaurant->online_payment_fees
                            ]);
                            return redirect()->to($result->Data->PaymentURL);
                        } else {
                            return redirect()->to(url('/error'));
                        }
                    } else {
                        return redirect()->to(url('/error'));
                    }
                } else {
                    flash('الدفع الاونلاين غير متاح لدي المطعم')->error();
                }

            elseif($reservation->payment_type == 'cash'):
                $reservation->update([
                    'payment_type' => 'cash',
                    'bank_id' => null,
                    'transfer_photo' => null,
                    'is_order' => 1,
                    'status' => 'not_paid',
                    'online_payment_fees' => null ,
                ]);
                if($reservation->table->isExpire() == true):
                    $reservation->table->update([
                        'is_available' => 0 ,
                    ]);
                endif;
            else: // bank

                $request->validate([
                    'bank_id' => 'required|integer',
                    'photo' => 'required|image',
                    
                ], [
                    'bank_id.*' => trans('messages.bank_not_found'),
                    'photo.*' => trans('messages.transfer_photo'),
                ]);

                if (!$bank = $restaurant->banks()->find($request->bank_id)):
                    throw ValidationException::withMessages([
                        'bank_id' => trans('messages.bank_not_found')
                    ]);
                endif;

                $reservation->update([
                    'payment_type' => 'bank',
                    'bank_id' => $bank->id,
                    'transfer_photo' => UploadImage($request->file('photo'), 'bank', 'uploads/transfers'),
                    'is_order' => 1,
                    'status' => 'not_paid',
                    'online_payment_fees' => null ,
                ]);
            endif;
        endif;
        $country = $restaurant->country;
        return redirect(route('reservation.summery' , [$branch->id , $reservation->id]));
    }
    public function summery(Request $request, ReservationBranch $branch, ReservationOrder $order)
    {
        
        $restaurant = $branch->restaurant;
        // if (!auth('web')->check()):
        //     session()->put('redirect_to', route('reservation.page1', $restaurant->id));
        //     return redirect(route('showUserLogin', [$restaurant->id]));
        // endif;
        $this->checkTheme($restaurant);
        // show the order details to user
        $reservation = $order;

        $country = $restaurant->country;
        return view('website.' . session('theme_path') . 'reservations.page4', compact('restaurant', 'branch', 'reservation', 'country'));
    }

    public function check_status(Request $request , $res_id)
    {
        $restaurant = Restaurant::find($res_id);
        $PaymentId = \Request::query('paymentId');
        $token = $restaurant->online_token;
        // $token = self::myFatoorahTestToken;
        $resData = MyFatoorahStatus($token, $PaymentId);
        $result = json_decode($resData);
        if ($result->IsSuccess === true && $result->Data->InvoiceStatus === "Paid") {
            $InvoiceId = $result->Data->InvoiceId;
            $order = ReservationOrder::where('invoice_id', $InvoiceId)->first();
            if(!empty($order->online_payment_fees)):
                $totalPrice = ( ($order->total_price * $order->online_payment_fees / 100) + $order->total_price );
            else:
                $totalPrice = $order->total_price;
            endif;
            $order->update([
                // 'invoice_id' => null,
                'status' => 'paid',
                'is_order' => 1,
                // 'is_confirm' => 1 , 
                'total_price' => $totalPrice
            ]);
            if($order->table->isExpire()):
                $order->table->update([
                    'is_available' => 0 ,
                ]);
            endif;

            // $order->period->update([
            //     'status' => 'not_available',
            // ]);
            flash(trans('messages.reservationOnlinePaymentDone'))->success();
            return redirect()->route('reservation.page4', [$order->table->reservation_branch_id, $order->id]);
        }else{
            return 'fail payment';
        }
    }

    public function check_tap_status(Request $request, $order_id)
    {
        $input = $request->all();
        $tap_id = $input['tap_id'];
        $basURL = "https://api.tap.company/v2/charges/" . $tap_id;
        $order = ReservationOrder::findOrFail($order_id);
        $restaurant = $order->restaurant;
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
                "authorization: Bearer " . $restaurant->online_token
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
                $order->update([
                    'invoice_id' => null,
                    'status' => 'paid',
                    'is_order' => 1,
                ]);
                $isExpire = $order->table->isExpire();
                if($isExpire == true):
                    $order->table->update([
                        'is_available' => 0 ,
                    ]);
                else:
                    $order->table->update([
                        'is_available' => 1 ,
                    ]);
                endif;
                flash(trans('messages.reservationOnlinePaymentDone'))->success();
                return redirect()->route('reservation.page4', [$order->table->reservation_branch_id, $order->id]);
            }
        }
    }
    public function check_express_status($order_id)
    {
        $order = ReservationOrder::findOrFail($order_id);
        $order->update([
            'invoice_id' => null,
            'status' => 'paid',
            'is_order' => 1,
        ]);
        flash(trans('messages.reservationOnlinePaymentDone'))->success();
        return redirect()->route('reservation.page4', [$order->table->reservation_branch_id, $order->id]);
    }


    public function loadPackageDetails(Restaurant $restaurant , $id , $date)
    {
        $period = ReservationTablePeriod::with('table')->findOrFail($id);
        $table = $period->table;
        $country = $restaurant->country;
        $order = DB::select('select sum(chairs) as quantity  from reservation_orders as ro where ro.reservation_table_id = '.$table->id.' and ro.period_id = '.$period->id.' and date = "'.$date.'" and ro.is_order = 1 group by ro.period_id');

        if(isset($order[0]->quantity)):
            $quantity = $table->chair_max - $order[0]->quantity ;
        else:
            $quantity = $table->chair_max;
        endif;
        return view('website.'.session('theme_path').'reservations.package-details' , compact('period' ,'table' , 'date' , 'restaurant' , 'country' , 'quantity'));
    }

}

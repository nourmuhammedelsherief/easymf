<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\CategoryService;
use App\Models\History;
use App\Models\MarketerOperation;
use App\Models\Report;
use App\Models\Restaurant;
use App\Models\SellerCode;
use App\Models\Service;
use App\Models\ServiceStore;
use App\Models\ServiceSubscription;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ServiceStoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth('restaurant')->check()) {
            return redirect(route('restaurant.login'));
        }
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id, 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $myServices = ServiceSubscription::where('restaurant_id', $restaurant->id)->whereNotNull('paid_at')
            ->where('status', 'active')
            ->get()
            ->pluck('service_id')
            ->toArray();
        $categories = CategoryService::with(['services' => function ($query) use ($restaurant) {
            $query->whereNotIn('type', ['bank', 'my_fatoora'])->with([
                'prices' => function ($query) use ($restaurant) {
                    return $query->where('country_id', $restaurant->country_id)->with('country');
                }
            ])->where('status', 'true')->orderBy('category_id', 'desc');
        }])->paginate(10);

        return view('restaurant.service_store.index', compact('categories', 'myServices', 'restaurant'));
    }

    public function getNewSubscription(Request $request, Service $service)
    {
        if (!auth('restaurant')->check()) {
            return redirect(route('restaurant.login'));
        }
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id, 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        // check if restaurant has previous service
        $sub = $restaurant->serviceSubscriptions()
            ->where('service_id', $service->id)
            ->first();
//        if (isset($sub->id) and $sub->paid_at != null and ($sub->status == 'active' and $sub->end_at > now()->addDays(30))):
//            flash(trans('dashboard.errors.service_is_subscribed_before'))->error();
//            return redirect()->back();
//        endif;
        $is_new = $sub == null ? 'true' : 'false';
        
        if ($service->getRealPrice(true, $restaurant->country_id) == 0) {
            return $this->freeSubscription($service);
        }
        $branches = Branch::with('subscription')
            ->whereRestaurantId($restaurant->id)
            ->whereHas('subscription', function ($q) {
                $q->whereIn('status', ['active' , 'tentative']);
            })->get();
        $serviceSubscription = ServiceSubscription::where('restaurant_id' , $restaurant->id)->where('service_id' , $service->id)->where('status' , 'tentative')->first();
        
        return view('restaurant.service_store.subscription', compact('restaurant', 'branches', 'service', 'is_new' , 'serviceSubscription'));
    }

    public function storeNewSubscriptionBank(Request $request, Service $service)
    {
        if (!auth('restaurant')->check()) {
            return redirect(route('restaurant.login'));
        }
        
        $user = auth('restaurant')->user();
        if ($user->type == 'employee'):
            if (check_restaurant_permission($user->id, 3) == false):
                abort(404);
            endif;
            $user = Restaurant::find($user->restaurant_id);
        endif;
        
        // validate service is subscribe before
        $sub = $user->serviceSubscriptions()
            ->where('service_id', $service->id)
            ->whereBranchId($request->branch_id)
            ->first();
        if (isset($sub->id) and $sub->paid_at != null and ($sub->status == 'active' and $sub->end_at > now()->addDays(30))):
            flash(trans('dashboard.errors.service_is_subscribed_before'))->error();
            return redirect()->back();
        endif;
        $this->validate($request, [
            'bank_id' => 'required|exists:banks,id',
            'transfer_photo' => 'required|mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000',
        ]);
        // return $user;
        $price = $request->price;
        $discount = $request->discount;
        $tax_value = $request->tax_value;
        if ($request->seller_code != null) {
            $seller_code = SellerCode::where('seller_name', $request->seller_code)
                ->where('active', 'true')
                ->where('country_id', $user->country_id)
                ->whereIn('type', ['service', 'both'])
                ->first();
        } else {
            $seller_code = null;
        }
        if (isset($sub->id)):
            $sub->update([
                'restaurant_id' => $user->id,
                'branch_id' => $request->branch_id,
                'restaurant_name' => $user->name_ar,
                'restaurant_phone' => $user->phone_number,
                'service_id' => $service->id,
                'type' => 'bank',
                'price' => $price,
                'photo' => UploadImage($request->file('transfer_photo'), 'photo', '/uploads/transfers'),
                'payment_type' => null,
                'paid_at' => null,
//                'end_at' => null,
                'seller_code_id' => $seller_code == null ? null : $seller_code->id,
                'discount' => $discount,
                'tax_value' => $tax_value,
            ]);


        else:
            $subscription = ServiceSubscription::create([
                'restaurant_id' => $user->id,
                'branch_id' => $request->branch_id,
                'restaurant_name' => $user->name_ar,
                'restaurant_phone' => $user->phone_number,
                'service_id' => $service->id,
                'type' => 'bank',
                'price' => $price,
                'seller_code_id' => $seller_code == null ? null : $seller_code->id,
                'discount' => $discount,
                'tax_value' => $tax_value,
                'photo' => UploadImage($request->file('transfer_photo'), 'photo', '/uploads/transfers'),
            ]);

        endif;
        flash(trans('messages.bankTransferDone'))->success();
        return redirect()->to(url('restaurant/services_store'));

    }

    private function freeSubscription(Service $service)
    {
        $user = auth('restaurant')->user();
        if ($user->type == 'employee'):
            if (check_restaurant_permission($user->id, 3) == false):
                abort(404);
            endif;
            $user = Restaurant::find($user->restaurant_id);
        endif;
        $subscription = ServiceSubscription::create([
            'restaurant_id' => $user->id,
            'restaurant_name' => $user->name_ar,
            'restaurant_phone' => $user->phone_number,
            'service_id' => $service->id,
            'type' => 'bank',
            'paid_at' => date('Y-m-d H:i:s'),
            'status' => 'active',
            'end_at' => Carbon::now()->addYear(),
            'price' => 0,
            'photo' => null,
        ]);
        flash(trans('messages.subscription_done'))->success();
        return redirect()->to(url('restaurant/services_store'));
    }

    public function storeNewSubscription(Request $request, Service $service)
    {
        if (!auth('restaurant')->check()) {
            return redirect(route('restaurant.login'));
        }
        $user = auth('restaurant')->user();
        if ($user->type == 'employee'):
            if (check_restaurant_permission($user->id, 3) == false):
                abort(404);
            endif;
            $user = Restaurant::find($user->restaurant_id);
        endif;
        $is_new = $request->is_new;
        if ($is_new == 'false'):
            // validate service is subscribe before
            $this->validate($request, [
                'payment_method' => 'required|in:bank,online',
                'branch_id' => 'sometimes|exists:branches,id',
                'payment_type' => 'sometimes|in:visa,mada,apple_pay',
                'seller_code' => 'nullable|exists:seller_codes,seller_name',
            ]);
        endif;

        if ($request->branch_id != null) {
            $sub = $user->serviceSubscriptions()
                ->where('service_id', $service->id)
                ->whereBranchId($request->branch_id)
                ->first();
        } else {
            $sub = $user->serviceSubscriptions()
                ->where('service_id', $service->id)
                ->first();
        }
        if (isset($sub->id) and $sub->paid_at != null and ($sub->status == 'active' and $sub->end_at > now()->addDays(30))):
            flash(trans('dashboard.errors.service_is_subscribed_before'))->error();
            return redirect()->back();
        endif;

        if ($service->id == 4) {
            Branch::find($request->branch_id)->update([
                'foodics_request' => 'true',
            ]);
        }
        if ($is_new == 'false'):
            $price = $service->getRealPrice(true);
            $discount = 0;
            if ($request->seller_code != null) {
                $seller_code = SellerCode::where('seller_name', $request->seller_code)
                    ->where('active', 'true')
                    ->where('country_id', $user->country_id)
                    ->whereIn('type', ['service', 'both'])
                    ->first();
                if ($seller_code) {
                    if ($seller_code->start_at <= Carbon::now() && $seller_code->end_at >= Carbon::now()) {
                        $discount_percentage = $seller_code->code_percentage;
                        $discount = ($price * $discount_percentage) / 100;
                        $price_after_percentage = $price - $discount;
                        $commission = $price * ($seller_code->percentage - $seller_code->code_percentage) / 100;
                        $total_commission = $seller_code->commission + $commission;
                        $seller_code->update([
                            'commission' => $total_commission,
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
                    }
                }
            }
            $tax = Setting::first()->tax;
            $tax_value = ($price * $tax) / 100;
            $price = $price + $tax_value;
            if ($request->payment_method == 'bank') {
                $banks = Bank::whereCountryId($user->country_id)
                    ->where('restaurant_id', null)
                    ->get();
                $seller_code = $request->seller_code == null ? null : $request->seller_code;
                $branch = $request->branch_id;
                return view('restaurant.service_store.bank_transfer', compact('user', 'tax_value', 'tax', 'discount', 'branch', 'seller_code', 'price', 'banks', 'service'));
            } else {
                // online payment By My fatoorah

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
                $price = number_format($price, 2, '.', '');
                $data = array(
                    'PaymentMethodId' => $charge,
                    'CustomerName' => $name,
                    'DisplayCurrencyIso' => 'SAR',
                    'MobileCountryCode' => $user->country->code,
                    'CustomerMobile' => $user->phone_number,
                    'CustomerEmail' => $user->email,
                    'InvoiceValue' => $price,
                    'CallBackUrl' => route('checkServiceStatus'),
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
                        'UnitPrice' => $price,
                    )],
                );
                $data = json_encode($data);
                $fatooraRes = MyFatoorah(self::myFatoorahToken, $data);
                $result = json_decode($fatooraRes);
                if ($result != null) {
                    if ($result->IsSuccess === true) {
                        if ($request->seller_code != null) {
                            $seller_code = SellerCode::where('seller_name', $request->seller_code)
                                ->where('active', 'true')
                                ->where('country_id', $user->country_id)
                                ->whereIn('type', ['service', 'both'])
                                ->first();
                        } else {
                            $seller_code = null;
                        }
                        if (isset($sub->id)):
                            $sub->update([
                                'restaurant_id' => $user->id,
                                'branch_id' => $request->branch_id,
                                'restaurant_name' => $user->name_ar,
                                'restaurant_phone' => $user->phone_number,
                                'service_id' => $service->id,
                                'type' => 'online',
                                'payment_type' => $request->payment_type,
                                'price' => $price,
                                'invoice_id' => $result->Data->InvoiceId,
                                'seller_code_id' => $seller_code == null ? null : $seller_code->id,
                                'discount' => $discount,
                                'tax_value' => $tax_value,
                            ]);
                        else:
                            $subscription = ServiceSubscription::create([
                                'restaurant_id' => $user->id,
                                'branch_id' => $request->branch_id,
                                'restaurant_name' => $user->name_ar,
                                'restaurant_phone' => $user->phone_number,
                                'service_id' => $service->id,
                                'type' => 'online',
                                'payment_type' => $request->payment_type,
                                'price' => $price,
                                'invoice_id' => $result->Data->InvoiceId,
                                'seller_code_id' => $seller_code == null ? null : $seller_code->id,
                                'discount' => $discount,
                                'tax_value' => $tax_value,
                            ]);
                        endif;

                        return redirect()->to($result->Data->PaymentURL);
                    } else {
                        return redirect()->to(url('/error'));
                    }
                } else {
                    return redirect()->to(url('/error'));
                }
            }
        else:
            // create new tentative service
            $subscription = ServiceSubscription::create([
                'restaurant_id' => $user->id,
                'branch_id' => $request->branch_id,
                'restaurant_name' => $user->name_ar,
                'restaurant_phone' => $user->phone_number,
                'service_id' => $service->id,
                'type' => 'bank',
                'price' => 0,
                'seller_code_id' => null,
                'discount' => 0,
                'tax_value' => 0,
                'status' => 'tentative',
                'end_at' => Carbon::now()->addDays(Setting::first()->branch_service_tentative_period),
            ]);
            if ($service->id == 1):
                $user->update([
                    'reservation_service' => 'true'
                ]);
            endif;
            if ($service->id == 4):
                $user->update([
                    'foodics_status' => 'true'
                ]);
            endif;
            flash(trans('messages.success_subscribe_service'))->success();
            if ($subscription->status == 'tentative'):
                return redirect()->to(url('restaurant/tentative_services'));
            else:
                return redirect()->to(url('restaurant/integrations'));
            endif;
        endif;
    }
}

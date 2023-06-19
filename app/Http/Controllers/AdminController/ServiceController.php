<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\CategoryService;
use App\Models\FoodicsDiscount;
use App\Models\History;
use App\Models\MarketerOperation;
use App\Models\Report;
use App\Models\Reservation\ReservationBranch;
use App\Models\Reservation\ReservationOrder;
use App\Models\Reservation\ReservationPlace;
use App\Models\Reservation\ReservationTable;
use App\Models\Restaurant;
use App\Models\RestaurantFoodicsBranch;
use App\Models\RestaurantOrderSetting;
use App\Models\SellerCode;
use App\Models\Service;
use App\Models\ServiceSubscription;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::withCount('prices')->whereNotIn('type', ['bank', 'my_fatoora'])->get();
        return view('admin.services.index', compact('services'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Service $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Service $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $ourService)
    {
        $service = $ourService;
        $categories = CategoryService::get();
        return view('admin.services.edit', compact('service', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Service $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $ourService)
    {
        $service = $ourService;
        $data = $request->validate([
            'name' => 'required|min:1|max:190',
            'price' => 'required|numeric|min:0|max:100000',
            'status' => 'required|in:true,false',
            'photo' => 'nullable|mimes:png,jpg|max:5000',
            'category_id' => 'required|exists:categories_service,id',
            'description_ar' => 'nullable|min:1',
            'description_en' => 'nullable|min:1',
        ]);
        if ($request->hasFile('photo')) {
            $data['photo'] = UploadImageEdit($request->file('photo'), 'services', '/uploads/services', $service->photo);
        } else $data['photo'] = $service->photo;
        $service->update($data);
        flash(trans('messages.updated'))->success();
        return redirect(route('admin.service.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Service $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        //
    }


    public function getSubscriptions(Request $request)
    {

        $subscriptions = ServiceSubscription::whereNull('paid_at')->where('type', 'bank')->whereNotNull('photo')->with('restaurant.subscription.package', 'service')->get();
        // return $subscriptions;
        return view('admin.services.notPaid', compact('subscriptions'));
    }

    public function subscriptionConfirm(Request $request, ServiceSubscription $subscription, $status)
    {
        // if(!auth('restaurant')->check()):
        //     return redirect(route('restaurant.login'));
        // endif;

        $restaurant = $subscription->restaurant;
        if ($restaurant->serviceSubscriptions()->where('id', $subscription->id)->count() == 0 or $subscription->service->status != 'true'):
            flash(trans('dashboard.errors.subscription_service_not_found'))->error();
            return redirect()->back();
        endif;
        $service = $subscription->service;
        if ($service->type == 'booking'): // subscripe in bank , my_fatoora
            $services = Service::whereIn('type', ['bank', 'my_fatoora'])->where('status', 'true')->whereDoesntHave('subscriptions', function ($query) use ($restaurant) {
                $query->where('restaurant_id', $restaurant->id);
            })->get();
            // return $services;
            foreach ($services as $temp):
                $temp->subscriptions()->create([
                    'restaurant_id' => $restaurant->id,
                    'restaurant_name' => $restaurant->name_ar,
                    'restaurant_phone' => $restaurant->phone_number,
                    'type' => 'bank',
                    'status' => 'active',
                    'price' => 0,
                    'paid_at' => date('Y-m-d H:i:s'),
                ]);
            endforeach;
        endif; // end  subscripe in bank , my_fatoora

        if ($status == 'confirm'):
            $subscription->update([
                'paid_at' => Carbon::now(),
                'end_at' => Carbon::now()->addYear(),
                'status' => 'active',
            ]);
            if ($subscription->service_id == 4) {
                $subscription->restaurant->update([
                    'foodics_status' => 'true',
                ]);
                $branch = Branch::whereRestaurantId($subscription->restaurant->id)
                    ->where('foodics_request', 'true')
                    ->first();
                if ($branch != null) {
                    $branch->update([
                        'foodics_status' => 'true',
                        'foodics_request' => 'false',
                    ]);
                }
            }
            if ($subscription->service_id == 1) {
                $subscription->restaurant->update([
                    'reservation_service' => 'true',
                ]);
            }

            $isNew = true;
            if (History::where('type', 'service')->where('restaurant_id', $subscription->restaurant->id)->count() > 0) $isNew = false;
            History::create([
                'restaurant_id' => $subscription->restaurant_id,
                // 'package_id' => $restaurant->package->id,
                'branch_id' => $subscription->branch_id,
                'bank_id' => $subscription->bank_id,
                'operation_date' => Carbon::now(),
                'details' => $subscription->created_at < Carbon::now()->addMonths(-11) ? ' تجديد خدمة ' . $subscription->service->name : trans('dashboard.service_subscription_success', ['name' => $subscription->service->name]),
                'payment_type' => 'bank',
                'invoice_id' => $subscription->invoice_id,
                'paid_amount' => $subscription->price,
                'is_new' => $isNew,
                'type' => 'service',
                'transfer_photo' => $subscription->photo,
                'discount_value' => $subscription->discount,
                'tax_value' => $subscription->tax_value,
            ]);
            Report::create([
                'restaurant_id' => $subscription->restaurant_id,
                'amount' => $subscription->price,
                'status' => $subscription->created_at < Carbon::now()->addMonths(-11) ? 'renewed' : 'subscribed',
                'type' => 'service',
                'service_subscription_id' => $subscription->id,
                'service_id' => $subscription->service_id,
                'transfer_photo' => $subscription->photo,
                'tax_value' => $subscription->tax_value,
                'discount' => $subscription->discount,
            ]);
            flash(trans('dashboard.messages.save_successfully'))->success();
            return redirect()->back();
        elseif ($status == 'cancel'):
            if (Storage::disk('public_storage')->exists($subscription->image_path)):
                Storage::disk('public_storage')->delete($subscription->image_path);
            endif;
            $subscription->update([
                'photo' => null,
                'canceled_at' => Carbon::now(),
            ]);

            flash(trans('dashboard.messages.save_successfully'))->success();
            return redirect()->back();
        else:
            flash(trans('dashboard.errors.fail'))->error();
            return redirect()->back();
        endif;
    }

    public function service_restaurants($service_id, $status)
    {
        $service = Service::findOrFail($service_id);
        if ($status == 'less_30_day'):
            $restaurant_services = ServiceSubscription::with('restaurant')
                ->whereHas('restaurant', function ($q) {
                    $q->where('archive', 'false');
                    $q->with('subscription');
                    $q->whereHas('subscription', function ($d) {
                        $d->whereIn('status', ['active', 'tentative']);
                    });
                })
                ->whereStatus('active')
                ->whereDate('end_at', '<=', now()->addDays(30))
                ->whereServiceId($service_id)->get();
        else:
            $restaurant_services = ServiceSubscription::with('restaurant')
                ->whereHas('restaurant', function ($q) {
                    $q->where('archive', 'false');
                    $q->with('subscription');
                    $q->whereHas('subscription', function ($d) {
                        $d->whereIn('status', ['active', 'tentative']);
                    });
                })
                ->whereStatus($status)
                ->whereServiceId($service_id)->get();
        endif;
        $statusCount = [
            'active' => ServiceSubscription::with('restaurant')
            ->whereHas('restaurant', function ($q) {
                $q->where('archive', 'false');
                $q->with('subscription');
                $q->whereHas('subscription', function ($d) {
                    $d->whereIn('status', ['active', 'tentative']);
                });
            })
            ->whereStatus('active')
            ->whereServiceId($service_id)->count() , 


            'tentative' => ServiceSubscription::with('restaurant')
            ->whereHas('restaurant', function ($q) {
                $q->where('archive', 'false');
                $q->with('subscription');
                $q->whereHas('subscription', function ($d) {
                    $d->whereIn('status', ['active', 'tentative']);
                });
            })
            ->whereStatus('tentative')
            ->whereServiceId($service_id)->count() , 


            'finished' => ServiceSubscription::with('restaurant')
            ->whereHas('restaurant', function ($q) {
                $q->where('archive', 'false');
                $q->with('subscription');
                $q->whereHas('subscription', function ($d) {
                    $d->whereIn('status', ['active', 'tentative']);
                });
            })
            ->whereStatus('finished')
            ->whereServiceId($service_id)->count() , 

            'tentative_finished' =>  ServiceSubscription::with('restaurant')
            ->whereHas('restaurant', function ($q) {
                $q->where('archive', 'false');
                $q->with('subscription');
                $q->whereHas('subscription', function ($d) {
                    $d->whereIn('status', ['active', 'tentative']);
                });
            })
            ->whereStatus('tentative_finished')
            ->whereServiceId($service_id)->count() , 

            'less_30_day' => ServiceSubscription::with('restaurant')
            ->whereHas('restaurant', function ($q) {
                $q->where('archive', 'false');
                $q->with('subscription');
                $q->whereHas('subscription', function ($d) {
                    $d->whereIn('status', ['active', 'tentative']);
                });
            })
            ->whereStatus('active')
            ->whereDate('end_at', '<=', now()->addDays(30))
            ->whereServiceId($service_id)->count() ,
        ];
        
        return view('admin.services.restaurants', compact('restaurant_services', 'service', 'status' , 'statusCount'));
    }

        public function delete_restaurant_service($id)
    {
        $subscription = ServiceSubscription::findOrFail($id);
        if ($subscription->service_id == 4) {
            if ($subscription->restaurant != null) {
                $subscription->restaurant->update([
                    'foodics_status' => 'false',
                    'foodics_access_token' => null,
                ]);
                $branch = Branch::whereRestaurantId($subscription->restaurant->id)
                    ->where('foodics_status', 'true')
                    ->first();
                if ($branch != null) {
                    $branch->update([
                        'foodics_status' => 'false',
                    ]);
                    // delete foodics discounts
                    FoodicsDiscount::whereBranchId($branch->id)->delete();
                    // delete foodicd branches
                    RestaurantFoodicsBranch::whereRestaurantId($branch->restaurant->id)->delete();
                }
            }
        }
        if ($subscription->service_id == 1) {
            if ($subscription->restaurant != null) {
                $subscription->restaurant->update([
                    'reservation_service' => 'false',
                ]);
                // delete restaurant branches
                ReservationBranch::whereRestaurantId($subscription->restaurant->id)->delete();
                ReservationPlace::whereRestaurantId($subscription->restaurant->id)->delete();
                ReservationTable::whereRestaurantId($subscription->restaurant->id)->delete();
                ReservationOrder::whereRestaurantId($subscription->restaurant->id)->delete();
            }
        }
        // check and delete service report
        $check_report = Report::where('service_subscription_id', $subscription->id)->first();
        if ($check_report) {
            $check_report->delete();
        }
        // check if the subscription has setting
        $setting = RestaurantOrderSetting::whereRestaurantId($subscription->restaurant_id)
            ->whereBranchId($subscription->branch_id)
            ->where('order_type', $subscription->service->type)
            ->first();
        if ($setting) {
            $setting->delete();
        }
        // delete service history
        $history = History::whereRestaurantId($subscription->restaurant_id)
            ->where('transfer_photo', $subscription->photo)
            ->orWhere('invoice_id', $subscription->invoice_id)
            ->whereRestaurantId($subscription->restaurant_id)
            ->first();
        if ($history) {
            $history->delete();
        }
        $subscription->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->back();
    }


    public function getNewSubscription($id)
    {
        $subscription = ServiceSubscription::findOrFail($id);
        return view('admin.services.renew_subscription', compact('subscription'));
    }

    public function storeNewSubscription(Request $request, $id)
    {
        $sub = ServiceSubscription::findOrFail($id);
        $user = $sub->restaurant;
        // validate service is subscribe before
        $this->validate($request, [
            'payment_method' => 'required|in:bank,online',
            'payment_type' => 'sometimes|in:visa,mada,apple_pay',
            'seller_code' => 'nullable|exists:seller_codes,seller_name',
        ]);


        if ($sub->service->id == 4) {
            Branch::find($request->branch_id)->update([
                'foodics_request' => 'true',
            ]);
        }
        $price = $sub->service->getRealPrice(true);
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
            return view('admin.services.bank_transfer', compact('user', 'tax_value', 'tax', 'discount', 'seller_code', 'price', 'banks', 'sub'));
        }
    }

    public function storeNewSubscriptionBank(Request $request, $id)
    {
        $sub = ServiceSubscription::findOrFail($id);
        $user = $sub->restaurant;
        // validate service is subscribe before
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
                'branch_id' => $sub->branch_id,
                'restaurant_name' => $user->name_ar,
                'restaurant_phone' => $user->phone_number,
                'service_id' => $sub->service->id,
                'type' => 'bank',
                'price' => $price,
                'photo' => UploadImage($request->file('transfer_photo'), 'photo', '/uploads/transfers'),
                'payment_type' => null,
                'paid_at' => null,
                'end_at' => null,
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
                'service_id' => $sub->service->id,
                'type' => 'bank',
                'price' => $price,
                'seller_code_id' => $seller_code == null ? null : $seller_code->id,
                'discount' => $discount,
                'tax_value' => $tax_value,
                'photo' => UploadImage($request->file('transfer_photo'), 'photo', '/uploads/transfers'),
            ]);

        endif;
        flash(trans('messages.bankTransferDone'))->success();
        return redirect()->to(url('admin/our_services'));
    }


}

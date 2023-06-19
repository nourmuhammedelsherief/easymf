<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\History;
use App\Models\MarketerOperation;
use App\Models\Package;
use App\Models\Report;
use App\Models\Setting;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::whereIn('id' , [1,2])
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.packages.index', compact('packages'));
    }

    public function edit($id)
    {
        $package = Package::findOrFail($id);
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);
        $this->validate($request, [
            'name_ar' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',

            'price' => 'required|numeric',

            'branch_price' => 'required|numeric',
            'duration' => 'required|numeric',
        ]);
        $package->update([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'description_ar' => $request->description_ar,
            'description_en' => $request->description_en,

            'price' => $request->price,

            'branch_price' => $request->branch_price,
            'duration' => $request->duration,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('packages.index');
    }

    /**
     * @get the un @confirmed @subscription
     * @confirm
     */
    public function confirm()
    {
        $subscriptions = Subscription::where('transfer_photo', '!=', null)
            ->where('type', 'restaurant')
            ->where('payment_type', 'bank')
            ->whereIn('status', ['finished', 'tentative_finished'])
            ->orWhere('payment', 'true')
            ->where('type', 'restaurant')
            ->where('payment_type', 'bank')
            ->where('transfer_photo', '!=', null)
            ->get();
        return view('admin.restaurants.subscriptions.notPaid', compact('subscriptions'));
    }

    public function confirm_branch()
    {
        $subscriptions = Subscription::where('transfer_photo', '!=', null)
            ->where('type', 'branch')
            ->where('status', '!=', 'active')
            ->where('payment_type', 'bank')
            ->orWhere('payment', 'true')
            ->where('type' , 'branch')
            ->where('payment_type', 'bank')
            ->where('transfer_photo', '!=', null)
            ->get();
        return view('admin.restaurants.subscriptions.notPaidBranch', compact('subscriptions'));
    }

    public function confirm_status($id, $status)
    {
        $subscription = Subscription::findOrFail($id);
        if ($status == 'confirm') {
            if ($subscription->end_at > Carbon::now()) {
                $end_at = $subscription->end_at->addMonths($subscription->package->duration);
            } else {
                $end_at = Carbon::now()->addMonths($subscription->package->duration);
            }
            //            @unlink(public_path('/uploads/transfers/' . $subscription->transfer_photo));

            if ($subscription->type == 'branch')
            {
                if ($subscription->status == 'finished' or $subscription->status == 'active')
                {
                    // create report as renewed
                    Report::create([
                        'restaurant_id'  => $subscription->restaurant_id,
                        'branch_id'      => $subscription->branch_id,
                        'seller_code_id' => $subscription->seller_code_id,
                        'amount'         => $subscription->price,
                        'status'         => 'renewed',
                        'bank_id'        => $subscription->bank_id,
                        'type'           => 'branch',
                        'transfer_photo' => $subscription->transfer_photo,
                        'discount'       => $subscription->discount_value,
                        'tax_value'      => $subscription->tax_value,
                    ]);
                    History::create([
                        'restaurant_id' => $subscription->restaurant->id,
                        'package_id' => $subscription->package->id,
                        'branch_id' => $subscription->branch_id,
                        'operation_date' => Carbon::now(),
                        'details' => 'تجديد اشتراك الفرع',
                        'payment_type' => 'bank',
                        'bank_id' => $subscription->bank_id,
                        'transfer_photo' => $subscription->transfer_photo,
                        'paid_amount' => $subscription->price,
                        'discount_value' => $subscription->discount_value,
                        'tax_value'      => $subscription->tax_value,
                    ]);
                }
                else{
                    // create report as subscribed
                    Report::create([
                        'restaurant_id'  => $subscription->restaurant_id,
                        'branch_id'      => $subscription->branch_id,
                        'seller_code_id' => $subscription->seller_code_id,
                        'amount'         => $subscription->price,
                        'status'         => 'subscribed',
                        'type'           => 'branch',
                        'bank_id'        => $subscription->bank_id,
                        'transfer_photo' => $subscription->transfer_photo,
                        'discount'       => $subscription->discount_value,
                        'tax_value'      => $subscription->tax_value,
                    ]);
                    History::create([
                        'restaurant_id' => $subscription->restaurant->id,
                        'package_id' => $subscription->package->id,
                        'branch_id' => $subscription->branch_id,
                        'operation_date' => Carbon::now(),
                        'details' => ' اشتراك فرع جديد',
                        'payment_type' => 'bank',
                        'bank_id' => $subscription->bank_id,
                        'transfer_photo' => $subscription->transfer_photo,
                        'paid_amount' => $subscription->price,
                        'discount_value' => $subscription->discount_value,
                        'tax_value'      => $subscription->tax_value,
                    ]);
                }
            }

            if ($subscription->type == 'restaurant')
            {
                if ($subscription->status == 'finished' or $subscription->status == 'active')
                {
                    // create report as renewed
                    Report::create([
                        'restaurant_id'  => $subscription->restaurant_id,
                        'branch_id'      => $subscription->branch_id,
                        'seller_code_id' => $subscription->seller_code_id,
                        'amount'         => $subscription->price,
                        'status'         => 'renewed',
                        'bank_id'        => $subscription->bank_id,
                        'type'           => 'restaurant',
                        'transfer_photo' => $subscription->transfer_photo,
                        'discount'       => $subscription->discount_value,
                        'tax_value'      => $subscription->tax_value,
                    ]);
                    History::create([
                        'restaurant_id' => $subscription->restaurant->id,
                        'package_id' => $subscription->package->id,
                        'branch_id' => $subscription->branch_id,
                        'operation_date' => Carbon::now(),
                        'details' => 'تجديد اشتراك المطعم',
                        'payment_type' => 'bank',
                        'bank_id' => $subscription->bank_id,
                        'transfer_photo' => $subscription->transfer_photo,
                        'paid_amount' => $subscription->price,
                        'discount_value' => $subscription->discount_value,
                        'tax_value'      => $subscription->tax_value,
                    ]);
                }
                else{
                    // create report as subscribed
                    Report::create([
                        'restaurant_id'  => $subscription->restaurant_id,
                        'branch_id'      => $subscription->branch_id,
                        'seller_code_id' => $subscription->seller_code_id,
                        'amount'         => $subscription->price,
                        'status'         => 'subscribed',
                        'type'           => 'restaurant',
                        'bank_id'        => $subscription->bank_id,
                        'transfer_photo' => $subscription->transfer_photo,
                        'discount'       => $subscription->discount_value,
                        'tax_value'      => $subscription->tax_value,
                    ]);
                    History::create([
                        'restaurant_id' => $subscription->restaurant->id,
                        'package_id' => $subscription->package->id,
                        'branch_id' => $subscription->branch_id,
                        'operation_date' => Carbon::now(),
                        'details' => ' اشتراك مطعم جديد',
                        'payment_type' => 'bank',
                        'bank_id' => $subscription->bank_id,
                        'transfer_photo' => $subscription->transfer_photo,
                        'paid_amount' => $subscription->price,
                        'discount_value' => $subscription->discount_value,
                        'tax_value'      => $subscription->tax_value,
                    ]);
                }
            }

            $subscription->update([
                'status' => 'active',
//                'transfer_photo' => null,
                'end_at' => $end_at,
                'payment' => 'false',
            ]);

            // modify marketer commission
            $operation = MarketerOperation::whereSubscriptionId($subscription->id)
                ->where('status', 'not_done')
                ->first();
            if ($operation != null) {

                $operation->update([
                    'status' => 'done',
                ]);
                $balance = $operation->marketer->balance + $operation->amount;
                $operation->marketer->update([
                    'balance' => $balance
                ]);
                $subscription->update(['seller_code_id' => $operation->seller_code_id]);

            }
            if ($subscription->branch != null) {
                $subscription->branch->update([
                    'status' => 'active',
                ]);
            }
            if ($subscription->type == 'restaurant') {
                $subscription->restaurant->update([
                    'status' => 'active',
                    'admin_activation' => 'true'
                ]);
                // update the main branch
                $main_branch = Branch::whereRestaurantId($subscription->restaurant->id)
                    ->where('main', 'true')
                    ->first();
                $main_branch->update([
                    'status' => 'active',
                ]);
                $main_branch->subscription->update([
                    'status' => 'active',
                    'end_at' => $end_at,
                    'payment' => 'false',
                ]);
            }
            flash(trans('messages.success_operation'))->success();
            return redirect()->back();
        } elseif ($status == 'cancel') {
            @unlink(public_path('/uploads/transfers/' . $subscription->transfer_photo));
            $subscription->update([
                'transfer_photo' => null,
            ]);
            flash(trans('messages.cancel_operation'))->success();
            return redirect()->back();
        }
    }
    /**
     * @foodics @transfer_photos
     * @confirm_foodics
     */
    public function confirm_foodics()
    {
        $branches = Branch::where('foodics_resquest' , 'true')
            ->where('transfer_photo' , '!=' , null)
            ->get();
        return view('admin.restaurants.subscriptions.foodics' , compact('branches'));
    }
    public function foodics_confirm($id , $status)
    {
        $branch = Branch::findOrFail($id);
        if ($status == 'confirm')
        {
            $branch->update([
                'foodics_resquest' => 'false',
                'transfer_photo' => null,
                'foodics_status' => 'true',
            ]);
            $branch->restaurant->update([
                'foodics_status' => 'true',
            ]);
            flash(trans('messages.success_operation'))->success();
            return redirect()->back();
        }else{
            $branch->update([
                'foodics_resquest' => 'false',
                'transfer_photo' => null,
            ]);
            flash(trans('messages.cancel_operation'))->success();
            return redirect()->back();
        }
    }
}

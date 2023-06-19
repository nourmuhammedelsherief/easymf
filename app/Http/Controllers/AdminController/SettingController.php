<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\History;
use App\Models\Principle;
use App\Models\Report;
use App\Models\Restaurant;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function about()
    {
        $about = AboutUs::find(1);
        return view('admin.settings.about' , compact('about'));
    }

    public function store_about(Request $request)
    {
        $about = AboutUs::find(1);
        $this->validate($request , [
            'title'    => 'required|string|max:191',
            'details'  => 'required|string',
            'photo'    => 'sometimes|mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000',
        ]);
        $about->update([
            'title'    => $request->title,
            'content'  => $request->details,
            'photo'    => $request->file('photo') == null ? $about->photo : UploadImageEdit($request->file('photo') , 'photo' , '/uploads/settings' , $about->photo),
        ]);
        flash('تم التعديل بنجاح')->success();
        return redirect()->back();
    }

    public function setting()
    {
        $setting = Setting::find(1);
        return view('admin.settings.setting' , compact('setting'));
    }

    public function store_setting(Request $request)
    {
        $this->validate($request , [
            'tentative_period'    => 'required|numeric',
            'branch_service_tentative_period'    => 'required|numeric',
            'active_whatsapp_number'   => 'required',
            'technical_support_number' => 'required',
            'customer_services_number' => 'required',
            'tax'  => 'required',
        ]);
        $setting = Setting::find(1);
        $setting->update($request->all());
        flash(trans('messages.updated'))->success();
        return redirect()->back();
    }
    public function histories(Request $request)
    {
        $year = $request->year == null ? Carbon::now()->format('Y') : $request->year;
        $month = $request->month == null ? Carbon::now()->format('m') : $request->month;
        $histories = History::orderBy('id' , 'desc')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->paginate(500);
        $month_total_amount = History::whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->sum('paid_amount');
        $tax_values = History::whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->sum('tax_value');
        $subscribed_restaurants = Report::whereType('restaurant')
            ->whereStatus('subscribed')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->count();
        $renewed_restaurants = Report::whereType('restaurant')
            ->whereStatus('renewed')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->count();
        $registered_services = Report::whereType('service')
            ->where('status', 'subscribed')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->count();
        $renewed_services = Report::whereType('service')
            ->where('status', 'renewed')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->count();
        $subscribed_branches = Report::whereType('branch')
            ->whereStatus('subscribed')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->count();
        $renewed_branches = Report::whereType('branch')
            ->whereStatus('renewed')
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->count();
        return view('admin.settings.histories' , compact('histories','renewed_branches','renewed_services','tax_values','month_total_amount' , 'year' , 'month' , 'subscribed_restaurants' , 'registered_services' , 'renewed_restaurants' , 'subscribed_branches'));
    }
    public function report_histories(Request $request)
    {
        $year = $request->year == null ? Carbon::now()->format('Y') : $request->year;
        $month = $request->month == null ? Carbon::now()->format('m') : $request->month;

        $histories = Report::whereType($request->type)
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->whereStatus($request->status)
            ->get();
        $month_total_amount = Report::whereType($request->type)
            ->whereyear('created_at','=',$year)
            ->whereMonth('created_at','=',$month)
            ->whereStatus($request->status)
            ->sum('amount');
        return view('admin.settings.report_histories' , compact('histories','month_total_amount' , 'year' , 'month'));
    }
    public function delete_histories($id)
    {
        $history = History::findOrFail($id);
        $report = Report::whereRestaurantId($history->restaurant_id)
            ->whereBranchId($history->branch_id)
            ->where('amount' , $history->paid_amount)
            ->where('transfer_photo' , $history->transfer_photo)
            ->orWhere('invoice_id' , $history->invoice_id)
            ->whereRestaurantId($history->restaurant_id)
            ->whereBranchId($history->branch_id)
            ->where('amount' , $history->paid_amount)
            ->first();
        if ($report)
        {
            $report->delete();
        }
        $history->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->back();
    }
    public function restaurant_history($id)
    {
        $histories = History::whereRestaurantId($id)
            ->orderBy('id' , 'desc')
            ->paginate(500);
        return view('admin.settings.restaurant_history' , compact('histories'));
    }
    public function show_restaurant_history($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        if (auth('restaurant')->user()->type == 'employee'):
            if (check_restaurant_permission(auth('restaurant')->user()->id, 2) == false):
                abort(404);
            endif;
        endif;
        $histories = History::whereRestaurantId($id)
            ->orderBy('id', 'desc')
            ->paginate(500);
        return view('restaurant.user.history', compact('histories', 'restaurant'));
    }
}

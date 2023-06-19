<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\RestaurantOrderPeriod;
use App\Models\RestaurantOrderPeriodDay;
use App\Models\RestaurantOrderSetting;
use App\Models\RestaurantOrderSettingDay;
use App\Models\RestaurantOrderSettingDayPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderSettingDaysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $setting = RestaurantOrderSetting::findOrFail($id);
        $periods = RestaurantOrderPeriod::whereSettingId($setting->id)
            ->where('restaurant_id' , Auth::guard('restaurant')->user()->id)
            ->where('type' , 'setting')
            ->where('setting_id' , '!=' , null)
            ->get();
//        dd($periods);
        return view('restaurant.settings.periods.index' , compact('setting' , 'periods'));
    }
    public function previous_index($id , $setting_id = null)
    {
        $branch = Branch::findOrFail($id);
        if ($setting_id != null)
        {
            $periods = RestaurantOrderPeriod::whereBranchId($branch->id)
                ->where('restaurant_id' , Auth::guard('restaurant')->user()->id)
                ->whereSettingId($setting_id)
                ->where('type' , 'previous')
                ->get();
        }else{
            $periods = RestaurantOrderPeriod::whereBranchId($branch->id)
                ->where('restaurant_id' , Auth::guard('restaurant')->user()->id)
                ->where('type' , 'previous')
                ->where('setting_id' , null)
                ->get();
        }
//        dd($periods);
        return view('restaurant.settings.previous_periods.index' , compact('branch' , 'periods' , 'setting_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $setting = RestaurantOrderSetting::findOrFail($id);
        return view('restaurant.settings.periods.create' , compact('setting'));
    }
    public function previous_create($id , $setting_id = null)
    {
        $branch = Branch::findOrFail($id);
        return view('restaurant.settings.previous_periods.create' , compact('branch' , 'setting_id'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request , $id)
    {
        $setting = RestaurantOrderSetting::findOrFail($id);
        $this->validate($request , [
            'day_id*'  => 'required|exists:days,id',
            'period'   => 'nullable',
            'start_at' => 'required',
            'end_at'   => 'required',
        ]);

        // create new period
        $period = RestaurantOrderPeriod::create([
            'restaurant_id' => $setting->restaurant_id,
            'branch_id'     => $setting->branch_id,
            'setting_id'    => $setting->id,
            'period'        => $request->period,
            'start_at'      => $request->start_at,
            'end_at'        => $request->end_at,
        ]);
        // create period days
        if ($request->day_id != null)
        {
            foreach ($request->day_id as $day_id) {
                RestaurantOrderPeriodDay::create([
                    'period_id'  => $period->id,
                    'day_id'     => $day_id,
                ]);
            }
        }
        flash(trans('messages.created'))->success();
        return redirect()->route('order_setting_days.index' , $setting->id);
    }
    public function previous_store(Request $request , $id)
    {
        $branch = Branch::findOrFail($id);
        $this->validate($request , [
            'day_id*'  => 'required|exists:days,id',
            'period'   => 'nullable',
            'start_at' => 'required',
            'end_at'   => 'required',
        ]);

        $setting = RestaurantOrderSetting::findOrFail($request->setting_id);
        // create new period
        $period = RestaurantOrderPeriod::create([
            'restaurant_id' => $branch->restaurant_id,
            'branch_id'     => $branch->id,
            'period'        => $request->period,
            'setting_id'    => $setting->id,
            'start_at'      => $request->start_at,
            'end_at'        => $request->end_at,
            'type'          => 'previous'
        ]);
        // create period days
        if ($request->day_id != null)
        {
            foreach ($request->day_id as $day_id) {
                RestaurantOrderPeriodDay::create([
                    'period_id'  => $period->id,
                    'day_id'     => $day_id,
                ]);
            }
        }
        flash(trans('messages.created'))->success();
        return redirect()->route('order_previous_days.index' , [$branch->id , $setting->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $period = RestaurantOrderPeriod::findOrFail($id);
        return view('restaurant.settings.periods.edit' , compact('period' ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $period = RestaurantOrderPeriod::findOrFail($id);
        $this->validate($request , [
            'day_id*'  => 'required|exists:days,id',
            'period'   => 'nullable',
            'start_at' => 'required',
            'end_at'   => 'required',
        ]);
        $period->update([
            'period'        => $request->period,
            'start_at'      => $request->start_at,
            'end_at'        => $request->end_at,
        ]);
        // create period days
        $period->days()->delete();
        if ($request->day_id != null)
        {
            foreach ($request->day_id as $day_id) {
                RestaurantOrderPeriodDay::create([
                    'period_id'  => $period->id,
                    'day_id'     => $day_id,
                ]);
            }
        }
        flash(trans('messages.updated'))->success();
        return redirect()->route('order_setting_days.index' , $period->setting->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $period = RestaurantOrderPeriod::findOrFail($id);
        $period->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('order_setting_days.index' , $period->setting->id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function previous_edit($id)
    {
        $period = RestaurantOrderPeriod::findOrFail($id);
        $branch = $period->branch;
        return view('restaurant.settings.previous_periods.edit' , compact('period' , 'branch' ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function previous_update(Request $request, $id)
    {
        $period = RestaurantOrderPeriod::findOrFail($id);
        $this->validate($request , [
            'day_id*'  => 'required|exists:days,id',
            'period'   => 'nullable',
            'start_at' => 'required',
            'end_at'   => 'required',
        ]);
        $period->update([
            'period'        => $request->period,
            'start_at'      => $request->start_at,
            'end_at'        => $request->end_at,
        ]);
        // create period days
        $period->days()->delete();
        if ($request->day_id != null)
        {
            foreach ($request->day_id as $day_id) {
                RestaurantOrderPeriodDay::create([
                    'period_id'  => $period->id,
                    'day_id'     => $day_id,
                ]);
            }
        }
        flash(trans('messages.updated'))->success();
        if ($period->setting_id)
        {
            return redirect()->route('order_previous_days.index' , [$period->branch->id , $period->setting_id]);
        }else{
            return redirect()->route('order_previous_days.index' , $period->branch->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function previous_destroy($id)
    {
        $period = RestaurantOrderPeriod::findOrFail($id);
        $period->delete();
        return redirect()->back();
    }
}

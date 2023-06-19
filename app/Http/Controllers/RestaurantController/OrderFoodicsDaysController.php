<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\RestaurantOrderPeriod;
use App\Models\RestaurantOrderPeriodDay;
use App\Models\RestaurantOrderSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderFoodicsDaysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $branch = Branch::findOrFail($id);
        if ($branch->foodics_status == 'false')
        {
            abort(404);
        }
        $periods = RestaurantOrderPeriod::whereBranchId($branch->id)
            ->where('restaurant_id' , Auth::guard('restaurant')->user()->id)
            ->where('type' , 'foodics_order')
            ->get();
//        dd($periods);
        return view('restaurant.settings.foodics_periods.index' , compact('branch' , 'periods'));
    }
    public function foodics_index($id)
    {
        $branch = Branch::findOrFail($id);
        if ($branch->foodics_status == 'false')
        {
            abort(404);
        }
        $periods = RestaurantOrderPeriod::whereBranchId($branch->id)
            ->where('restaurant_id' , Auth::guard('restaurant')->user()->id)
            ->where('type' , 'foodics')
            ->get();
//        dd($periods);
        return view('restaurant.settings.foodics_menu_periods.index' , compact('branch' , 'periods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $branch = Branch::findOrFail($id);
        if ($branch->foodics_status == 'false')
        {
            abort(404);
        }
        return view('restaurant.settings.foodics_periods.create' , compact('branch'));
    }

    public function foodics_create($id)
    {
        $branch = Branch::findOrFail($id);
        if ($branch->foodics_status == 'false')
        {
            abort(404);
        }
        return view('restaurant.settings.foodics_menu_periods.create' , compact('branch'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request , $id)
    {
        $branch = Branch::findOrFail($id);
        if ($branch->foodics_status == 'false')
        {
            abort(404);
        }
        $this->validate($request , [
            'day_id*'  => 'required|exists:days,id',
            'period'   => 'nullable',
            'start_at' => 'required',
            'end_at'   => 'required',
        ]);

        // create new period
        $period = RestaurantOrderPeriod::create([
            'restaurant_id' => $branch->restaurant_id,
            'branch_id'     => $branch->id,
            'period'        => $request->period,
            'start_at'      => $request->start_at,
            'end_at'        => $request->end_at,
            'type'          => 'foodics_order'

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
        return redirect()->route('order_foodics_days.index' , $branch->id);
    }
    public function foodics_store(Request $request , $id)
    {
        $branch = Branch::findOrFail($id);
        if ($branch->foodics_status == 'false')
        {
            abort(404);
        }
        $this->validate($request , [
            'day_id*'  => 'required|exists:days,id',
            'period'   => 'nullable',
            'start_at' => 'required',
            'end_at'   => 'required',
        ]);

        // create new period
        $period = RestaurantOrderPeriod::create([
            'restaurant_id' => $branch->restaurant_id,
            'branch_id'     => $branch->id,
            'period'        => $request->period,
            'start_at'      => $request->start_at,
            'end_at'        => $request->end_at,
            'type'          => 'foodics'

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
        return redirect()->route('menu_foodics_days.index' , $branch->id);
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
        $branch = $period->branch;
        if ($branch->foodics_status == 'false')
        {
            abort(404);
        }
        return view('restaurant.settings.foodics_periods.edit' , compact('period' ));
    }
    public function foodics_edit($id)
    {
        $period = RestaurantOrderPeriod::findOrFail($id);
        $branch = $period->branch;
        if ($branch->foodics_status == 'false')
        {
            abort(404);
        }
        return view('restaurant.settings.foodics_menu_periods.edit' , compact('period' , 'branch' ));
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
            'type'     => 'foodics_order'
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
        return redirect()->route('order_foodics_days.index' , $period->branch->id);
    }
    public function foodics_update(Request $request, $id)
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
            'type'          => 'foodics'
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
        return redirect()->route('menu_foodics_days.index' , $period->branch->id);
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
        return redirect()->route('order_foodics_days.index' , $period->branch->id);
    }
    public function foodics_destroy($id)
    {
        $period = RestaurantOrderPeriod::findOrFail($id);
        $period->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('menu_foodics_days.index' , $period->branch->id);
    }
}

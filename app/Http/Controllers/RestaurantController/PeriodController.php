<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Day;
use App\Models\RestaurantPeriod;
use App\Models\RestaurantPeriodDay;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $branch = Branch::findOrFail($id);
        $periods = RestaurantPeriod::whereRestaurantId($branch->restaurant_id)
            ->whereBranchId($branch->id)
            ->get();
        return view('restaurant.periods.index' , compact('branch',  'periods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $branch = Branch::findOrFail($id);
        $days = Day::all();
        return view('restaurant.periods.create' , compact('branch' , 'days'));
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
        $this->validate($request , [
            'name'   => 'nullable|string|max:191',
            'start_at' => 'required',
            'day_id*'  => 'required|exists:days,id',
        ]);
        // create new branch period
        $period = RestaurantPeriod::create([
            'restaurant_id' => $branch->restaurant_id,
            'branch_id'     => $branch->id,
            'name'          => $request->name,
            'start_at'      => $request->start_at,
            'end_at'        => $request->end_at,
        ]);
        // create period days
        if ($request->day_id != null)
        {
            foreach ($request->day_id as $day_id) {
                RestaurantPeriodDay::create([
                    'period_id'  => $period->id,
                    'day_id'     => $day_id,
                ]);
            }
        }
        flash(trans('messages.created'))->success();
        return redirect()->route('BranchPeriod' , $branch->id);
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
        $period = RestaurantPeriod::findOrFail($id);
        $days = Day::all();
        return view('restaurant.periods.edit' , compact('period' , 'days'));
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
        $period = RestaurantPeriod::findOrFail($id);
        $this->validate($request , [
            'name'   => 'nullable|string|max:191',
            'start_at' => 'required',
            'day_id*'  => 'required|exists:days,id',
        ]);
        // create new branch period
        $period->update([
            'name'          => $request->name,
            'start_at'      => $request->start_at,
            'end_at'        => $request->end_at,
        ]);
        // create period days
        if ($request->day_id != null)
        {
            $period->days()->delete();
            foreach ($request->day_id as $day_id) {
                RestaurantPeriodDay::create([
                    'period_id'  => $period->id,
                    'day_id'     => $day_id,
                ]);
            }
        }
        flash(trans('messages.updated'))->success();
        return redirect()->route('BranchPeriod' , $period->branch->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $period = RestaurantPeriod::findOrFail($id);
        $period->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('BranchPeriod' , $period->branch->id);
    }
}

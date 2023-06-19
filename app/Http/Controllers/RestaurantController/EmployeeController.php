<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Restaurant;
use App\Models\RestaurantEmployee;
use App\Models\RestaurantEmployeeBranch;
use App\Models\ServiceSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $checkOrderService = ServiceSubscription::whereRestaurantId($restaurant->id)
            ->whereIn('service_id' , [9 , 10])
            ->first();
        if ($checkOrderService == null)
        {
            abort(404);
        }
        $employees = RestaurantEmployee::whereRestaurantId($restaurant->id)
            ->get();
        return view('restaurant.employees.index' , compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $checkOrderService = ServiceSubscription::whereRestaurantId($restaurant->id)
            ->whereIn('service_id' , [10])
            ->first();
        if ($checkOrderService == null)
        {
            abort(404);
        }
        $branches = Branch::with('subscription' , 'service_subscriptions')
            // ->whereHas('subscription' , function ($q){
            //     $q->where('end_at' , '!=' , null);
            // })
            ->whereHas('service_subscriptions' , function ($q){
                $q->whereIn('service_id' , [10])
                ->whereIn('status' , ['active' , 'tentative']);
            })
            ->whereRestaurantId($restaurant->id)
            // ->whereStatus('active')
            ->where('foodics_status' , 'false')
            ->get();

            
        return view('restaurant.employees.create' , compact('branches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $this->validate($request , [
            'branch_id' => 'required|exists:branches,id',
            'name'   => 'required|string|max:191',
            'email' => 'required|string|email|max:255|unique:restaurant_employees',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => ['required', 'unique:restaurant_employees', 'regex:/^((05)|(01))[0-9]{8}/'],
        ]);
        // create new employee
        $employee = RestaurantEmployee::create([
            'restaurant_id' => $restaurant->id,
            'branch_id'  => $request->branch_id,
            'name'  => $request->name,
            'email'  => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password) ,
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('employees.index');

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
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $checkOrderService = ServiceSubscription::whereRestaurantId($restaurant->id)
            ->whereIn('service_id' , [9 , 10])
            ->first();
        if ($checkOrderService == null)
        {
            abort(404);
        }
        $employee = RestaurantEmployee::findOrFail($id);
        $branches = Branch::with('subscription' , 'service_subscriptions')
            // ->whereHas('subscription' , function ($q){
            //     $q->where('end_at' , '!=' , null);
            // })
            ->whereHas('service_subscriptions' , function ($q){
                $q->where('service_id' , 10)->whereIn('status' , ['active'  , 'tentative']);
            })
            ->whereRestaurantId($restaurant->id)
            // ->whereStatus('active')
            ->where('foodics_status' , 'false')
            ->get();
        return view('restaurant.employees.edit' , compact('branches' , 'employee'));
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
        $employee = RestaurantEmployee::findOrFail($id);
        $this->validate($request , [
            'branch_id' => 'required|exists:branches,id',
            'name'   => 'required|string|max:191',
            'email' => 'required|string|email|max:255|unique:restaurant_employees,email,'.$employee->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone_number' => ['required', 'unique:restaurant_employees,phone_number,' .$employee->id, 'regex:/^((05)|(01))[0-9]{8}/'],
        ]);
        // create new employee
        $employee->update([
            'branch_id'  => $request->branch_id,
            'name'  => $request->name,
            'email'  => $request->email,
            'phone_number' => $request->phone_number,
            'password' => $request->password == null ? $employee->password : Hash::make($request->password),
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('employees.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = RestaurantEmployee::findOrFail($id);
        $employee->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('employees.index');
    }
}

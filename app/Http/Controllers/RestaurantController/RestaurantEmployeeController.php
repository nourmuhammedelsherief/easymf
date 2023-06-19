<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Restaurant;
use App\Models\RestaurantPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RestaurantEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurant = auth()->guard('restaurant')->user();
        $employees = Restaurant::whereRestaurantId($restaurant->id)
            ->whereType('employee')
            ->get();
        return view('restaurant.restaurant_employees.index' , compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('restaurant.restaurant_employees.create' , compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $restaurant = auth()->guard('restaurant')->user();
        $this->validate($request , [
            'name'  => 'required|string|max:191',
            'email' => 'required|unique:restaurants,email|max:191',
            'password' => 'required|string|min:6|confirmed',
            'permission_id*' => 'required',
        ]);
        // create new restaurant employee
        $emp = Restaurant::create([
            'restaurant_id' => $restaurant->id,
            'name_ar'       => $request->name,
            'name_en'       => $request->name,
            'country_id'    => $restaurant->country_id,
            'city_id'       => $restaurant->city_id,
            'package_id'    => $restaurant->package_id,
            'phone_number'  => $restaurant->phone_number,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'latitude'      => $restaurant->latitude,
            'longitude'     => $restaurant->longitude,
            'status'        => $restaurant->status,
            'logo'          => $restaurant->logo,
            'ar'            => $restaurant->ar,
            'en'            => $restaurant->en,
            'type'          => 'employee',
        ]);
        if ($request->permission_id != null)
        {
            foreach ($request->permission_id as $permission) {
                RestaurantPermission::create([
                    'restaurant_id' => $emp->id,
                    'permission_id' => $permission,
                ]);
            }
        }
        flash(trans('messages.created'))->success();
        return redirect()->route('restaurant_employees.index');
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
        $employee = Restaurant::findOrFail($id);
        $permissions = Permission::all();
        return view('restaurant.restaurant_employees.edit' , compact('permissions' , 'employee'));
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
        $employee = Restaurant::findOrFail($id);
        $restaurant = auth()->guard('restaurant')->user();
        $this->validate($request , [
            'name'  => 'required|string|max:191',
            'email' => 'required|unique:restaurants,email,'.$employee->id,
            'password' => 'nullable|string|min:6|confirmed',
            'permission_id*' => 'required',
        ]);
        // create new restaurant employee
        $employee->update([
            'name_ar'       => $request->name,
            'name_en'       => $request->name,
            'country_id'    => $restaurant->country_id,
            'city_id'       => $restaurant->city_id,
            'package_id'    => $restaurant->package_id,
            'phone_number'  => $restaurant->phone_number,
            'email'         => $request->email,
            'password'      => $request->password != null ? Hash::make($request->password) : $employee->password,
            'latitude'      => $restaurant->latitude,
            'longitude'     => $restaurant->longitude,
            'status'        => $restaurant->status,
            'logo'          => $restaurant->logo,
            'ar'            => $restaurant->ar,
            'en'            => $restaurant->en,
            'type'          => 'employee',
        ]);
        if ($request->permission_id != null)
        {
            RestaurantPermission::whereRestaurantId($employee->id)->delete();
            foreach ($request->permission_id as $permission) {
                RestaurantPermission::updateOrCreate([
                    'restaurant_id' => $employee->id,
                    'permission_id' => $permission,
                ]);
            }
        }
        flash(trans('messages.updated'))->success();
        return redirect()->route('restaurant_employees.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Restaurant::findOrFail($id);
        $employee->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('restaurant_employees.index');
    }
}

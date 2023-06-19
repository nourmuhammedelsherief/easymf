<?php

namespace App\Http\Controllers\RestaurantController\Reservation;

use App\Http\Controllers\Controller;
use App\Models\Reservation\ReservationBranch;
use App\Models\Reservation\ReservationOrder;
use App\Models\Reservation\ReservationTable;
use App\Models\Restaurant;
use App\Models\ServiceSubscription;
use Illuminate\Http\Request;

class ReservationBranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:restaurant');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $checkReservation = ServiceSubscription::whereRestaurantId($restaurant->id)
            ->where('service_id' , 1)
            ->whereIn('status' , ['active' , 'tentative'])
            ->first();
        if ($checkReservation == null)
        {
            abort(404);
        }
        $branches = ReservationBranch::where('restaurant_id' , $restaurant->id)->get();
        return view('restaurant.reservations.branches.index', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('restaurant.reservations.branches.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [

            'name_ar' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
            'status' => 'required|boolean' ,
            'location_link' => 'nullable|url' ,
        ]);
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        // create new bank
        ReservationBranch::create([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'status' => $request->status  ,
            'restaurant_id' => $restaurant->id ,
            'location_link' => $request->location_link ,
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('restaurant.reservation.branch.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branch = ReservationBranch::findOrFail($id);
        return view('restaurant.reservations.branches.edit', compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReservationBranch $branch)
    {
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $branch = ReservationBranch::where('restaurant_id' , $restaurant->id)->findOrFail($branch->id);
        $this->validate($request, [
            'name_ar' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
            'status' => 'required|boolean',
            'location_link' => 'nullable|url' ,
        ]);
        $branch->update([

            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'status' => $request->status,
            'location_link' => $request->location_link
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('restaurant.reservation.branch.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $bank = ReservationBranch::where('restaurant_id' ,$restaurant->id )->findOrFail($id);
        $check = ReservationTable::where('reservation_branch_id' , $bank->id)->first();
        if ($check)
        {
            flash(trans('messages.cant_deleted'))->error();
            return redirect()->route('restaurant.reservation.branch.index');
        }
        $bank->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('restaurant.reservation.branch.index');
    }

}

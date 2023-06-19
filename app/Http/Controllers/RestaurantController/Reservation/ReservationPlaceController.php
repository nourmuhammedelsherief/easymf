<?php

namespace App\Http\Controllers\RestaurantController\Reservation;

use App\Http\Controllers\Controller;
use App\Models\Reservation\ReservationPlace;
use App\Models\Reservation\ReservationTable;
use App\Models\Restaurant;
use App\Models\ServiceSubscription;
use Illuminate\Http\Request;

class ReservationPlaceController extends Controller
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
        $places = ReservationPlace::where('restaurant_id' , $restaurant->id)->get();
        return view('restaurant.reservations.places.index', compact('places'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('restaurant.reservations.places.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [

            'name_ar' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
            'status' => 'required|boolean' ,
            'image'   => 'nullable|mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000',
        ]);
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $data['restaurant_id'] = $restaurant->id;
        if($request->hasFile('image')):
            $data['image'] = UploadImageEdit($request->file('image') , 'image' , '/uploads/reservation_places' , null);
        endif;
        // create new bank
        ReservationPlace::create($data);
        flash(trans('messages.created'))->success();
        return redirect()->route('restaurant.reservation.place.index');
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
        $place = ReservationPlace::findOrFail($id);
        return view('restaurant.reservations.places.edit', compact('place'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReservationPlace $place)
    {
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $place = ReservationPlace::where('restaurant_id' , $restaurant->id)->findOrFail($place->id);
        $data = $this->validate($request, [
            'name_ar' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
            'status' => 'required|boolean' ,
            'image'   => 'nullable|mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000',
        ]);

        if($request->hasFile('image')):
            $data['image'] = UploadImageEdit($request->file('image') , 'image' , '/uploads/reservation_places' , $place->image);
        endif;

        $place->update($data);
        flash(trans('messages.updated'))->success();
        return redirect()->route('restaurant.reservation.place.index');
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
        $place = ReservationPlace::where('restaurant_id' ,$restaurant->id )->findOrFail($id);
        $check = ReservationTable::where('reservation_place_id' , $place->id)->first();
        if ($check)
        {
            flash(trans('messages.cant_deleted'))->error();
            return redirect()->route('restaurant.reservation.place.index');
        }
        $place->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('restaurant.reservation.place.index');
    }

}

<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantDelivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
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
            if (check_restaurant_permission($restaurant->id , 6) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $deliveries = RestaurantDelivery::whereRestaurantId($restaurant->id)->paginate(500);
        return view('restaurant.deliveries.index' , compact('deliveries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('restaurant.deliveries.create');
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
            if (check_restaurant_permission($restaurant->id , 6) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $this->validate($request , [
            'name_ar'  => 'nullable|string|max:191',
            'name_en'  => 'nullable|string|max:191',
            'icon'     => 'required|mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000',
            'link'     => 'required|max:191',
        ]);
        // create new delivery
        RestaurantDelivery::create([
            'restaurant_id' => $restaurant->id,
            'name_ar'       => $request->name_ar,
            'name_en'       => $request->name_en,
            'icon'          => $request->file('icon') == null ? null : UploadImage($request->file('icon') , 'icon' , '/uploads/deliveries'),
            'link'          => $request->link,
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('deliveries.index');
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
        $delivery = RestaurantDelivery::findOrFail($id);
        return view('restaurant.deliveries.edit' , compact('delivery'));
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
        $delivery = RestaurantDelivery::findOrFail($id);
        $this->validate($request , [
            'name_ar'  => 'nullable|string|max:191',
            'name_en'  => 'nullable|string|max:191',
            'icon'     => 'nullable|mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000',
            'link'     => 'required|max:191',
        ]);
        $delivery->update([
            'name_ar'       => $request->name_ar,
            'name_en'       => $request->name_en,
            'icon'          => $request->file('icon') == null ? $delivery->icon : UploadImageEdit($request->file('icon') , 'icon' , '/uploads/deliveries' , $delivery->icon),
            'link'          => $request->link,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('deliveries.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delivery = RestaurantDelivery::findOrFail($id);
        if ($delivery->icon != null)
        {
            @unlink(public_path('/uploads/deliveries/' . $delivery->icon));
        }
        $delivery->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('deliveries.index');
    }
}

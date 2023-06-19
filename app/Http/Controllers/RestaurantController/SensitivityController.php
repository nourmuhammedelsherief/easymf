<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantSensitivity;
use App\Models\Sensitivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SensitivityController extends Controller
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
        $sensitivities = RestaurantSensitivity::whereRestaurantId($restaurant->id)->orderBy('id' , 'desc')->paginate(500);
        return view('restaurant.sensitivities.index' , compact('sensitivities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('restaurant.sensitivities.create');
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
            'name_ar'    => 'nullable|string|max:191',
            'name_en'    => 'nullable|string|max:191',
            'photo'      => 'required|mimes:jpg,jpeg,png,gif,tif,psd,bmp,webp|max:5000',
            'details_ar' => 'nullable|string',
            'details_en' => 'nullable|string',
        ]);
        RestaurantSensitivity::create([
            'restaurant_id' => $restaurant->id,
            'name_ar'       => $request->name_ar,
            'name_en'       => $request->name_en,
            'photo'         => $request->file('photo') == null ? null : UploadImage($request->file('photo') , 'photo' , '/uploads/sensitivities'),
            'details_ar'    => $request->details_ar,
            'details_en'    => $request->details_en,
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('sensitivities.index');
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
        $sensitivity = RestaurantSensitivity::findOrFail($id);
        return view('restaurant.sensitivities.edit' , compact('sensitivity'));
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
        $sensitivity = RestaurantSensitivity::findOrFail($id);
        $this->validate($request , [
            'name_ar'    => 'nullable|string|max:191',
            'name_en'    => 'nullable|string|max:191',
            'photo'      => 'nullable|mimes:jpg,jpeg,png,gif,tif,psd,bmp,webp|max:5000',
            'details_ar' => 'nullable|string',
            'details_en' => 'nullable|string',
        ]);
        $sensitivity->update([
            'name_ar'       => $request->name_ar,
            'name_en'       => $request->name_en,
            'photo'         => $request->file('photo') == null ? $sensitivity->photo : UploadImageEdit($request->file('photo') , 'photo' , '/uploads/sensitivities' , $sensitivity->photo),
            'details_ar'    => $request->details_ar,
            'details_en'    => $request->details_en,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('sensitivities.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sensitivity = RestaurantSensitivity::findOrFail($id);
        $dataPhotos = Sensitivity::all()->pluck('photo')->toArray();
        if ($sensitivity->photo != null and !in_array($sensitivity->photo , $dataPhotos))
        {
            @unlink(public_path('/uploads/sensitivities/' . $sensitivity->photo));
        }
        $sensitivity->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('sensitivities.index');
    }
}

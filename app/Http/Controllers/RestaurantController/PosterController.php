<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantPoster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PosterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 4) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $posters = RestaurantPoster::whereRestaurantId($restaurant->id)
            ->orderBy('id' , 'desc')
            ->paginate(500);
        return view('restaurant.posters.index' , compact('posters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('restaurant.posters.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 4) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $this->validate($request , [
            'poster'   => 'required|mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000',
            'name_ar'  => 'nullable|string|max:191',
            'name_en'  => 'nullable|string|max:191',
        ]);
        RestaurantPoster::create([
            'restaurant_id' => $restaurant->id,
            'poster'        => $request->file('poster') == null ? null : UploadImage($request->file('poster') , 'poster' , '/uploads/posters'),
            'name_ar'       => $request->name_ar,
            'name_en'       => $request->name_en,
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('posters.index');
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
        $poster = RestaurantPoster::findOrFail($id);
        return view('restaurant.posters.edit' , compact('poster'));
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
        $poster = RestaurantPoster::findOrFail($id);
        $this->validate($request , [
            'poster'   => 'nullable|mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000',
            'name_ar'  => 'nullable|string|max:191',
            'name_en'  => 'nullable|string|max:191',
        ]);
        $poster->update([
            'poster'        => $request->file('poster') == null ? $poster->poster : UploadImageEdit($request->file('poster') , 'poster' , '/uploads/posters' , $poster->poster),
            'name_ar'       => $request->name_ar,
            'name_en'       => $request->name_en,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('posters.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $poster = RestaurantPoster::findOrFail($id);
        if ($poster->poster != null)
        {
            @unlink(public_path('/uploads/posters/' . $poster->poster));
        }
        $poster->delete();
        flash(trans('messages.updated'))->success();
        return redirect()->route('posters.index');
    }
}

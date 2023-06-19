<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantSocial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 6) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $socials = RestaurantSocial::whereRestaurantId($restaurant->id)->paginate(100);
        return view('restaurant.socials.index' , compact('socials'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        return view('restaurant.socials.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
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
        // create new social
        RestaurantSocial::create([
            'restaurant_id' => $restaurant->id,
            'name_ar'       => $request->name_ar,
            'name_en'       => $request->name_en,
            'icon'          => $request->file('icon') == null ? null : UploadImage($request->file('icon') , 'icon' , '/uploads/socials'),
            'link'          => $request->link,
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('socials.index');
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
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $social = RestaurantSocial::findOrFail($id);
        return view('restaurant.socials.edit' , compact('social'));
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
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $social = RestaurantSocial::findOrFail($id);
        $this->validate($request , [
            'name_ar'  => 'nullable|string|max:191',
            'name_en'  => 'nullable|string|max:191',
            'icon'     => 'nullable|mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000',
            'link'     => 'required|max:191',
        ]);
        $social->update([
            'name_ar'       => $request->name_ar,
            'name_en'       => $request->name_en,
            'icon'          => $request->file('icon') == null ? $social->icon : UploadImageEdit($request->file('icon') , 'icon' , '/uploads/socials' , $social->icon),
            'link'          => $request->link,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('socials.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $social = RestaurantSocial::findOrFail($id);
        if ($social->icon != null)
        {
            @unlink(public_path('/uploads/socials/' . $social->icon));
        }
        $social->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('socials.index');
    }
}

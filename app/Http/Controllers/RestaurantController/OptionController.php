<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Modifier;
use App\Models\Option;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OptionController extends Controller
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
        $this->middleware('auth:restaurant');
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 4) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        if ($restaurant->status == 'finished' or $restaurant->subscription->status == 'tentative_finished')
        {
            return redirect()->route('RestaurantProfile');
        }
        $options = Option::whereRestaurantId($restaurant->id)->paginate(500);
        return view('restaurant.options.index' , compact('options'));
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
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 4) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $modifiers = Modifier::whereRestaurantId($restaurant->id)->get();
        return view('restaurant.options.create' , compact('modifiers'));
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
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 4) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $this->validate($request , [
            'name_ar'      => 'nullable|string|max:191',
            'name_en'      => 'nullable|string|max:191',
            'modifier_id'  => 'required|exists:modifiers,id',
            'is_active'    => 'required|in:true,false',
            'price'        => 'required|numeric',
            'calories'     => 'nullable|numeric',
        ]);
        if ($request->name_ar == null && $request->name_en == null)
        {
            flash(trans('messages.name_required'))->error();
            return redirect()->back();
        }
        // create new option
        Option::create([
            'name_ar'       => $request->name_ar,
            'name_en'       => $request->name_en,
            'modifier_id'   => $request->modifier_id,
            'restaurant_id' => $restaurant->id,
            'is_active'     => $request->is_active,
            'price'         => $request->price,
            'calories'      => $request->calories,
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('additions.index');
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
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 4) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $option = Option::findOrFail($id);
        $modifiers = Modifier::whereRestaurantId($restaurant->id)->get();
        return view('restaurant.options.edit' , compact('option' , 'modifiers'));
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
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 4) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $option = Option::findOrFail($id);
        $this->validate($request , [
            'name_ar'      => 'nullable|string|max:191',
            'name_en'      => 'nullable|string|max:191',
            'modifier_id'  => 'required|exists:modifiers,id',
            'is_active'    => 'required|in:true,false',
            'price'        => 'required|numeric',
            'calories'     => 'nullable|numeric',
        ]);
        if ($request->name_ar == null && $request->name_en == null)
        {
            flash(trans('messages.name_required'))->error();
            return redirect()->back();
        }
        $option->update([
            'name_ar'       => $request->name_ar,
            'name_en'       => $request->name_en,
            'modifier_id'   => $request->modifier_id,
            'restaurant_id' => $restaurant->id,
            'is_active'     => $request->is_active,
            'price'         => $request->price,
            'calories'      => $request->calories,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('additions.index');
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
        $option = Option::findOrFail($id);
        $option->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('additions.index');
    }

    public function active($id , $active)
    {
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $option = Option::findOrFail($id);
        $option->update([
            'is_active'  => $active
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('additions.index');
    }
}

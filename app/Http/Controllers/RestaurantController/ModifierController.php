<?php

namespace App\Http\Controllers\RestaurantController;

use App\Models\Restaurant;
use Twilio;
use App\Http\Controllers\Controller;
use App\Models\Modifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModifierController extends Controller
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
        $modifiers = Modifier::whereRestaurantId($restaurant->id)->paginate(500);
        return view('restaurant.modifiers.index' , compact('modifiers'));
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
        return view('restaurant.modifiers.create');
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
            'name_ar'   => 'nullable|string|max:191',
            'name_en'   => 'nullable|string|max:191',
            'is_ready'  => 'required|in:true,false',
            'choose'    => 'required|in:one,multiple',
        ]);
        if ($request->name_ar == null && $request->name_en == null)
        {
            flash(trans('messages.name_required'))->error();
            return redirect()->back();
        }
        // create new modifier
        Modifier::create([
            'restaurant_id' => $restaurant->id,
            'name_ar'   => $request->name_ar,
            'name_en'   => $request->name_en,
            'is_ready'  => $request->is_ready,
            'choose'    => $request->choose,
        ]);
//        Twilio::message('00201119399781', 'hello from Api');
        flash(trans('messages.created'))->success();
        return redirect()->route('modifiers.index');
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
        $modifier = Modifier::findOrFail($id);
        return view('restaurant.modifiers.edit' , compact('modifier'));
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
        $modifier = Modifier::findOrFail($id);
        $this->validate($request , [
            'name_ar'   => 'nullable|string|max:191',
            'name_en'   => 'nullable|string|max:191',
            'is_ready'  => 'required|in:true,false',
            'choose'    => 'nullable|in:one,multiple',
        ]);
        if ($request->name_ar == null && $request->name_en == null)
        {
            flash(trans('messages.name_required'))->error();
            return redirect()->back();
        }
        $modifier->update([
            'name_ar'   => $request->name_ar,
            'name_en'   => $request->name_en,
            'is_ready'  => $request->is_ready,
            'choose'    => $request->choose,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('modifiers.index');
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
        $modifier = Modifier::findOrFail($id);
        $modifier->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('modifiers.index');
    }

    public function active($id , $active)
    {
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $modifier = Modifier::findOrFail($id);
        $modifier->update([
            'is_ready' => $active
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('modifiers.index');
    }
}

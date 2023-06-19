<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResBranchesController extends Controller
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
        $branches = RestaurantBranch::whereRestaurantId($restaurant->id)
            ->orderBy('id' , 'desc')
            ->paginate(500);
        return view('restaurant.res_branches.index' , compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('restaurant.res_branches.create');
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
            'name_ar' => 'nullable|string|max:191',
            'name_en' => 'nullable|string|max:191',
            'link'    => 'required',
        ]);
        RestaurantBranch::create([
            'restaurant_id' => $restaurant->id,
            'name_ar'  => $request->name_ar,
            'name_en'  => $request->name_en,
            'link'     => $request->link,
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('res_branches.index');
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
        $branch = RestaurantBranch::findOrFail($id);
        return view('restaurant.res_branches.edit' , compact('branch'));
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
        $branch = RestaurantBranch::findOrFail($id);
        $this->validate($request , [
            'name_ar' => 'nullable|string|max:191',
            'name_en' => 'nullable|string|max:191',
            'link'    => 'required',
        ]);
        $branch->update([
            'name_ar'  => $request->name_ar,
            'name_en'  => $request->name_en,
            'link'     => $request->link,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('res_branches.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $branch = RestaurantBranch::findOrFail($id);
        $branch->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('res_branches.index');
    }
}

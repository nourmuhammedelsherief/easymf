<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Count;

class ThemeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $country = Country::findOrFail($id);
        $cities = City::orderBy('id' , 'desc')
            ->where('country_id' , $id)
            ->get();
        return view('admin.cities.index' , compact('cities' , 'country'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $country = Country::findOrFail($id);
        return view('admin.cities.create' , compact('country'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request , $id)
    {
        $country = Country::findOrFail($id);
        $this->validate($request , [
//            'country_id' => 'required|exists:countries,id',
            'name_ar'    => 'required|string|max:255',
            'name_en'    => 'required|string|max:255',
        ]);
        // create new city
        City::create([
            'country_id' => $country->id,
            'name_ar'    => $request->name_ar,
            'name_en'    => $request->name_en,
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('cities.index' , $country->id);
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
        $city = City::findOrFail($id);
        return view('admin.cities.edit' , compact( 'city'));
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
        $this->validate($request , [
            'name_ar'    => 'required|string|max:255',
            'name_en'    => 'required|string|max:255',
        ]);
        $city = City::findOrFail($id);
        $city->update([
            'name_ar'    => $request->name_ar,
            'name_en'    => $request->name_en,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('cities.index' , $city->country->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $city = City::findOrFail($id);
        if ($city->restaurants->count() > 0)
        {
            flash(trans('messages.cant_deleted'))->error();
            return redirect()->route('cities.index' , $city->country->id);
        }else{
            $city->delete();
            flash(trans('messages.deleted'))->success();
            return redirect()->route('cities.index' , $city->country->id);
        }
    }
}

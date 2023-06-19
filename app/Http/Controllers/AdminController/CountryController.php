<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\MenuCategory;
use App\Models\RestaurantOrderPeriodDay;
use App\Models\RestaurantSubCategory;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Count;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::orderBy('id' , 'desc')->get();
        return view('admin.countries.index' , compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.countries.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request , [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'currency_ar' => 'required|string|max:255',
            'currency_en' => 'required|string|max:255',
            'code'  => 'required|max:5',
            'flag'  => 'required|mimes:jpg,jpeg,png,gif,tif,psd,pmp|max:5000'
        ]);
        // create new country
        Country::create([
            'name_ar'   => $request->name_ar,
            'name_en'   => $request->name_en,
            'currency_ar' => $request->currency_ar,
            'currency_en' => $request->currency_en,
            'code' => $request->code,
            'flag' => $request->file('flag') == null ? null : UploadImage($request->file('flag') , 'flag' , '/uploads/flags')
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('countries.index');
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
        $country = Country::findOrFail($id);
        return view('admin.countries.edit' , compact('country'));
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
        $country = Country::findOrFail($id);
        $this->validate($request , [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'currency_ar' => 'required|string|max:255',
            'currency_en' => 'required|string|max:255',
            'code'  => 'required|max:5',
            'flag'  => 'sometimes|mimes:jpg,jpeg,png,gif,tif,psd,pmp|max:5000'
        ]);
        $country->update([
            'name_ar'   => $request->name_ar,
            'name_en'   => $request->name_en,
            'currency_ar' => $request->currency_ar,
            'currency_en' => $request->currency_en,
            'code' => $request->code,
            'flag' => $request->file('flag') == null ? $country->flag : UploadImageEdit($request->file('flag') , 'flag' , '/uploads/flags' , $country->flag)
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('countries.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $country = Country::findOrFail($id);
        if ($country->restaurants->count() > 0 || $country->cities->count() > 0)
        {
            flash(trans('messages.cant_deleted'))->error();
            return redirect()->route('countries.index');
        }else{
            @unlink(public_path('/uploads/flags/' . $country->flag));
            $country->delete();
            flash(trans('messages.deleted'))->success();
            return redirect()->route('countries.index');
        }
    }

    public function get_cities($id)
    {
        $cities = City::whereCountryId($id)->get();
        return response()->json($cities);
    }
    public function sub_categories($id)
    {
        $sub_categories = RestaurantSubCategory::where('menu_category_id',$id)->get();
        return response()->json($sub_categories);
    }
    public function get_days($id)
    {
        $days = RestaurantOrderPeriodDay::wherePeriodId($id)
            ->with('day')
            ->get();
        return response()->json($days);
    }
    public function categories($id)
    {
        $categories = MenuCategory::where('branch_id',$id)->get();
        return response()->json($categories);
    }
    public function active($id , $active)
    {
        $country = Country::findOrFail($id);
        $country->update([
            'active'  => $active
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('countries.index');
    }
    public function country_restaurants($id)
    {
        $country = Country::findOrFail($id);
        $restaurants = $country->restaurants;
        return view('admin.countries.restaurants' , compact('country' , 'restaurants'));
    }
}

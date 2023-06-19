<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Service;
use App\Models\ServiceCountry;
use Illuminate\Http\Request;

class ServicePriceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        
        $service = Service::find($id);
        $serviceCountries = ServiceCountry::whereServiceId($id)->get();
        return view('admin.services.countries.index' , compact('service' , 'serviceCountries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $service = Service::find($id);
        $countriesId = ServiceCountry::where('service_id' , $id)->get()->pluck('country_id')->toArray();
        $countries = Country::whereNotIn('id' , $countriesId)->get();
        return view('admin.services.countries.create' , compact('service' , 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request , $id)
    {
        $service = Service::find($id);
        $this->validate($request , [
            'country_id'   => 'required|exists:countries,id',
            'price'        => 'required|numeric|min:0',
            
        ]);
        $check = ServiceCountry::whereServiceId($service->id)
            ->whereCountryId($request->country_id)
            ->first();
        if ($check != null)
        {
            flash(trans('messages.created_before'))->error();
            return redirect()->route('admin.service.country.index' , $service->id);
        }
        // create new country package
        ServiceCountry::create([
            'service_id'   => $service->id,
            'country_id'   => $request->country_id,
            'price'        => $request->price,
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('admin.service.country.index' , $service->id);
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
    public function edit(Service $service , $country)
    {
        $serviceCountry = ServiceCountry::findOrFail($country);
        $service = $serviceCountry->service;
        $countriesId = ServiceCountry::where('service_id' , $service->id)->where('id' , '!=' , $serviceCountry->id)->get()->pluck('country_id')->toArray();
        $countries = Country::whereNotIn('id' , $countriesId)->get();
        return view('admin.services.countries.edit' , compact('serviceCountry','service' , 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Service $service ,$country)
    {
        $serviceCountry = ServiceCountry::findOrFail($country);
        $this->validate($request , [
            'country_id' => 'required|exists:countries,id',
            'price'      => 'required|numeric',
            
        ]);
        $serviceCountry->update([
            'country_id'  => $request->country_id,
            'price'       => $request->price,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('admin.service.country.index' , $serviceCountry->service->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service , $id)
    {
        $country_package = ServiceCountry::where('service_id' , $service->id)->findOrFail($id);
        $country_package->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('admin.service.country.index' , $country_package->service->id);
    }
}

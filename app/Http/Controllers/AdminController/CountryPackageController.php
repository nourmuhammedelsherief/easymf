<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\CountryPackage;
use App\Models\Package;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Count;

class CountryPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $country = Country::find($id);
        $country_packages = CountryPackage::whereCountryId($id)->get();
        return view('admin.countries.packages.index' , compact('country' , 'country_packages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $country = Country::find($id);
        $packages = Package::all();
        return view('admin.countries.packages.create' , compact('country' , 'packages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request , $id)
    {
        $country = Country::find($id);
        $this->validate($request , [
            'package_id'   => 'required|exists:packages,id',
            'price'        => 'required|numeric',
            'branch_price' => 'required|numeric',
        ]);
        $check = CountryPackage::whereCountryId($country->id)
            ->wherePackageId($request->package_id)
            ->first();
        if ($check != null)
        {
            flash(trans('messages.created_before'))->error();
            return redirect()->route('country_packages.index' , $country->id);
        }
        // create new country package
        CountryPackage::create([
            'country_id'   => $country->id,
            'package_id'   => $request->package_id,
            'price'        => $request->price,
            'branch_price' => $request->branch_price,
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('country_packages.index' , $country->id);
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
        $country_package = CountryPackage::findOrFail($id);
        $country = $country_package->country;
        $packages = Package::all();
        return view('admin.countries.packages.edit' , compact('country_package','country' , 'packages'));
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
        $country_package = CountryPackage::findOrFail($id);
        $this->validate($request , [
            'package_id' => 'required|exists:packages,id',
            'price'      => 'required|numeric',
            'branch_price' => 'required|numeric',
        ]);
        $country_package->update([
            'package_id'  => $request->package_id,
            'price'       => $request->price,
            'branch_price'  => $request->branch_price,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('country_packages.index' , $country_package->country->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $country_package = CountryPackage::findOrFail($id);
        $country_package->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('country_packages.index' , $country_package->country->id);
    }
}

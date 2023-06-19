<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryService;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Count;

class CategoryServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $categories = CategoryService::orderBy('id' , 'desc')
            
            ->get();
        return view('admin.service_categories.index' , compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('admin.service_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request )
    {
        
        $data = $this->validate($request , [
//            'country_id' => 'required|exists:countries,id',
            'name_ar'    => 'required|string|max:255',
            'name_en'    => 'required|string|max:255',
        ]);
        // create new city
        CategoryService::create($data);
        flash(trans('messages.created'))->success();
        return redirect()->route('admin.service_category.index' );
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
        $category = CategoryService::findOrFail($id);
        return view('admin.service_categories.edit' , compact( 'category'));
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
        $category = CategoryService::findOrFail($id);
        $category->update([
            'name_ar'    => $request->name_ar,
            'name_en'    => $request->name_en,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('admin.service_category.index' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $city = CategoryService::findOrFail($id);
       
            $city->delete();
            flash(trans('messages.deleted'))->success();
            return redirect()->route('admin.service_category.index');
        
    }
}

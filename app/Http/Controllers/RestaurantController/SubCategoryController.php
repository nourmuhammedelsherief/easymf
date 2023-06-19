<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\RestaurantCategory;
use App\Models\RestaurantSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $category = MenuCategory::findOrFail($id);
        $sub_categories = RestaurantSubCategory::where('menu_category_id' , $id)
            ->orderBy('id' , 'desc')
            ->paginate(100);
        return view('restaurant.sub_categories.index' , compact('sub_categories' , 'category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $category = MenuCategory::findOrFail($id);
        return view('restaurant.sub_categories.create' , compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request , $id)
    {
        $category = MenuCategory::findOrFail($id);
        $this->validate($request , [
            'name_ar'  => 'nullable|string|max:191',
            'name_en'  => 'nullable|string|max:191'
        ]);
        RestaurantSubCategory::create([
            'menu_category_id' => $category->id,
            'name_ar'  => $request->name_ar,
            'name_en'  => $request->name_en
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('sub_categories.index' , $category->id);
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
        $sub_category = RestaurantSubCategory::findOrFail($id);
        return view('restaurant.sub_categories.edit' , compact('sub_category'));
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
        $sub_category = RestaurantSubCategory::findOrFail($id);
        $this->validate($request , [
            'name_ar'  => 'nullable|string|max:191',
            'name_en'  => 'nullable|string|max:191'
        ]);
        $sub_category->update([
            'name_ar'  => $request->name_ar,
            'name_en'  => $request->name_en
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('sub_categories.index' , $sub_category->menu_category_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sub_category = RestaurantSubCategory::findOrFail($id);
        $sub_category->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('sub_categories.index', $sub_category->menu_category_id);
    }
}

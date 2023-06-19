<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::withCount('restaurant_categories')->orderBy('id' , 'desc')->get();
        
        return  view('admin.categories.index' , compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return  view('admin.categories.create');
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
        ]);

        // create new category
        Category::create([
            'name_ar'  => $request->name_ar,
            'name_en'  => $request->name_en,
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('categories.index');
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
        $category = Category::findOrFail($id);
        return  view('admin.categories.edit' , compact('category'));
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
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ]);
        $category = Category::findOrFail($id);
        $category->update([
            'name_ar'  => $request->name_ar,
            'name_en'  => $request->name_en,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if ($category->restaurant_categories->count() > 0)
        {
            flash(trans('messages.cant_deleted'))->error();
            return redirect()->route('categories.index');
        }
        $category->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('categories.index');
    }
}

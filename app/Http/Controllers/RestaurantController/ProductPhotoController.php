<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Http\Request;

class ProductPhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $product = Product::findOrFail($id);
        $photos = ProductPhoto::whereProductId($product->id)->orderBy('id' , 'desc')->paginate(50);
        return view('restaurant.products.photos.index' , compact('product' , 'photos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $product = Product::findOrFail($id);
        return view('restaurant.products.photos.create' , compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request , $id)
    {
        $product = Product::findOrFail($id);
        $this->validate($request , [
            'photo' => 'required|mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000',
        ]);
        // create new product photo
        ProductPhoto::create([
            'product_id' => $product->id,
            'photo' => UploadImage($request->file('photo') , 'photo' , '/uploads/products')
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('productPhoto' , $product->id);
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
        $photo = ProductPhoto::findOrFail($id);
        $product = $photo->product;
        return view('restaurant.products.photos.edit' , compact('product' , 'photo'));
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
        $photo = ProductPhoto::findOrFail($id);
        $this->validate($request , [
            'photo' => 'nullable|mimes:jpg,jpeg,png,gif,tif,psd,pmp,webp|max:5000',
        ]);
        $photo->update([
            'photo' => $request->file('photo') == null ? $photo->photo : UploadImageEdit($request->file('photo') , 'photo' , '/uploads/products' , $photo->photo)
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('productPhoto' , $photo->product->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $photo = ProductPhoto::findOrFail($id);
        if ($photo->photo != null)
        {
            @unlink(public_path('/uploads/photos/' . $photo->photo));
        }
        $photo->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('productPhoto' , $photo->product->id);
    }
}

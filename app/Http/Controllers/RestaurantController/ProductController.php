<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\MenuCategory;
use App\Models\Product;
use App\Models\ProductDay;
use App\Models\ProductSensitivity;
use App\Models\Restaurant;
use App\Models\RestaurantPoster;
use App\Models\RestaurantSensitivity;
use App\Models\RestaurantSubCategory;
use App\Models\ServiceSubscription;
use App\Models\SilverOrder;
use App\Models\TemporaryFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return MenuCategory::isShow()->where('menu_categories.id' , 2237)->get();
        if(!auth('restaurant')->check()){

            return redirect('restaurant/login');
        }
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
        $products = Product::whereRestaurantId($restaurant->id)->paginate(500);
        $branches = Branch::whereRestaurantId($restaurant->id)
            ->whereIn('status' , ['active' , 'tentative'])
            ->get();
        $this->deleteTemporaryFiles();
        return view('restaurant.products.index' , compact('products' , 'branches'));
    }
    public function branch_products($id)
    {
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 4) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $products = Product::whereRestaurantId($restaurant->id)
            ->where('branch_id' , $id)
            ->paginate(500);
        $branches = Branch::whereRestaurantId($restaurant->id)
            ->whereIn('status' , ['active' , 'tentative'])
            ->get();
        return view('restaurant.products.index' , compact('products' , 'branches'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 4) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $restaurant = Restaurant::whereId($restaurant->id)->firstOrFail();
        $branches = Branch::whereRestaurantId($restaurant->id)
            ->whereIn('status' , ['active' , 'tentative'])
            ->where('foodics_status' , 'false')
            ->get();
//        $categories = MenuCategory::whereRestaurantId(Auth::guard('restaurant')->user()->id)->get();
        $posters = RestaurantPoster::whereRestaurantId($restaurant->id)->get();
        $sensitivities = RestaurantSensitivity::whereRestaurantId($restaurant->id)->get();
        $branchesSubscription = ServiceSubscription::whereRestaurantId(auth('restaurant')->id())->whereHas('service' , function($query){
            $query->where('id' , 11);
        })
            ->whereIn('status' , ['active' , 'tentative'])->get()->pluck('branch_id')->toArray();
        
        return view('restaurant.products.create' , compact('branches'  ,'sensitivities' , 'restaurant' , 'posters' ,'branchesSubscription'));
    }
    protected function deleteTemporaryFiles(){
        $currentDate = Carbon::now()->subDay();
        $items = TemporaryFile::where('created_at' , '<=' , $currentDate->format('Y-m-d H:i:s'))->get();
        foreach($items as $item):
            if(Storage::disk('public_storage')->exists($item->path)):
                Storage::disk('public_storage')->delete($item->path);
            endif;
            $item->delete();
        endforeach;
        return $items;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 4) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        if($request->video_type == 'local_video' and (empty($request->video_path) or !$tempVideo = TemporaryFile::where('path' , $request->video_path)->first())){
            flash('يرجي ارفاق الفيديو اولا !!')->error();
            return redirect()->back();
        }elseif($request->video_type == 'gif' and (empty($request->gif_path) or !$tempVideo = TemporaryFile::where('path' , $request->gif_path)->first())){
            flash('يرجي ارفاق صورة اولا !!')->error();
            return redirect()->back();
        }
        $this->validate($request , [
            'branch_id'         => 'required|exists:branches,id',
            'menu_category_id'  => 'required',
            'menu_category_id*' => 'exists:menu_categories,id',
            'poster_id'         => 'nullable|exists:restaurant_posters,id',
            'sub_category_id'   => 'nullable|exists:restaurant_sub_categories,id',
            'name_ar'           => 'nullable|string|max:191',
            'name_en'           => 'nullable|string|max:191',
            'description_ar'    => 'nullable|string',
            'description_en'    => 'nullable|string',
            'price'             => 'required|numeric',
            'price_before_discount' => 'nullable|numeric',
            'calories'          => 'nullable|numeric',
            'image_name' => 'nullable|min:1|max:190' ,
            // 'photo'             => 'nullable|mimes:jpg,jpeg,png,gig,tif,psd,pmp,webp|max:10000',
            'loyalty_points' => 'nullable|integer|min:1' , 
            'start_at' => 'sometimes',
            'end_at' => 'sometimes',
            'time' => 'sometimes|in:true,false',
            'sensitivity_id' => 'nullable',
            'video_type' => 'nullable|in:local_video,youtube,gif'  , 
        ]);
        if ($request->name_ar == null && $request->name_en == null)
        {
            flash(trans('messages.name_required'))->error();
            return redirect()->back();
        }
        if ($request->menu_category_id != null)
        {
            foreach ($request->menu_category_id as $menu_category_id)
            {
                if($request->video_type == 'gif'):
                    
                    $image = $request->gif_path;
                endif;
                // return $image;
                // create new product
                $product = Product::create([
                    'restaurant_id'     => $restaurant->id,
                    'branch_id'         => $request->branch_id,
                    'menu_category_id'  => $menu_category_id,
                    'name_ar'           => $request->name_ar,
                    'name_en'           => $request->name_en,
                    'description_ar'    => $request->description_ar,
                    'description_en'    => $request->description_en,
                    'price'             => $request->price,
                    'price_before_discount' => $request->price_before_discount,
                    'poster_id'         => $request->poster_id,
                    'sub_category_id'   => $request->sub_category_id,
                    'calories'          => $request->calories,
                    'active'            => 'true',
                    'start_at'          => $request->start_at,
                    'end_at'            => $request->end_at,
                    'loyalty_points' => $request->loyalty_points , 
                    'time'              => $request->time == null ? 'false' : $request->time,
                    'photo'             => $request->video_type == 'gif' ? $image : $request->image_name ,
                    'video_type' => $request->video_type , 
                    'video_id' => $request->video_type == 'local_video' ? $request->video_path : $request->video_id , 
                    // 'photo'             => $request->file('photo') == null ? 'default.png' : UploadImage($request->file('photo'),'photo' , '/uploads/products')
                ]);
                if(isset($tempVideo->id)){
                    $tempVideo->delete();
                }
                if ($request->time == 'true' && $request->day_id != null)
                {
                    ProductDay::where('product_id' , $product->id)->delete();
                    foreach ($request->day_id as $day)
                    {
                        ProductDay::create([
                            'product_id' => $product->id,
                            'day_id'           => $day,
                        ]);
                    }
                }
                // check if the restaurant store sensitivity
                if ($request->sensitivity_id != null)
                {
                    foreach ($request->sensitivity_id as $sen_id)
                    {
                        // create product sensitivities
                        ProductSensitivity::create([
                            'product_id'     => $product->id,
                            'sensitivity_id' => $sen_id,
                        ]);
                    }
                }
            }
        }
        
        flash(trans('messages.created'))->success();
        return redirect()->route('products.index');
//        return response()->json(['url' => route('products.index')]);

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
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 4) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $product = Product::findOrFail($id);
        if ($product->branch->foodics_status == 'true')
        {
            $branches = Branch::whereRestaurantId($restaurant->id)
                ->whereIn('status' , ['active' , 'tentative'])
                ->get();
        }else{
            $branches = Branch::whereRestaurantId($restaurant->id)
                ->whereIn('status' , ['active' , 'tentative'])
                ->where('foodics_status' , 'false')
                ->get();
        }
        $posters = RestaurantPoster::whereRestaurantId($restaurant->id)->get();
        $sensitivities = RestaurantSensitivity::whereRestaurantId($restaurant->id)->get();
//        $categories = MenuCategory::whereRestaurantId(Auth::guard('restaurant')->user()->id)->get();
        $branchesSubscription = ServiceSubscription::whereRestaurantId(auth('restaurant')->id())->whereHas('service' , function($query){
            $query->where('id' , 11);
        })
            ->whereIn('status' , ['active' , 'tentative'])->get()->pluck('branch_id')->toArray();
        return view('restaurant.products.edit' , compact('branches' , 'sensitivities','product','posters' , 'branchesSubscription'));
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
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 4) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $product = Product::findOrFail($id);
        // return $request->all();
        if($request->video_type == 'local_video' and empty($product->video_id)){
            flash('يرجي ارفاق الفيديو اولا !!')->error();
            return redirect()->back();
        }
        if ($product->branch->foodics_status == 'true')
        {
            $this->validate($request , [
                'poster_id'         => 'nullable|integer|exists:restaurant_posters,id',
                'sub_category_id'   => 'nullable|exists:restaurant_sub_categories,id',
                'description_ar'    => 'nullable|string',
                'description_en'    => 'nullable|string',
                'price_before_discount' => 'nullable|numeric',
                'calories'          => 'nullable|numeric',
//            'active'            => 'required|in:true,false',
                'photo'             => 'nullable|mimes:jpg,jpeg,png,gig,gif,tif,psd,pmp,webp|max:20000',
                'start_at' => 'sometimes',
                'end_at' => 'sometimes',
                'time' => 'sometimes|in:true,false',
                'sensitivity_id' => 'nullable',
                'loyalty_points' => 'nullable|integer|min:1' , 
                'video_id' => 'nullable|min:1|max:190' ,
                'video_type' => 'nullable|in:local_video,youtube,gif'
            ]);
        }else{
            $this->validate($request , [
                'branch_id'         => 'required|exists:branches,id',
                'menu_category_id'  => 'required|exists:menu_categories,id',
                'poster_id'         => 'nullable|exists:restaurant_posters,id',
                'sub_category_id'   => 'nullable|exists:restaurant_sub_categories,id',
                'name_ar'           => 'nullable|string|max:191',
                'name_en'           => 'nullable|string|max:191',
                'description_ar'    => 'nullable|string',
                'description_en'    => 'nullable|string',
                'loyalty_points' => 'nullable|integer|min:1' , 
                'price'             => 'required|numeric',
                'price_before_discount' => 'nullable|numeric',
                'calories'          => 'nullable|numeric',
//            'active'            => 'required|in:true,false',
                'photo'             => 'nullable|mimes:jpg,jpeg,png,gig,gif,tif,psd,pmp,webp|max:20000',
                'start_at' => 'sometimes',
                'end_at' => 'sometimes',
                'time' => 'sometimes|in:true,false',
                'sensitivity_id' => 'nullable',
                'video_id' => 'nullable|min:1|max:190' ,
                'video_type' => 'nullable|in:local_video,youtube,gif'
            ]);
            if ($request->name_ar == null && $request->name_en == null)
            {
                flash(trans('messages.name_required'))->error();
                return redirect()->back();
            }
        }
//        if ($request->description_ar == null && $request->description_en == null)
//        {
//            flash(trans('messages.description_required'))->error();
//            return redirect()->back();
//        }
        // return $request->all();
        if($request->hasFile('video')):
            $videoPath = Storage::disk('public_storage')->put('uploads/products' , $request->video);
        endif;

        if($request->video_type == 'gif'):
            if ($request->photo != null)
            {
                $photo = $request->file('photo') == null ? $product->photo : UploadImageEdit($request->file('photo'),'photo' , '/uploads/products' , $product->photo);
            }else{
                $photo = $product->photo;
            }
        else:
            if ($request->photo != null)
            {
                $photo = $request->file('photo') == null ? $product->photo : UploadImageEdit($request->file('photo'),'photo' , '/uploads/products' , $product->photo);
            }else{
                $photo = $product->photo;
            }

        endif;
        //    return $request->all();
        if ($product->branch->foodics_status == 'true')
        {
            $product->update([
                'poster_id'         => $request->poster_id,
                'sub_category_id'   => $request->sub_category_id,
                'description_ar'    => $request->description_ar,
                'description_en'    => $request->description_en,
                'price_before_discount' => $request->price_before_discount,
                'calories'          => $request->calories,
//            'active'            => 'true',
                'photo'             => $photo,
                'loyalty_points' => $request->loyalty_points , 
                'start_at' => $request->start_at == null ? $product->start_at : $request->start_at,
                'end_at' => $request->end_at == null ? $product->end_at : $request->end_at,
                'time' => $request->time == null ? $product->time : $request->time,
                'video_id' => isset($videoPath) ? $videoPath : $request->video_id ,
                'video_type' => $request->video_type ,
            ]);
        }else{
            $product->update([
                'restaurant_id'     => $restaurant->id,
                'branch_id'         => $request->branch_id,
                'menu_category_id'  => $request->menu_category_id,
                'poster_id'         => $request->poster_id,
                'sub_category_id'   => $request->sub_category_id,
                'name_ar'           => $request->name_ar,
                'loyalty_points' => $request->loyalty_points , 
                'name_en'           => $request->name_en,
                'description_ar'    => $request->description_ar,
                'description_en'    => $request->description_en,
                'price'             => $request->price,
                'price_before_discount' => $request->price_before_discount,
                'calories'          => $request->calories,
//            'active'            => 'true',
                'photo'             => $photo,
                'start_at' => $request->start_at == null ? $product->start_at : $request->start_at,
                'end_at' => $request->end_at == null ? $product->end_at : $request->end_at,
                'time' => $request->time == null ? $product->time : $request->time,
                'video_id' => isset($videoPath) ? $videoPath : $request->video_id ,
                'video_type' => $request->video_type ,
            ]);
        }
        if ($request->time == 'true' && $request->day_id != null)
        {
            ProductDay::where('product_id' , $product->id)->delete();
            foreach ($request->day_id as $day)
            {
                ProductDay::create([
                    'product_id' => $product->id,
                    'day_id'           => $day,
                ]);
            }
        }
        // check if the restaurant store sensitivity
        if ($request->sensitivity_id != null)
        {
            foreach ($request->sensitivity_id as $sen_id)
            {
                $check_sen = ProductSensitivity::whereProductId($product->id)
                    ->where('sensitivity_id' , $sen_id)
                    ->first();
                if ($check_sen == null)
                {
                    // create product sensitivities
                    ProductSensitivity::create([
                        'product_id'     => $product->id,
                        'sensitivity_id' => $sen_id,
                    ]);
                }
            }
        }else{
            ProductSensitivity::whereProductId($product->id)->delete();
        }
        flash(trans('messages.updated'))->success();
//        return response()->json(['url' => route('products.index')]);
        return redirect()->route('products.index');
    }

    
    public function uploadVideo(Request $request){
        
        $request->validate([
            'product_id' => 'nullable|integer' , 
            'video' => 'required|mimes:mp4,gif' , 
            'type' => 'required|in:local_video,gif',
        ]);
        if($request->type == 'gif'):
            $request->validate([
                'video' => 'required|mimes:gif' , 
            ]); 
        endif;
        if(!empty($request->product_id) and !$product = Product::find($request->product_id)):
            return trans('dashboard.errors.product_not_found');
        endif;
        
        if(isset($product->id) and $product->video_type == 'local_video' and !empty($product->video_id) and Storage::disk('public_storage')->exists($product->video_id)):
            Storage::disk('public_storage')->delete($product->video_id);
        endif;
        $videoPath = Storage::disk('public_storage')->put('uploads/products' ,$request->file('video'));
        if($request->type == 'gif'){
            if(isset($product->id) and $product->video_type == 'gif' and !empty($product->video_id) and Storage::disk('public_storage')->exists('uploads/products/' . $product->photo)):
                Storage::disk('public_storage')->delete('uploads/products/' . $product->photo);
            endif;
            $videoPath = basename($videoPath);
        } 
        if(isset($product->id) and $request->type == 'local_video'){
            $product->update([
                'video_type' => $request->type , 
                'video_id' => $videoPath,
            ]);
        }elseif(isset($product->id) and $request->type == 'gif'){
            $product->update([
                'video_type' => $request->type , 
                'photo' => $videoPath,
            ]);
        }else{
            $temp = TemporaryFile::create([
                'type' => 'product' , 
                'path' => $videoPath
            ]);
        }
       
        return response([
            'status' => 1 , 
            'video_path' => $videoPath , 
            'temp_id' => isset($temp->id) ? $temp->id : null  , 
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->photo != null)
        {
            @unlink(public_path('/uploads/products/' . $product->photo));
        }
        $product->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('products.index');
    }

    public function active($id , $active)
    {
        $product = Product::findOrFail($id);
        $product->update([
            'active'   => $active,
        ]);
        if ($active == 'false')
        {
            // remove it from cart
            SilverOrder::whereProductId($id)->delete();
        }
        flash(trans('messages.updated'))->success();
        return redirect()->route('products.index');
    }


    public function updateProductImage(Request $request){

        $request->validate([
            'photo' => 'required|mimes:png,jepg,jpg,svg' ,
            'action' => 'required|in:create,edit' ,
            'item_id' => 'required_if:action,edit|integer|exists:products,id' ,
        ]);
        if($request->action == 'edit')
            $product = Product::findOrFail($request->item_id);

        if ($request->photo != null)
        {
            $photo = UploadImageEdit($request->file('photo'),'photo' , '/uploads/products' , (isset($product->photo) ? $product->photo : null));
            if(isset($product->id))
                $product->update([
                    'photo' => $photo ,
                ]);
            return response([
                'photo' =>  $photo,
                'status' => true ,
            ]);
        }
        return response('error' , 500);
    }

    public function arrange($id)
    {
        $product = Product::findOrFail($id);
        return view('restaurant.products.arrange' , compact('product'));
    }
    public function arrange_submit(Request $request , $id)
    {
        $product = Product::findOrFail($id);
        $this->validate($request , [
            'arrange' => 'required'
        ]);
        $product->update([
            'arrange' => $request->arrange
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('products.index');
    }
    public function copy_product($id)
    {
        $product = Product::findOrFail($id);
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 4) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $branches = Branch::whereRestaurantId($restaurant->id)
            ->whereIn('status' , ['active' , 'tentative'])
            ->where('foodics_status' , 'false')
            ->get();
        $posters = RestaurantPoster::whereRestaurantId($restaurant->id)->get();
        $sensitivities = RestaurantSensitivity::whereRestaurantId($restaurant->id)->get();
//        $categories = MenuCategory::whereRestaurantId(Auth::guard('restaurant')->user()->id)->get();
        return view('restaurant.products.copy' , compact('branches' , 'sensitivities','product','posters'));
    }
    public function copy_product_submit(Request $request , $id)
    {
        $old_product = Product::findOrFail($id);
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 4) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $this->validate($request , [
            'branch_id'         => 'required|exists:branches,id',
            'menu_category_id'  => 'required',
            'menu_category_id*' => 'exists:menu_categories,id',
            'poster_id'         => 'nullable|exists:restaurant_posters,id',
            'sub_category_id'   => 'nullable|exists:restaurant_sub_categories,id',
            'name_ar'           => 'nullable|string|max:191',
            'name_en'           => 'nullable|string|max:191',
            'description_ar'    => 'nullable|string',
            'description_en'    => 'nullable|string',
            'price'             => 'required|numeric',
            'price_before_discount' => 'nullable|numeric',
            'calories'          => 'nullable|numeric',
            'image_name' => 'nullable|min:1|max:190' ,
            // 'photo'             => 'nullable|mimes:jpg,jpeg,png,gig,tif,psd,pmp,webp|max:10000',
            'start_at' => 'sometimes',
            'end_at' => 'sometimes',
            'time' => 'sometimes|in:true,false',
            'sensitivity_id' => 'nullable',
        ]);
        if ($request->name_ar == null && $request->name_en == null)
        {
            flash(trans('messages.name_required'))->error();
            return redirect()->back();
        }
        if ($request->menu_category_id != null)
        {
            foreach ($request->menu_category_id as $menu_category_id)
            {
                // create new product
                $product = Product::create([
                    'restaurant_id'     => $restaurant->id,
                    'branch_id'         => $request->branch_id,
                    'menu_category_id'  => $menu_category_id,
                    'name_ar'           => $request->name_ar,
                    'name_en'           => $request->name_en,
                    'description_ar'    => $request->description_ar,
                    'description_en'    => $request->description_en,
                    'price'             => $request->price,
                    'price_before_discount' => $request->price_before_discount,
                    'poster_id'         => $request->poster_id,
                    'sub_category_id'   => $request->sub_category_id,
                    'calories'          => $request->calories,
                    'active'            => 'true',
                    'start_at'          => $request->start_at,
                    'end_at'            => $request->end_at,
                    'time'              => $request->time == null ? 'false' : $request->time,
                    'photo'             => $request->image_name ,
                    // 'photo'             => $request->file('photo') == null ? 'default.png' : UploadImage($request->file('photo'),'photo' , '/uploads/products')
                ]);
                if ($request->time == 'true' && $request->day_id != null)
                {
                    ProductDay::where('product_id' , $product->id)->delete();
                    foreach ($request->day_id as $day)
                    {
                        ProductDay::create([
                            'product_id' => $product->id,
                            'day_id'           => $day,
                        ]);
                    }
                }
                // check if the restaurant store sensitivity
                if ($request->sensitivity_id != null)
                {
                    foreach ($request->sensitivity_id as $sen_id)
                    {
                        // create product sensitivities
                        ProductSensitivity::create([
                            'product_id'     => $product->id,
                            'sensitivity_id' => $sen_id,
                        ]);
                    }
                }
            }
        }

        flash(trans('messages.updated'))->success();
        return redirect()->route('products.index');
    }
    public function deleteProductPhoto($id)
    {
        $product = Product::findOrFail($id);
        if ($product->photo != null)
        {
            @unlink(public_path('/uploads/products/' . $product->photo));
        }
        $product->update([
            'photo' => null
        ]);
        flash(trans('messages.deleted'))->success();
        return redirect()->back();
    }

}

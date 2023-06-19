<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\Restaurant;
use App\Models\RestaurantAds;
use App\Models\TemporaryFile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class AdsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:restaurant')->except(['notWatchAgain']);
    }

    public function notWatchAgain(Request $request){

        $request->validate([
            'not_allowed_ads_id' => 'required|exists:restaurant_ads,id'
        ]);
        $keyName = 'not_allowed_ads' ;
        if($request->hasCookie($keyName)){

            $data = json_decode($request->cookie($keyName) , true);

        }else{
            $data = [];
        }
        if($ads = RestaurantAds::findOrFail($request->not_allowed_ads_id) and !in_array($ads->id , $data)){
            $data[] = $ads->id;
        }
        // Cookie::queue(Cookie::forget($keyName));
        $response = new Response('Not ads');
        $response->withCookie(cookie($keyName , json_encode($data) , (3*30*24*60)));
        return $response;
        // return redirect()->back();
    }
    public function mainIndex(Request $request){
        // $ads = RestaurantAds::find(5);
        // dd($ads->whiteList());
        // dd(Cookie::get('not_allowed_ads'));
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 5) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $currentDate = date('Y-m-d');
        $ads = RestaurantAds::where('restaurant_id' , $restaurant->id)
            ->with('menuCategory')
            ->orderBy('created_at' , 'desc')
            ->get();

        return view('restaurant.ads.main_index' , compact('restaurant' , 'ads'));
    }

    // public function menuCategoryIndex(Request $request){
    //     $restaurant = auth('restaurant')->user();
    //     $ads = RestaurantAds::where('restaurant_id' , $restaurant->id)->whereType('menu_category')->orderBy('created_at' , 'desc')->get();
    //     $type = 'menu_category';
    //     return view('restaurant.ads.main_index' , compact('restaurant' , 'ads' , 'type'));
    // }

    public function create(Request $request){

        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 5) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $ids = RestaurantAds::where('restaurant_id' , $restaurant->id)->where('type' , 'menu_category')->get()->pluck('category_id')->toArray();
        $menuCategories = MenuCategory::where('restaurant_id' , $restaurant->id);
        if(count($ids) > 0) $menuCategories = $menuCategories->whereNotIn('id' , $ids);
        $menuCategories = $menuCategories->whereActive(1)->orderBy('arrange')->get();
        return view('restaurant.ads.create' , compact( 'menuCategories'  , 'restaurant'));
    }

    public function store(Request $request){
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 5) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $request->validate([
            'start_date' => 'required|date' ,
            'end_date' => 'required|date|after:start_date',
            'type' => 'required|in:main,menu_category,contact_us' ,
            'content_type' => 'required|in:image,youtube,local_video,gif' ,
            'link' => 'required_if:content_type,youtube|string|nullable' ,
            // 'image' => 'required_if:content_type,image|mimes:png,jpg,jepg,svg|max:5200',
            'video_path' => 'required_if:content_type,local_video|min:1|nullable' , 
            'image_name' => 'required_if:content_type,image|max:190|string|nullable',

        ]);
        if($request->type == 'menu_category'):
            $request->validate([
                'category_id' => 'required_if:type,menu_category|integer'
            ]);
        endif;
        if($request->content_type == 'youtube'){
            $request->validate([
                'link' => 'required_if:content_type,youtube|min:1|max:100|string' ,
            ]);

        }
        if($request->type == 'menu_category' and !$menuCategory = MenuCategory::where('restaurant_id' , $restaurant->id)->where('id' , $request->category_id)->where('active' , 'true')->first()):
            throw ValidationException::withMessages([
                'category_id' => trans('dashboard.errors.menu_category_not_found'),
            ]);
        endif;
        if($request->type == 'main' and $check  = RestaurantAds::where('restaurant_id' , $restaurant->id)->where('type' , 'main')->first()):
            flash(trans('dashboard.errors.ads_check_date') , 'error');
            return redirect()->back();
        elseif($request->type == 'menu_category' and $check = RestaurantAds::where('restaurant_id' , $restaurant->id)->where('category_id' , $menuCategory->id)->first()):
            flash(trans('dashboard.errors.ads_check_date') , 'error');
            return redirect()->back();
        endif;
        $data = $request->only([
            'start_date' , 'end_date' , 'type' , 'content_type'  , 'time' , 'start_at' , 'end_at'
        ]);

        if($request->type == 'menu_category'):
            $data['category_id'] = $menuCategory->id ;
        endif;
        if($request->content_type == 'youtube'):
            $data['content'] = 'https://www.youtube.com/embed/' . $request->link;
        elseif($request->content_type == 'local_video'):
            if(!$temp = TemporaryFile::where('path' , $request->video_path)->first()):
                flash('يرجي ارفاق الفيديو اولا !!')->error();
                return redirect()->back();
            else:
                $data['content'] = $temp->path;
            endif;
        elseif($request->content_type == 'gif'):
            if(!$temp = TemporaryFile::where('path' , $request->video_path)->first()):
                flash('يرجي ارفاق الفيديو اولا !!')->error();
                return redirect()->back();
            else:
                $data['content'] = basename($temp->path);
            endif;
            // $data['content'] = UploadImage($request->file('photo') , 'ads' , 'uploads/restaurants/ads');
        else:
            $image = $request->image_name;
            $data['content'] = $image;
        endif;

        $data['restaurant_id'] = $restaurant->id ;
        $ads = RestaurantAds::create($data);
        if(!empty($request->day_id) and count($request->day_id) > 0):
            foreach($request->day_id as $id):
                $ads->days()->attach($id);
            endforeach;
        endif;
        flash(trans('dashboard.messages.save_successfully') , 'success');
        return redirect(route('restaurant.ads.index' ));
    }

    public function edit(Request $request , RestaurantAds $ad){
        $ads  = $ad;
        $type = $request->type;
        $type = $ads->type;
        $restaurant = auth('restaurant')->user();
        $videoId = '' ;
        $temp = explode('/'  , $ad->content);
        $videoId = end($temp);
        $menuCategories = MenuCategory::where('restaurant_id' , $restaurant->id)->whereActive(1)->orderBy('arrange')->get();
        return view('restaurant.ads.edit' , compact('type' , 'ads' , 'menuCategories'  , 'restaurant' , 'videoId'));
    }

    public function update(Request $request , RestaurantAds $ad){
        $ads = $ad;
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 5) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $request->validate([
            'start_date' => 'required|date' ,
            'end_date' => 'required|date|after:start_date',
            'type' => 'required|in:main,menu_category,contact_us' ,
            'content_type' => 'required|in:image,youtube,local_video,gif' ,
            'link' => 'required_if:content_type,youtube|string|nullable' ,
            'image' => 'nullable|image|mimes:png,jpg,jepg,svg|max:5200',

        ]);
        if($request->type == 'menu_category'):
            $request->validate([
                'category_id' => 'required_if:type,menu_category|integer'
            ]);
        endif;
        if($request->content_type == 'youtube'){
            $request->validate([
                'link' => 'required_if:content_type,youtube|string|min:1' ,
            ]);
        }
        if($request->type == 'menu_category' and !$menuCategory = MenuCategory::where('restaurant_id' , $restaurant->id)->where('id' , $request->category_id)->where('active' , 'true')->first()):
            throw ValidationException::withMessages([
                'category_id' => trans('dashboard.errors.menu_category_not_found'),
            ]);
        endif;

        if($request->type == 'main' and $check  = RestaurantAds::where('restaurant_id' , $restaurant->id)->where('type' , 'main')->where('id' , '!=' , $ads->id)->first()):
            flash(trans('dashboard.errors.ads_check_date') , 'error');
            return redirect()->back();
        elseif($request->type == 'menu_category' and $check = RestaurantAds::where('restaurant_id' , $restaurant->id)->where('id' , '!=' , $ads->id)->where('category_id' , $menuCategory->id)->first()):
            return $check;
            flash(trans('dashboard.errors.ads_check_date') , 'error');
            return redirect()->back();
        endif;
        $data = $request->only([
            'start_date' , 'end_date' , 'type'  , 'time' , 'start_at' , 'end_at'
        ]);
        if($request->type == 'menu_category'):
            $data['category_id'] = $menuCategory->id ;
        endif;
        if($ads->content_type == 'youtube'):
            $data['content'] = 'https://www.youtube.com/embed/' . $request->link;
        elseif($request->content_type == 'local_video'):
            if(empty($ads->content)):
                flash('يرجي ارفاق الفيديو اولا !!')->error();
                return redirect()->back();
            else:
                $data['content'] = $ads->content;
            endif;
        elseif($request->content_type == 'gif'):
            $data['content'] = empty($request->file('photo')) ? $ads->content : UploadImage($request->file('photo') , 'ads' , 'uploads/restaurants/ads');
        elseif($request->hasFile('image')):
            if($ads->content_type == 'image' and Storage::disk('public_storage')->exists($ads->image_path)):
                Storage::disk('public_storage')->delete($ads->image_path);
            endif;
            $image = UploadImageEdit($request->file('image'), 'ads', '/uploads/restaurants/ads', null);
            $data['content'] = $image;
        endif;
        $data['content_type'] = $request->content_type;
        // return $data;
        $oldContent = $ads->content;
        $ads->update($data);
        if($request->time == 'true' and !empty($request->day_id) and count($request->day_id) > 0):
            $ads->days()->detach();
            foreach($request->day_id as $id):
                $ads->days()->attach($id);
            endforeach;
        endif;
        $response = new Response('Not ads');
        if($oldContent != $ads->content or true){
            if($request->hasCookie('not_allowed_ads')):
                $data = json_decode($request->cookie('not_allowed_ads') , true);
                if(!empty($data) and is_array($data) and in_array($ads->id , $data)){
                    $data = array_values( array_diff( $data, [$ads->id] ));
                    // Cookie::queue(Cookie::forget('not_allowed_ads'));
                    Cookie::queue(Cookie::make('not_allowed_ads' , json_encode($data) , (3*30*24*60)));
                    // return $data;
                    // $response = new Response('Not ads');
                    // $response->withCookie(cookie());
                }
            endif;
        }
        // return $response;
        flash(trans('dashboard.messages.save_successfully') , 'success');
        return redirect(route('restaurant.ads.index' ));
    }
    public function uploadVideo(Request $request){
        $request->validate([
            'id' => 'nullable|integer' , 
            'video' => 'required|mimes:mp4,gif' ,
            'type' =>'required|in:local_video,gif' 
        ]);
        if(!empty($request->id) and !$slider = RestaurantAds::find($request->id)):
            return trans('dashboard.errors.product_not_found');
        endif;
        
        if(isset($slider->id) and $slider->type == 'local_video' and !empty($slider->photo) and Storage::disk('public_storage')->exists($slider->photo)):
            Storage::disk('public_storage')->delete($slider->photo);
        endif;
        if(isset($slider->id) and $slider->type == 'gif' and !empty($slider->photo) and Storage::disk('public_storage')->exists('uploads/restaurants/ads/' . $slider->photo)):
            Storage::disk('public_storage')->delete('uploads/restaurants/ads/' . $slider->photo);
        endif;
        $videoPath = Storage::disk('public_storage')->put('uploads/restaurants/ads' ,$request->file('video'));
        if(isset($slider->id) and $request->type == 'local_video'){
            $slider->update([
                'content_type' => 'local_video' , 
                'content' => $videoPath,
            ]);
        }
        elseif(isset($slider->id) and $request->type == 'gif'){
            $slider->update([
                'content_type' => 'gif' , 
                'content' => basename($videoPath),
            ]);
        }else{
            $temp = TemporaryFile::create([
                'type' => 'restaurant_ads' , 
                'path' => $videoPath
            ]);
        }
       
        return response([
            'status' => 1 , 
            'video_path' => $videoPath , 
            'temp_id' => isset($temp->id) ? $temp->id : null  , 
        ]);
    }

    public function delete($id){
        $restaurant = auth('restaurant')->user();

        $ads = RestaurantAds::where('restaurant_id' , $restaurant->id)->findOrFail($id);
        if($ads->content_type == 'image' and Storage::disk('public_storage')->exists($ads->image_path)):
            Storage::disk('public_storage')->delete($ads->image_path);
        endif;

        $ads->delete();
        flash(trans('dashboard.messages.delete_successfully') , 'success');
        return redirect()->back();
    }


    public function uploadImage(Request $request){
        $request->validate([
            'photo' => 'required|mimes:png,jepg,jpg,svg' ,
            'action' => 'required|in:edit,create' ,
            'item_id' => 'required_if:action,edit|integer|exists:restaurant_ads,id' ,
        ]);
        if($request->action == 'edit')
            $item = RestaurantAds::findOrFail($request->item_id);

        if ($request->photo != null)
        {

            $photo = UploadImageEdit($request->file('photo'),'photo' , '/uploads/restaurants/ads' , (isset($item->photo) ? $item->photo : null) , 400 , 700);
            if(!empty($photo) and !empty($request->old_image) and Storage::disk('public')->exists('uploads/restaurants/ads/' . $request->old_image)){
                Storage::disk('public')->delete('uploads/restaurants/ads/' . $request->old_image);
            }
            if(isset($item->id))
                $item->update([
                    'content_type' => 'image' , 
                    'content' => $photo ,
                ]);
            return response([
                'photo' =>  $photo,
                'status' => true ,
            ]);
        }
        return response('error' , 500);
    }
}


<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantOffer;
use App\Models\RestaurantOfferPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Utils;
use Image;

class OfferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:restaurant');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::guard('restaurant')->user();
        if ($user->type == 'employee'):
            if (check_restaurant_permission($user->id , 5) == false):
                abort(404);
            endif;
            $user = Restaurant::find($user->restaurant_id);
        endif;
        $offers = RestaurantOffer::whereRestaurantId($user->id)
            ->orderBy('id' , 'desc')
            ->paginate(500);
        return view('restaurant.offers.index' , compact('offers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 5) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        return view('restaurant.offers.create' , compact('restaurant'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 5) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $this->validate($request , [
            'name'        => 'required|string|max:191',
            'image_name' => 'required|min:1|max:190'
        ]);
        // create new offer
        $offer = RestaurantOffer::create([
            'restaurant_id' => $restaurant->id,
            'name'          => $request->name,
            'photo' => $request->image_name ,
            'time' => $request->time ,
            'start_at' => $request->start_at ,
            'end_at' => $request->end_at ,
        ]);

        if(!empty($request->day_id) and count($request->day_id)):
            foreach($request->day_id as $id):
                $offer->days()->attach($id);
            endforeach;
        endif;
        // create offer photos
        flash(trans('messages.created'))->success();
        return redirect()->route('offers.index');
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
        $offer = RestaurantOffer::findOrfail($id);
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 5) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        return view('restaurant.offers.edit' , compact('offer' , 'restaurant'));
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
        $offer = RestaurantOffer::findOrfail($id);
        $this->validate($request , [
            'name'        => 'required|string|max:191',
        ]);

        $offer->update([
            'name'          => $request->name,
            'time' => $request->time ,
            'start_at' => $request->start_at ,
            'end_at' => $request->end_at
        ]);
        if(!empty($request->day_id) and count($request->day_id)):
            $offer->days()->detach();
            foreach($request->day_id as $id):
                $offer->days()->attach($id);
            endforeach;
        endif;
        flash(trans('messages.updated'))->success();
        return redirect()->route('offers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $offer = RestaurantOffer::findOrfail($id);
        $restaurant = Auth::guard('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 5) == false):
                abort(404);
            endif;
        endif;
        $offer->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('offers.index');
    }

    public function remove_photo($id)
    {
        $deleted = RestaurantOfferPhoto::findOrFail($id);
        if ($deleted->photo != null)
        {
            @unlink(public_path('/uploads/offers/' . $deleted->photo));
        }
        $deleted->delete();
        if ($deleted) {
            $v = '{"message":"done"}';
            return response()->json($v);
        }
    }



    public function uploadImage(Request $request){
        // return response($request->all() , 404);
        $request->validate([
            'photo' => 'required|mimes:png,jepg,jpg,svg' ,
            'action' => 'required|in:edit,create' ,
            'item_id' => 'required_if:action,edit|integer|exists:restaurant_offers,id' ,
        ]);
        if($request->action == 'edit')
            $item = RestaurantOffer::findOrFail($request->item_id);

        if ($request->photo != null)
        {

            $photo = UploadImageEdit($request->file('photo'),'photo' , '/uploads/offers' , (isset($item->photo) ? $item->photo : null) , 400 , 700);
            if(!empty($photo) and !empty($request->old_image) and Storage::disk('public')->exists('uploads/offers/' . $request->old_image)){
                Storage::disk('public')->delete('uploads/offers/' . $request->old_image);
            }
            if(isset($item->id))
                $item->update([
                    'photo' => $photo ,
                ]);
            return response([
                'photo' =>  $photo,
                'status' => true ,
            ]);
        }
        return response('error' , 500);
    }
}

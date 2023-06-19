<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantContactUs;
use App\Models\RestaurantContactUsLink;
use App\Models\RestaurantFeedback;
use App\Models\RestaurantFeedbackBranch;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RestaurantContactUsController extends Controller
{

    public function index(Request $request){
        $user = auth('restaurant')->user();
        if ($user->type == 'employee'):
            if (check_restaurant_permission($user->id , 5) == false):
                abort(404);
            endif;
            $user = Restaurant::find($user->restaurant_id);
        endif;
        $contactUs = RestaurantContactUs::where('restaurant_id' , $user->id)
            ->orderBy('created_at' , 'desc')
            ->paginate(500);

        return view('restaurant.restaurant_contact_us.index'  , compact('contactUs'));
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
            if (check_restaurant_permission($restaurant->id , 5) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        if(!$restaurant):
            abort(422);
        endif;
        $links = RestaurantContactUsLink::where('restaurant_id' , $restaurant->id)->where('status' , 'true')->get();
        $maxSort = RestaurantContactUs::max('sort')  + 1;
        return view('restaurant.restaurant_contact_us.create' , compact('links' , 'maxSort'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([

            'title_ar'   => 'required|string|max:191',
            'title_en'   => 'required|string|max:191',
            'url'   => 'required|url',
            'sort' => 'required|integer|min:1' , 
            'image' => 'required|file|image' ,
            'link_id' => 'nullable|exists:restaurant_contact_us_links,id'

        ]);
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 5) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        if(!$restaurant):
            abort(422);
        endif;
        $data['restaurant_id'] = $restaurant->id;
        if($request->hasFile('image')){
            $data['image'] = Storage::disk('public_storage')->put( 'uploads/contact_us', $request->file('image'));
        }
        // create new barnch
        $temp = RestaurantContactUs::create($data);
        flash(trans('messages.created'))->success();
        return redirect()->route('restaurant.contact_us.index');

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
            if (check_restaurant_permission($restaurant->id , 5) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        if(!$restaurant):
            abort(422);
        endif;
        $contact = RestaurantContactUs::where('restaurant_id' , $restaurant->id)->findOrFail($id);
        
        $links = RestaurantContactUsLink::where('restaurant_id' , $restaurant->id)->where('status' , 'true')->get();
        return view('restaurant.restaurant_contact_us.edit' , compact('contact' , 'links'));
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
        $data = $request->validate([

            'title_ar'   => 'required|string|max:191',
            'title_en'   => 'required|string|max:191',
            'url'   => 'required|url',
            'sort' => 'required|integer|min:1' , 
            'image' => 'nullable|file|image' ,
            'status' => 'required|in:true,false',
            'link_id' => 'nullable|exists:restaurant_contact_us_links,id'
        ]);
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 5) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        if(!$restaurant):
            abort(422);
        endif;
        $contact = RestaurantContactUs::where('restaurant_id' , $restaurant->id)->findOrFail($id);
        $data['restaurant_id'] = $restaurant->id;

        if($request->hasFile('image')){
            if(!empty($contact->image) and Storage::disk('public_storage')->exists($contact->image)){
                Storage::disk('public_storage')->delete($contact->image);
            }
            $data['image'] = Storage::disk('public_storage')->put( 'uploads/contact_us', $request->file('image'));
        }
        // create new barnch
        $contact->update($data);
        flash(trans('messages.updated'))->success();
        return redirect()->route('restaurant.contact_us.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 5) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        if(!$restaurant):
            abort(422);
        endif;
        $branch = RestaurantContactUs::where('restaurant_id' , $restaurant->id)->findOrFail($id);
        if(!empty($branch->image) and Storage::disk('public_storage')->exists($branch->image)){
            Storage::disk('public_storage')->delete($branch->image);
        }
        $branch->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('restaurant.contact_us.index');
    }


    public function setting(Request $request){
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 5) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        if(!$restaurant):
            abort(422);
        endif;
        if($request->method() == 'POST' and $request->has('enable_contact_us')):
            $request->validate([
                'enable_contact_us' => 'required|in:true,false'
            ]);
            $restaurant->update([
                'enable_contact_us' => $request->enable_contact_us
            ]);
        endif;

        return view('restaurant.restaurant_contact_us.settings' , compact('restaurant'));
    }
}

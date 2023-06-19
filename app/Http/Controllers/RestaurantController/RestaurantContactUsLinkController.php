<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantContactUsLink;
use App\Models\RestaurantContactUsLinkLink;
use App\Models\RestaurantFeedback;
use App\Models\RestaurantFeedbackBranch;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RestaurantContactUsLinkController extends Controller
{

    public function index(Request $request){
        $user = auth('restaurant')->user();
        if ($user->type == 'employee'):
            if (check_restaurant_permission($user->id , 5) == false):
                abort(404);
            endif;
            $user = Restaurant::find($user->restaurant_id);
        endif;
        $contactUs = RestaurantContactUsLink::where('restaurant_id' , $user->id)
            ->orderBy('created_at' , 'desc')
            ->paginate(500);

        return view('restaurant.restaurant_contact_us_links.index'  , compact('contactUs'));
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
        return view('restaurant.restaurant_contact_us_links.create');
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

            'name_ar'   => 'required|string|max:191',
            'name_en'   => 'required|string|max:191',
            'barcode'   => 'required|unique:restaurant_contact_us_links,barcode',

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
        // create new barnch
        $temp = RestaurantContactUsLink::create($data);
        flash(trans('messages.created'))->success();
        return redirect()->route('restaurant.link_contact_us.index');

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
        $contact = RestaurantContactUsLink::where('restaurant_id' , $restaurant->id)->findOrFail($id);

        return view('restaurant.restaurant_contact_us_links.edit' , compact('contact'));
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

            'name_ar'   => 'required|string|max:191',
            'name_en'   => 'required|string|max:191',
            'barcode'   => 'required|unique:restaurant_contact_us_links,barcode,' . $id,
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
        $contact = RestaurantContactUsLink::where('restaurant_id' , $restaurant->id)->findOrFail($id);
        
        // create new barnch
        $contact->update($data);
        flash(trans('messages.updated'))->success();
        return redirect()->route('restaurant.link_contact_us.index');

    }

    public function changeStatus(Request $request, $id)
    {
        $data = $request->validate([
            'status' => 'required|in:true,false' 
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
        $contact = RestaurantContactUsLink::where('restaurant_id' , $restaurant->id)->findOrFail($id);
        
        // create new barnch
        $contact->update([
            'status' => $request->status ,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('restaurant.link_contact_us.index');

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
        $contact = RestaurantContactUsLink::where('restaurant_id' , $restaurant->id)->findOrFail($id);
        if($contact->items()->count() > 0):
            flash(trans('dashboard.errors.contact_us_link_delete_items'))->error();
            return redirect()->route('restaurant.link_contact_us.index');
        endif;
        $contact->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('restaurant.link_contact_us.index');
    }


    public function show(Request $request , $id){
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
        
        $contact = RestaurantContactUsLink::where('restaurant_id' , $restaurant->id)->findOrFail($id);
        // return $contact;
        return view('restaurant.restaurant_contact_us_links.show' , compact('restaurant' , 'contact'));
    }
}

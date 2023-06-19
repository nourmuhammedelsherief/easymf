<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantFeedback;
use App\Models\LoyaltyPointPrice;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LayoltyPointController extends Controller
{

    public function index(Request $request){

        $setting = Setting::findOrFail(1);
        $user = auth('restaurant')->user();
        if ($user->type == 'employee'):
            if (check_restaurant_permission($user->id , 5) == false):
                abort(404);
            endif;
            $user = Restaurant::find($user->restaurant_id);
        endif;
        $items = LoyaltyPointPrice::where('restaurant_id' , $user->id)
            ->orderBy('created_at' , 'desc')
            ->paginate(500);

        return view('restaurant.loyalty_points_prices.index'  , compact('items' , 'setting' ));
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
            //if (check_restaurant_permission($restaurant->id , 5) == false):
   //             abort(404);
      //      endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        if(!$restaurant):
            abort(422);
        endif;
        return view('restaurant.loyalty_points_prices.create');
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

            'points'   => 'required|integer|min:1',
            'price'   => 'required|integer|min:1',
        ]);
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            // if (check_restaurant_permission($restaurant->id , 5) == false):
            //     abort(404);
            // endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        if(!$restaurant):
            abort(422);
        endif;
        $data['restaurant_id'] = $restaurant->id;
        // create new barnch
        $temp = LoyaltyPointPrice::create($data);
        flash(trans('messages.created'))->success();
        return redirect()->route('restaurant.loyalty_point_price.index');

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
            //if (check_restaurant_permission($restaurant->id , 5) == false):
   //             abort(404);
      //      endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        if(!$restaurant):
            abort(422);
        endif;
        $branch = LoyaltyPointPrice::where('restaurant_id' , $restaurant->id)->findOrFail($id);
        return view('restaurant.loyalty_points_prices.edit' , compact('branch'));
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

            'points'   => 'required|integer|min:1',
            'price'   => 'required|integer|min:1',
        ]);
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            //if (check_restaurant_permission($restaurant->id , 5) == false):
   //             abort(404);
      //      endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        if(!$restaurant):
            abort(422);
        endif;
        $branch = LoyaltyPointPrice::where('restaurant_id' , $restaurant->id)->findOrFail($id);
        $branch->update($data);
        flash(trans('messages.updated'))->success();
        return redirect()->route('restaurant.loyalty_point_price.index');
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
            //if (check_restaurant_permission($restaurant->id , 5) == false):
   //             abort(404);
      //      endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        if(!$restaurant):
            abort(422);
        endif;
        $branch = LoyaltyPointPrice::where('restaurant_id' , $restaurant->id)->findOrFail($id);
        $branch->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('restaurant.loyalty_point_price.index');
    }


    public function settings(Request $request){
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            //if (check_restaurant_permission($restaurant->id , 5) == false):
   //             abort(404);
      //      endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        if(!$restaurant):
            abort(422);
        endif;
        if($request->method() == 'POST' and $request->has('enable_loyalty_point')):
            $data = $request->validate([
                'enable_loyalty_point' => 'required|in:true,false' , 
                'enable_loyalty_point_paymet_method'=> 'required|in:true,false' , 
            ]);
            $restaurant->update($data);
        endif;

        return view('restaurant.loyalty_points_prices.settings' , compact('restaurant'));
    }
}

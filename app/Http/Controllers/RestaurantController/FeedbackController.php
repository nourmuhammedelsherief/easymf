<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantFeedback;
use App\Models\RestaurantFeedbackBranch;
use App\Models\Setting;
use Illuminate\Http\Request;

class FeedbackController extends Controller
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
        $feedbacks = RestaurantFeedback::where('restaurant_id' , $user->id)
            ->orderBy('created_at' , 'desc')
            ->paginate(500);
        return view('restaurant.feedback.index'  , compact('feedbacks' , 'setting' ));
    }

    public function rateUs(Request $request , $id){
        $restaurant = Restaurant::findOrFail($id);
        if($restaurant->enable_feedback != 'true') return redirect()->back()->withError('Fail');
        // return $request->all();
        $data = $request->validate([
            'name' => 'nullable|min:1|max:190' ,
            'mobile' => 'nullable|numeric' ,
            'message' => 'nullable|min:1|max:10000' ,
            'eat_rate' => 'nullable|in:1,2,3,4,5' ,
            'place_rate' => 'nullable|in:1,2,3,4,5' ,
            'service_rate' => 'nullable|in:1,2,3,4,5' ,
            'worker_rate' => 'nullable|in:1,2,3,4,5' ,
            'speed_rate' => 'nullable|in:1,2,3,4,5' ,
            'reception_rate' => 'nullable|in:1,2,3,4,5' ,
            'branch_id' => 'nullable|integer|exists:restaurant_feedback_branches,id'
        ]);

        if(auth('web')->check() and $user = auth('web')->user()):
            $data['user_id'] = $user->id;
        endif;
        $data['restaurant_id'] = $restaurant->id;
        $branch = RestaurantFeedbackBranch::findOrFail($request->branch_id);
        RestaurantFeedback::create($data);
        return response([
            'status' => true ,
            'redirect_to' => $branch->link,
        ]);
    }
}

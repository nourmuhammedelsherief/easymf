<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantFeedback;
use App\Models\RestaurantFeedbackBranch;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedbackBranchController extends Controller
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
        $branches = RestaurantFeedbackBranch::where('restaurant_id' , $user->id)
            ->orderBy('created_at' , 'desc')
            ->paginate(500);

        return view('restaurant.feedback_branches.index'  , compact('branches' , 'setting' ));
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
        return view('restaurant.feedback_branches.create');
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
            'link'   => 'nullable|url',
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
        $temp = RestaurantFeedbackBranch::create($data);
        flash(trans('messages.created'))->success();
        return redirect()->route('restaurant.feedback.branch.index');

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
        $branch = RestaurantFeedbackBranch::where('restaurant_id' , $restaurant->id)->findOrFail($id);
        return view('restaurant.feedback_branches.edit' , compact('branch'));
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
            'link'   => 'nullable|url',
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
        $branch = RestaurantFeedbackBranch::where('restaurant_id' , $restaurant->id)->findOrFail($id);
        $branch->update($data);
        flash(trans('messages.updated'))->success();
        return redirect()->route('restaurant.feedback.branch.index');
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
        $branch = RestaurantFeedbackBranch::where('restaurant_id' , $restaurant->id)->findOrFail($id);
        $branch->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('restaurant.feedback.branch.index');
    }


    public function enableFeedback(Request $request){
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
        if($request->method() == 'POST' and $request->has('enable_feedback')):
            $request->validate([
                'enable_feedback' => 'required|in:true,false'
            ]);
            if($restaurant->rateBranches()->count() == 0):
                $restaurant->update([
                    'enable_feedback' => "false",
                ]);
                flash(trans('dashboard.errors.reservation_setting_no_branches'))->error();
                return redirect()->back();
            endif;
            $restaurant->update([
                'enable_feedback' => $request->enable_feedback
            ]);
        endif;

        return view('restaurant.feedback_branches.settings' , compact('restaurant'));
    }
}

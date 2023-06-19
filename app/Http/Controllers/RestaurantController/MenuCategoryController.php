<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\MenuCategory;
use App\Models\MenuCategoryDay;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MenuCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 4) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        if ($restaurant->status == 'finished' or $restaurant->subscription->status == 'tentative_finished') {
            return redirect()->route('RestaurantProfile');
        }
        $branches = Branch::whereRestaurantId($restaurant->id)
            ->whereIn('status' , ['active' , 'tentative'])
            ->get();
        $categories = MenuCategory::whereRestaurantId($restaurant->id)->paginate(500);
        return view('restaurant.menu_categories.index', compact('categories' , 'branches'));
    }
    public function branch_categories($id)
    {
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 4) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $categories = MenuCategory::whereRestaurantId($restaurant->id)
            ->where('branch_id' , $id)
            ->paginate(500);
        $branches = Branch::whereRestaurantId($restaurant->id)
            ->whereIn('status' , ['active' , 'tentative'])
            ->get();
        return view('restaurant.menu_categories.index', compact('categories' , 'branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 4) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $branches = Branch::whereRestaurantId($restaurant->id)
            ->where('foodics_status' , 'false')
            ->whereIn('status' , ['active' , 'tentative'])
            ->get();
        return view('restaurant.menu_categories.create', compact('branches' , 'restaurant'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 4) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $this->validate($request, [
            'branch_id' => 'required',
            'branch_id*' => 'exists:branches,id',
            'name_ar' => 'nullable|string|max:191',
            'name_en' => 'nullable|string|max:191',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            // 'photo' => 'nullable|mimes:jpg,jpeg,png,gif,tif,psd,webp|max:20000',
            'image_name' =>'nullable|min:1|max:190' ,
            'start_at' => 'sometimes',
            'end_at' => 'sometimes',
            'time' => 'sometimes|in:true,false',
        ]);

        if ($request->name_ar == null && $request->name_en == null) {
            flash(trans('messages.name_required'))->error();
            return redirect()->back();
        }
        if ($request->branch_id != null)
        {
            foreach ($request->branch_id as $branch_id)
            {
                // create new menu category
                $cat = MenuCategory::create([
                    'restaurant_id' => $restaurant->id,
                    'branch_id' => $branch_id,
                    'name_ar' => $request->name_ar,
                    'name_en' => $request->name_en,
                    'description_ar' => $request->description_ar,
                    'description_en' => $request->description_en,
                    'photo' => $request->image_name ,
                    'active' => 'true',
                    'start_at' => $request->start_at,
                    'end_at' => $request->end_at,
                    'time' => $request->time == null ? 'false' : $request->time,
                ]);
                if ($request->time == 'true' && $request->day_id != null)
                {
                    MenuCategoryDay::where('menu_category_id' , $cat->id)->delete();
                    foreach ($request->day_id as $day)
                    {
                        MenuCategoryDay::create([
                            'menu_category_id' => $cat->id,
                            'day_id'           => $day,
                        ]);
                    }
                }
            }
        }
        flash(trans('messages.created'))->success();
        return redirect()->route('menu_categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 4) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $category = MenuCategory::findOrFail($id);
        $branches = Branch::whereRestaurantId($restaurant->id)
            ->where('foodics_status' , 'false')
            ->whereIn('status' , ['active' , 'tentative'])
            ->get();
        return view('restaurant.menu_categories.edit', compact('branches', 'category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $category = MenuCategory::findOrFail($id);
        if ($category->branch->foodics_status == 'true')
        {
            $this->validate($request, [
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'photo' => 'nullable|mimes:jpg,jpeg,png,gif,tif,psd,webp|max:20000',
                'start_at' => 'sometimes',
                'end_at' => 'sometimes',
                'time' => 'sometimes|in:true,false',
            ]);
            // create new menu category
            $category->update([
                'description_ar' => $request->description_ar,
                'description_en' => $request->description_en,
                'photo' => $request->file('photo') == null ? $category->photo : UploadImageEdit($request->file('photo'), 'photo', '/uploads/menu_categories', $category->photo),
                'start_at' => $request->start_at == null ? $category->start_at : $request->start_at,
                'end_at' => $request->end_at == null ? $category->end_at : $request->end_at,
                'time' => $request->time == null ? $category->time : $request->time,
            ]);
        }else{
            $this->validate($request, [
                'branch_id' => 'required|exists:branches,id',
                'name_ar' => 'nullable|string|max:191',
                'name_en' => 'nullable|string|max:191',
                'description_ar' => 'nullable|string',
                'description_en' => 'nullable|string',
                'photo' => 'nullable|mimes:jpg,jpeg,png,gif,tif,psd,webp|max:20000',
                'start_at' => 'sometimes',
                'end_at' => 'sometimes',
                'time' => 'sometimes|in:true,false',
            ]);
            if ($request->name_ar == null && $request->name_en == null) {
                flash(trans('messages.name_required'))->error();
                return redirect()->back();
            }
            // create new menu category
            $category->update([
                'branch_id' => $request->branch_id,
                'name_ar' => $request->name_ar,
                'name_en' => $request->name_en,
                'description_ar' => $request->description_ar,
                'description_en' => $request->description_en,
                'photo' => $request->file('photo') == null ? $category->photo : UploadImageEdit($request->file('photo'), 'photo', '/uploads/menu_categories', $category->photo),
                'start_at' => $request->start_at == null ? $category->start_at : $request->start_at,
                'end_at' => $request->end_at == null ? $category->end_at : $request->end_at,
                'time' => $request->time == null ? $category->time : $request->time,
            ]);
        }
        if ($request->time == 'true' && $request->day_id != null)
        {
            MenuCategoryDay::where('menu_category_id' , $category->id)->delete();
            foreach ($request->day_id as $day)
            {
                MenuCategoryDay::create([
                    'menu_category_id' => $category->id,
                    'day_id'           => $day,
                ]);
            }
        }
        flash(trans('messages.updated'))->success();
        return redirect()->route('menu_categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $category = MenuCategory::findOrFail($id);
        if ($category->photo != null) {
            @unlink(public_path('/uploads/menu_categories/' . $category->photo));
        }
        $category->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('menu_categories.index');
    }

    public function activate($id, $active)
    {
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $category = MenuCategory::findOrFail($id);
        $category->update([
            'active' => $active,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('menu_categories.index');
    }



    public function uploadImage(Request $request){
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $request->validate([
            'photo' => 'required|mimes:png,jepg,jpg,svg' ,
            'action' => 'required|in:create,edit' ,
            'item_id' => 'required_if:action,edit|integer|exists:menu_categories,id' ,
        ]);
        if($request->action == 'edit')
            $item = MenuCategory::findOrFail($request->item_id);

        if ($request->photo != null)
        {
            $photo = UploadImageEdit($request->file('photo'),'photo' , '/uploads/menu_categories' , (isset($item->photo) ? $item->photo : null));
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
    public function arrange($id)
    {
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $category = MenuCategory::findOrFail($id);
        return view('restaurant.menu_categories.arrange' , compact('category'));
    }
    public function arrange_submit(Request $request , $id)
    {
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $category = MenuCategory::findOrFail($id);
        $this->validate($request , [
            'arrange' => 'required'
        ]);
        $category->update([
            'arrange' => $request->arrange
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('menu_categories.index');

    }

    public function copy_category($id)
    {
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 4) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $category = MenuCategory::findOrFail($id);
        $branches = Branch::whereRestaurantId($restaurant->id)
            ->where('foodics_status' , 'false')
            ->whereIn('status' , ['active' , 'tentative'])
            ->get();
        return view('restaurant.menu_categories.copy', compact('branches', 'category'));
    }
    public function copy_category_post(Request $request)
    {
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 4) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $this->validate($request, [
            'branch_id' => 'required',
            'branch_id*' => 'exists:branches,id',
            'name_ar' => 'nullable|string|max:191',
            'name_en' => 'nullable|string|max:191',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            // 'photo' => 'nullable|mimes:jpg,jpeg,png,gif,tif,psd,webp|max:20000',
            'image_name' =>'nullable|min:1|max:190' ,
            'start_at' => 'sometimes',
            'end_at' => 'sometimes',
            'time' => 'sometimes|in:true,false',
        ]);
        if ($request->name_ar == null && $request->name_en == null) {
            flash(trans('messages.name_required'))->error();
            return redirect()->back();
        }
        if ($request->branch_id != null)
        {
            foreach ($request->branch_id as $branch_id)
            {
                // create new menu category
                $cat = MenuCategory::create([
                    'restaurant_id' => $restaurant->id,
                    'branch_id' => $branch_id,
                    'name_ar' => $request->name_ar,
                    'name_en' => $request->name_en,
                    'description_ar' => $request->description_ar,
                    'description_en' => $request->description_en,
                    'photo' => $request->image_name ,
                    'active' => 'true',
                    'start_at' => $request->start_at,
                    'end_at' => $request->end_at,
                    'time' => $request->time == null ? 'false' : $request->time,
                ]);
                if ($request->time == 'true' && $request->day_id != null)
                {
                    MenuCategoryDay::where('menu_category_id' , $cat->id)->delete();
                    foreach ($request->day_id as $day)
                    {
                        MenuCategoryDay::create([
                            'menu_category_id' => $cat->id,
                            'day_id'           => $day,
                        ]);
                    }
                }
            }
        }
        flash(trans('messages.created'))->success();
        return redirect()->route('menu_categories.index');
    }

    public function deleteCategoryPhoto($id)
    {
        if(!auth('restaurant')->check()):
            return redirect(url('restaurant/login'));
        endif;
        $category = MenuCategory::findOrFail($id);
        if ($category->photo != null) {
            @unlink(public_path('/uploads/menu_categories/' . $category->photo));
        }
        $category->update([
            'photo' => null
        ]);
        flash(trans('messages.deleted'))->success();
        return redirect()->back();
    }
}

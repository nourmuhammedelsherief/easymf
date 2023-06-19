<?php

namespace App\Http\Controllers\RestaurantController\Reservation;

use App\Http\Controllers\Controller;
use App\Models\Reservation\ReservationBranch;
use App\Models\Reservation\ReservationOrder;
use App\Models\Reservation\ReservationPlace;
use App\Models\Reservation\ReservationTable;
use App\Models\Restaurant;
use App\Models\ServiceSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReservationTableController extends Controller
{

    public function __construct()
    {
        // Carbon::now()->greaterThanOrEqualTo()
        $this->middleware('auth:restaurant');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $checkReservation = ServiceSubscription::whereRestaurantId($restaurant->id)
            ->where('service_id' , 1)
            ->whereIn('status' , ['active' , 'tentative'])
            ->first();
        if ($checkReservation == null)
        {
            abort(404);
        }
        $tables = ReservationTable::whereRestaurantId($restaurant->id)->with('branch' , 'place')->where('is_available' , 1)
            ->orderBy('id' , 'desc')
            ->paginate(20);
        $action = 'index';
        $type = $request->type == 'chair' ? 'chair' : 'table';
        return view('restaurant.reservations.tables.index' , compact('tables' , 'action' , 'type'));
    }
    public function expireIndex(Request $request)
    {
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $checkReservation = ServiceSubscription::whereRestaurantId($restaurant->id)
            ->where('service_id' , 1)
            ->whereIn('status' , ['active' , 'tentative'])
            ->first();
        if ($checkReservation == null)
        {
            abort(404);
        }
        $tables = ReservationTable::whereRestaurantId($restaurant->id)->with('branch' , 'place' , 'periods')->where('is_available' , 0);
        if(!empty($request->type)){
            $tables = $tables->where('type' , $request->type);
        }

        $tables   = $tables->orderBy('id' , 'desc')
            ->paginate(20);
        $action = 'expire';
        $type = $request->type == 'chair' ? 'chair' : 'table';
        return view('restaurant.reservations.tables.index' , compact('tables' , 'action' , 'type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $branches = ReservationBranch::where('restaurant_id' , $restaurant->id)->whereStatus(1)->get();
        $places = ReservationPlace::where('restaurant_id' , $restaurant->id)->whereStatus(1)->get();
        $now = Carbon::now();
        $dates = [];
        for ($i=0; $i < (360 * 2); $i++) {
            $dates[] = Carbon::now()->addDays($i)->format('Y-m-d');
        }
        $type = $request->type ;
        return view('restaurant.reservations.tables.create'  , compact('places' , 'branches' , 'dates' , 'type'));
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
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        // return $request->all();
        $data = $request->validate([
            'branch_id' => 'required|integer' ,
            'place_id' => 'required|integer' ,
            'type' => 'required|in:table,chair,package' ,
            'dates' => 'required|array|min:1' ,
            'dates.*' => 'required|date|after:yesterday' ,
            'times' => 'required|array|min:1' ,
            'times.*.from' => 'required|date_format:H:i' ,
            'times.*.to' => 'required|date_format:H:i|after:from' ,
            'price' => 'required|integer|min:1'
        ] , [
            'times.*.from.*' => trans('dashboard.errors.time_from_fail') ,
            'times.*.to.*' => trans('dashboard.errors.time_to_fail')
        ]);
        $data['reservation_branch_id'] = $request->branch_id;
        $data['reservation_place_id'] = $request->place_id;
        $image = null ;
        if($request->type == 'table'):
            $request->validate([
                'people_count' => 'required|integer|min:1' ,
                'table_count' => 'required|integer|min:1' ,
            ]);
            $data['people_count'] = $request->people_count;
            $data['table_count'] = $request->table_count;
        elseif($request->type == 'chair' ):
            // return $request->all();
            $request->validate([
                'chair_min' => 'required|integer|min:1' ,
                'chair_max' => 'required|integer|min:1' ,
            ]);
            $data['chair_min']  = $request->chair_min;
            $data['chair_max']  = $request->chair_max;
        endif;
        $images = [];
        if($request->type == 'package'):
            $dd = $request->validate([
                'image' => 'required|mimes:png,jpg,jpeg|max:5500'  ,
                'title_en' => 'required|min:1|max:190' ,
                'title_ar' => 'required|min:1|max:190' ,
                'description_ar' => 'nullable|min:1' ,
                'description_en' => 'nullable|min:1' ,
                'images' => 'nullable|array'  ,
                'images.*' => 'file|mimes:png,jpeg,jpg|max:5000' ,
                'people_count' => 'required|integer
                |min:1' ,
                'chair_max' => 'required|integer|min:1' ,
                'chair_min' => 'required|integer|min:1' ,
            ]);
            $data = array_merge($data , $dd);
            if($request->hasFile('image')):
                $data['image'] = UploadImageEdit($request->file('image') , 'image' , '/uploads/reservation_tables' , null);
            endif;
            if(!empty($request->images) and count($request->images) > 0):
                foreach($request->images as $index => $img):
                    // $file = $request->file('image.' . $index);
                    // return var_dump($img);
                    $images[] =  'uploads/reservation_tables/' . UploadImageEdit($img , 'image' , '/uploads/reservation_tables' , null);
                endforeach;
            endif;
        endif;
        $data['restaurant_id'] = $restaurant->id;
        $reservation = ReservationTable::create($data);

        foreach($request->dates as $date):
            $reservation->dates()->create(['date' => $date]);
        endforeach;

        foreach($request->times as $date):
            $reservation->periods()->create(['from' => $date['from'] , 'to' => $date['to'] , 'table_count' => $request->table_count]);
        endforeach;
        if(count($images) > 0):
            foreach($images as $i => $value){
                $reservation->images()->create([
                    'path' => $value ,
                ]);
            }
        endif;

        flash(trans('messages.created'))->success();
        return redirect(route('restaurant.reservation.tables.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($table)
    {
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $table = ReservationTable::where('restaurant_id' , $restaurant->id)->with(['dates' , 'periods'])->findOrFail($table);

        return response([
            'html' => view('restaurant.reservations.tables.show' , compact('table'))->render()
        ]);
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
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $table = $restaurant->reservationTables()->with(['dates' => function($query){
            $query->orderBy('date' , 'asc');
        }, 'periods'])->findOrFail($id);
        $branches = ReservationBranch::where('restaurant_id' , $restaurant->id)->whereStatus(1)->get();
        $places = ReservationPlace::where('restaurant_id' , $restaurant->id)->whereStatus(1)->get();
        $now = Carbon::now();
        $startDate = isset($table->dates[0]) ? $table->dates[0]->date : null;

        $dates = [];
        for ($i=0; $i < (360 * 2); $i++) {
            if($startDate == null):
                $dates[] = Carbon::now()->addDays($i)->format('Y-m-d');
            else:
                $dates[] = Carbon::createFromTimestamp(strtotime($startDate))->addDays($i)->format('Y-m-d');
            endif;
        }
        return view('restaurant.reservations.tables.edit'  , compact('places' , 'branches' , 'dates'  , 'table'));
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
        // return $request->all();
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $table = $restaurant->reservationTables()->with(['dates' => function($query){
            $query->orderBy('date' , 'asc');
        }, 'periods'])->findOrFail($id);

        $data = $request->validate([
            'branch_id' => 'required|integer' ,
            'place_id' => 'required|integer' ,
            // 'people_count' => 'required|integer|min:1' ,
            // 'table_count' => 'required|integer|min:1' ,
            'dates' => 'required|array|min:1' ,
            'dates.*' => 'required|date' ,
            'times' => 'required|array|min:1' ,
            'times.*.from' => 'required|date_format:H:i' ,
            'times.*.to' => 'required|date_format:H:i|after:from' ,
            'price' => 'required|integer|min:1'
        ] , [
            'times.*.from.*' => trans('dashboard.errors.time_from_fail') ,
            'times.*.to.*' => trans('dashboard.errors.time_to_fail')
        ]);
        $data['reservation_branch_id'] = $request->branch_id ;
        $data['reservation_place_id'] = $request->place_id ;
        if($table->type == 'table'):
            $request->validate([
                'people_count' => 'required|integer|min:1' ,
                'table_count' => 'required|integer|min:1' ,
            ]);
            $data['people_count'] = $request->people_count;
            $data['table_count'] = $request->table_count;
        elseif($table->type == 'chair'):
            $request->validate([
                'chair_min' => 'required|integer|min:1' ,
                'chair_max' => 'required|integer|min:1' ,
            ]);
            $data['chair_min'] = $request->chair_min;
            $data['chair_max'] = $request->chair_max;
        endif;
        $images = [];
        if($table->type == 'package'):
            $dd = $request->validate([
                'image' => 'mimes:png,jpg,jpeg|max:5500'  ,
                'title_en' => 'required|min:1|max:190' ,
                'title_ar' => 'required|min:1|max:190' ,
                'description_ar' => 'nullable|min:1' ,
                'description_en' => 'nullable|min:1' ,
                'images' => 'nullable|array'  ,
                'images.*' => 'file|mimes:png,jpeg,jpg|max:5000' ,
                'chair_max' => 'required|integer|min:1' ,
                'chair_min' => 'required|integer|min:1' ,
                'people_count' => 'required|integer|min:1' ,
            ]);
            $data = array_merge($data , $dd);
            if($request->hasFile('image')):
                $data['image'] = UploadImageEdit($request->file('image') , 'image' , '/uploads/reservation_tables' , null);
            endif;

            if(!empty($request->images) and count($request->images) > 0):
                foreach($request->images as $index => $img):
                    // $file = $request->file('image.' . $index);
                    // return var_dump($img);
                    $images[] =  'uploads/reservation_tables/' . UploadImageEdit($img , 'image' , '/uploads/reservation_tables' , null);
                endforeach;
            endif;
        endif;

        $table->update($data);
        // $table->dates()->delete();
        $table->dates()->whereNotIn('date' , $request->dates)->delete();
        foreach($request->dates as $date):
            if(!$table->dates()->where('date' , $date)->first()):
                $table->dates()->create(['date' => $date]);
            endif;
        endforeach;
        $periodsId = [];
        foreach($request->times as $date):
            if(isset($date['id']) and is_numeric($date['id']))
                $periodsId[] = $date['id'];
        endforeach;
        if(count($periodsId) > 0)
            $table->periods()->whereNotIn('id' , $periodsId)->delete();
        foreach($request->times as $date):
            if(isset($date['id']) and is_numeric($date['id']) and $period = $table->periods()->find($date['id'])):
                $period->update([
                    'from' => $date['from'] ,
                    'to' => $date['to'] ,
                    'table_count' => $request->table_count,
                ]);
            else:
                $table->periods()->create(['from' => $date['from'] , 'to' => $date['to'] , 'table_count' => $request->table_count]);
            endif;
        endforeach;
        if(count($images) > 0):
            foreach($images as $i => $value){
                $table->images()->create([
                    'path' => $value ,
                ]);
            }
        endif;
        $isExpire = $table->isExpire();
        if($isExpire == true):
            $table->update([
                'is_available' => 0 ,
            ]);
        else:
            $table->update([
                'is_available' => 1 ,
            ]);
        endif;
        flash(trans('messages.updated'))->success();
        return redirect(route('restaurant.reservation.tables.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        $table = ReservationTable::where('restaurant_id' , $restaurant->id)->findOrFail($id);
        if($table->orders()->count() > 0):
            flash(trans('dashboard.errors.delete_table_order_fail'))->error();
            return redirect(route('restaurant.reservation.tables.index'));
        endif;

        if($table->type == 'package'):
            Storage::disk('public_storage')->delete('uploads/reservation_tables/' . $table->image);
            if($table->images->count() > 0):
                foreach($table->images as $image):
                    Storage::disk('public_storage')->delete($image->path);
                endforeach;
            endif;
        endif;
        $table->delete();

        flash(trans('dashboard.messages.delete_successfully'))->success();
        return redirect(route('restaurant.reservation.tables.index'));
    }
    public function deleteImage(ReservationTable $table , $id){
        if(!$image  = $table->images()->find($id)):
            return response([
                'status' => 0,
                'message' => trans('dashboard.errors.image_not_found')
            ]);
        endif;
        Storage::disk('public_storage')->delete($image->path);
        $image->delete();
        return response([
            'status' => true ,
            "message" => trans('dashboard.messages.image_delete_success')
        ]);
    }
    public function changeStatus(Request $request , ReservationTable $table){
        $request->validate([
            'status' => 'required|boolean'
        ]);

        $restaurant = auth('restaurant')->user();
        if ($restaurant->type == 'employee'):
            if (check_restaurant_permission($restaurant->id , 3) == false):
                abort(404);
            endif;
            $restaurant = Restaurant::find($restaurant->restaurant_id);
        endif;
        if($restaurant->id != $table->restaurant_id) abort(404 , trans('dashboard.errors.tables_not_found'));

        $table->update([
            'status' => $request->status == 1 ? 'available' : 'not_available'
        ]);

        flash(trans('dashboard.messages.change_status_success'))->success();
        return redirect(route('restaurant.reservation.tables.index'));
    }

    public function confirmReservation(Request $request , $id , $code){
        if($order = ReservationOrder::where('num' , $code)->where('is_confirm' , 0)->find($id)):
            $order->update([
                'is_confirm' => 1 ,
                'status' => 'paid'
            ]);
            return response([
                'status' => true ,
                'message' => trans('dashboard.messages.reservation_confirmed')
            ]);
        endif;
        return response([
            'status' => false ,
            'message' => trans('dashboard.errors.reservation_num_fail')
        ]);
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MenuCategory extends Model
{
    use HasFactory;
    protected $table = 'menu_categories';
    protected $fillable = [
        'restaurant_id',
        'branch_id',
        'name_ar',
        'name_en',
        'photo',
        'foodics_image',
        'foodics_id',
        'active',
        'arrange',
        'start_at',
        'end_at',
        'time',
        'description_ar',
        'description_en',
        'old_id',
    ];
    public function getNameAttribute(){
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }
    public function getDescriptionAttribute(){
        return app()->getLocale() == 'ar' ? $this->description_ar : $this->description_en;
    }
    public function getImagePathAttribute(){
        if(empty($this->photo)) return $this->restaurant->image_path;
        else return 'uploads/menu_categories/' . $this->photo;
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class , 'branch_id');
    }
    public function sub_categories()
    {
        return $this->hasMany(RestaurantSubCategory::class , 'menu_category_id');
    }
    public function days(){
        return $this->belongsToMany(Day::class , 'menu_category_days' , 'menu_category_id' , 'day_id');
    }

    public function scopeIsShow($query){
        $day = Carbon::now()->format('l');
        $nowTime = Carbon::now()->format('H:i:s');
        return $query->
        leftJoin('menu_category_days' , 'menu_category_days.menu_category_id' , 'menu_categories.id')->
        leftJoin('days' , 'days.id' , 'menu_category_days.day_id')->
        whereRaw('(time = "false" or (days.name_en = "'.$day.'" and start_at <= "'.$nowTime.'" and end_at > "'.$nowTime.'"))')->
        select(DB::raw('distinct menu_categories.id') ,'menu_categories.*' );
    }
    public function isTime(){
        // return true;
        if($this->time == 'false') return true; 
        if($this->time == 'true' and (!empty($this->start_at) or !empty($this->end_at))):
            $startTime = empty($this->start_at) ? null : Carbon::createFromFormat('H:i:s' , $this->start_at);
            $endTime = empty($this->end_at) ? null : Carbon::createFromFormat('H:i:s' , $this->end_at);
            $now = Carbon::createFromFormat('H:i:s' , date('H:i:s')) ;
            $nowDay = Carbon::now()->format('l');
            // check day
            if(!$check = $this->days()->where('name_en' , $nowDay)->first()) return false;



            $checkInSameDay = true;
            if($startTime->greaterThan($endTime)) $checkInSameDay = false;

            if($checkInSameDay == false):
                if($now->greaterThanOrEqualTo($startTime) or $now->lessThanOrEqualTo($endTime)): return true; endif;
                
            else:
                if(!empty($startTime) and !empty($endTime) and $now->greaterThanOrEqualTo($startTime) and $now->lessThan($endTime)) return true;
                // dd($this->start_at . ' : '  . $this->end_at);
            endif;

        endif;
        return false;
    }
}

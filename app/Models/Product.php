<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'restaurant_id',
        'branch_id',
        'menu_category_id',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'price',
        'price_before_discount',
        'calories',
        'arrange',
        'photo',
        'active',
        'poster_id',
        'sub_category_id',
        'start_at',
        'end_at',
        'time',
        'foodics_image',
        'foodics_id',
        'old_id',
        'video_id' , 'video_type'  , 'loyalty_points'
    ];
    public function getVideoLinkAttribute(){
        return empty($this->video_id) ? null : 'https://www.youtube.com/embed/' . $this->video_id;
    }
    public function getImagePathAttribute(){
        if(empty($this->photo)) return $this->restaurant->image_path;
        return 'uploads/products/' . $this->photo;
    }
    public function getNameAttribute(){
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class , 'branch_id');
    }
    public function menu_category()
    {
        return $this->belongsTo(MenuCategory::class , 'menu_category_id');
    }
    public function options()
    {
        return $this->hasMany(ProductOption::class , 'product_id');
    }
    public function sizes()
    {
        return $this->hasMany(ProductSize::class , 'product_id');
    }
    public function poster()
    {
        return $this->belongsTo(RestaurantPoster::class , 'poster_id');
    }
    public function sub_category()
    {
        return $this->belongsTo(RestaurantSubCategory::class , 'sub_category_id');
    }
    
    
    public function scopeIsShow($query){
        $day = Carbon::now()->format('l');
        $nowTime = Carbon::now()->format('H:i:s');
        return $query->
        leftJoin('product_days' , 'product_days.product_id' , 'products.id')->
        leftJoin('days' , 'days.id' , 'product_days.day_id')->
        whereRaw('(time = "false" or (days.name_en = "'.$day.'" and start_at <= "'.$nowTime.'" and end_at > "'.$nowTime.'"))')->
        select(DB::raw('distinct products.id') ,'products.*' );
    }
    public function days(){
        return $this->belongsToMany(Day::class , 'product_days' , 'product_id' , 'day_id');
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
            $checkTheSameDay = true;
            if($startTime->greaterThan($endTime)) $checkTheSameDay = false;

            if($checkTheSameDay == false):
                if($now->greaterThanOrEqualTo($startTime) or $now->lessThanOrEqualTo($endTime)): return true; endif;
                
            else:
                if(!empty($startTime) and !empty($endTime) and $now->greaterThanOrEqualTo($startTime) and $now->lessThan($endTime)) return true;
                // dd($this->start_at . ' : '  . $this->end_at);
            endif;

        endif;
        return false;
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;

class RestaurantAds extends Model
{
    use HasFactory;
    public const types = [
        'main' , 'menu_category'
    ];
    public const contentType = [
        'image' , 'youtube'
    ];
    protected $table = 'restaurant_ads';
    protected $fillable =[
        'restaurant_id' , 'category_id' , 'type' , 'content_type' , 'content' , 'start_date' , 'end_date' , 'time' , 'start_at' , 'end_at' , 'to'
    ];

    public function getImagePathAttribute(){
        return 'uploads/restaurants/ads/' . $this->content;
    }
    public function scopeCheckDate($query){
        return $query->where('start_date' , '<=' , date('Y-m-d'))->where('end_date' , '>' , date('Y-m-d'));
    }
    public function restaurant(){
        return $this->belongsTo(Restaurant::class);
    }
    public function isActive(){
        $start = Carbon::createFromFormat('Y-m-d' , $this->start_date);
        $end = Carbon::createFromFormat('Y-m-d' , $this->end_date);
        $now = Carbon::now();
        if($now->greaterThanOrEqualTo($start) and $now->lessThan($end)) return true;
        return false;
    }
    public function menuCategory(){
        return $this->belongsTo(MenuCategory::class , 'category_id');
    }

    public function isAllow(){
        
        if(Cookie::has('not_allowed_ads')):
            $data = json_decode(Cookie::get('not_allowed_ads') , true);
            if(!empty($data) and is_array($data) and in_array($this->id , $data)) return false;
        endif;
        return true;
    }

    public function whiteList(){
        if(Cookie::has('not_allowed_ads')):
            $data = json_decode(Cookie::get('not_allowed_ads') , true);
            if(!empty($data) and is_array($data) and in_array($this->id , $data)){
                $data = array_diff( $data, [$this->id] );
                
                $response = new Response('Not ads');
                $response->withCookie(cookie('not_allowed_ads' , json_encode($data) , (3*30*24*60)));
                return $data;
            }
        endif;
        return true;
    }
    public function days(){
        return $this->belongsToMany(Day::class , 'restaurant_ads_days' , 'ads_id' , 'day_id');
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

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantOffer extends Model
{
    use HasFactory;
    protected $table = 'restaurant_offers';
    protected $fillable = ['restaurant_id' , 'name' , 'photo' , 'time' , 'start_at' , 'end_at'];

    
    public function getImagePathAttribute(){
        return 'uploads/offers/' . $this->photo ;
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }

    public function days(){
        return $this->belongsToMany(Day::class , 'restaurant_offers_days' , 'offer_id' , 'day_id');
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

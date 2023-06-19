<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantFeedback extends Model
{
    use HasFactory;
    protected $table = 'restaurants_feedback';
    protected $fillable =[
        'name'  , 'mobile' , 'message' , 'eat_rate' , 'place_rate' , 'service_rate' , 'reception_rate' , 'restaurant_id' , 'user_id' , 'speed_rate' , 'worker_rate' ,  'branch_id'
    ];

    public function restaurant(){
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    public function branch(){
        return $this->belongsTo(RestaurantFeedbackBranch::class , 'branch_id');
    }
    public function getAllRateHtml(){
        $content = '';
        foreach(['eat_rate' , 'place_rate' , 'service_rate' , 'reception_rate' , 'speed_rate' , 'worker_rate']  as $key):
            if(!empty($this->attributes[$key])):
                $content .= '
                    <div class="static-rate text-center">
                        <div class="description">'.trans('dashboard._feedback.type.' . $key).'</div>
                        <div class="stars">
                            <i class="fas fa-star " style="'.($this->attributes[$key] >= 1 ? 'color:#f9bf00' : '' ).'"  ></i>
                            <i class="fas fa-star  " style="'.($this->attributes[$key] >= 2 ? 'color:#f9bf00' : '' ).'"  ></i>
                            <i class="fas fa-star " style="'.($this->attributes[$key] >= 3 ? 'color:#f9bf00' : '' ).'"  ></i>
                            <i class="fas fa-star  " style="'.($this->attributes[$key] >= 4 ? 'color:#f9bf00' : '' ).'"  ></i>
                            <i class="fas fa-star  " style="'.($this->attributes[$key] >= 5 ? 'color:#f9bf00' : '' ).'" ></i>
                        </div>
                    </div>
                ';
            endif;
        endforeach;

        return $content;
    }
}

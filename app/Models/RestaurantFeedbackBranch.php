<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RestaurantFeedbackBranch extends Model
{
    use HasFactory;

    protected $table = 'restaurant_feedback_branches';
    protected $fillable = [
        'name_en' , 'name_ar' , 'link' , 'restaurant_id' , 'sort'
    ];

    public function getNameAttribute(){
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }

    public function restaurant(){
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    
}

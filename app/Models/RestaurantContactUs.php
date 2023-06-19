<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantContactUs extends Model
{
    use HasFactory;
    protected $table = 'restaurant_contact_us';
    protected $fillable = [
        'restaurant_id',
        'url',
        'image',
        'sort',
        'title_en' , 'title_ar'  , 'status' , 
        'link_id'
    ];
    public function getTitleAttribute(){
        return app()->getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    public function link(){
        return $this->belongsTo(RestaurantContactUsLink::class , 'link_id');
    }
}

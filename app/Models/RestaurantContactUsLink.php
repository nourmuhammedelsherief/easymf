<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantContactUsLink extends Model
{
    use HasFactory;
    protected $table = 'restaurant_contact_us_links';
    protected $fillable = [
        'restaurant_id',
        'name_ar' , 'name_en' , 'status' , 'barcode'  , 'is_default'
    ];
    public function getNameAttribute(){
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    public function items(){
        return $this->hasMany(RestaurantContactUs::class , 'link_id');
    }
}

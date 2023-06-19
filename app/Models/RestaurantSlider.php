<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantSlider extends Model
{
    use HasFactory;
    protected $table = 'restaurant_sliders';
    protected $fillable = [
        'restaurant_id',
        'photo',
        'type' , 'youtube'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
}

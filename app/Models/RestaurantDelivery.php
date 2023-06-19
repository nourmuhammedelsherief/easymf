<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantDelivery extends Model
{
    use HasFactory;
    protected $table = 'restaurant_deliveries';
    protected $fillable = [
        'restaurant_id',
        'name_ar',
        'name_en',
        'icon',
        'link',
    ];
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
}

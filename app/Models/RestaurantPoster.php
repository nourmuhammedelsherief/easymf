<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantPoster extends Model
{
    use HasFactory;
    protected $table = 'restaurant_posters';
    protected $fillable = [
        'restaurant_id',
        'poster',
        'name_ar',
        'name_en',
    ];
    public function getImagePathAttribute(){
        return 'uploads/posters/' . $this->poster;
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
}

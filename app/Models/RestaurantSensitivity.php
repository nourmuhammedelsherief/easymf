<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantSensitivity extends Model
{
    use HasFactory;
    protected $table = 'restaurant_sensitivities';
    protected $fillable = [
        'restaurant_id',
        'name_ar',
        'name_en',
        'photo',
        'details_ar',
        'details_en',
    ];

    public function getImagePathAttribute(){
        return 'uploads/sensitivities/' . $this->photo ;
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
}

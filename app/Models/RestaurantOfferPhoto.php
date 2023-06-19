<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantOfferPhoto extends Model
{
    use HasFactory;
    protected $table = 'restaurant_offer_photos';
    protected $fillable = [
        'restaurant_offer_id',
        'photo'
    ];
    public function getImagePathAttribute(){
        return 'uploads/offers/' . $this->photo;
    }
    public function restaurant_offer()
    {
        return $this->belongsTo(RestaurantOffer::class , 'restaurant_offer_id');
    }
}

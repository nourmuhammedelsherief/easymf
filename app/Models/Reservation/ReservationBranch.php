<?php

namespace App\Models\Reservation;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationBranch extends Model
{
    use HasFactory;
    protected $table = 'reservation_branches';
    protected $fillable = [
        'name_ar' , 'name_en' , 'status' , 'restaurant_id' , 'location_link'
    ];
    protected $casts = [
        'status' => 'boolean'
    ];

    public function getNameAttribute(){
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }
    public function restaurant(){
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
}

<?php

namespace App\Models\Reservation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationPlace extends Model
{
    use HasFactory;
    protected $table = 'reservation_places';
    protected $fillable = [
        'name_ar' , 'name_en' , 'status' , 'restaurant_id' , 'image'
    ];
    protected $casts = [
        'status' => 'boolean'
    ];
    protected $appends = [
        'name' , 'image_path'
    ];
    public function getImagePathAttribute(){
        return empty($this->image) ? null : 'uploads/reservation_places/' . $this->image;
    }
    public function getNameAttribute(){
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }
    public function restaurant(){
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    public function tables(){
        return $this->hasMany(ReservationTable::class , 'reservation_place_id');
    }
}

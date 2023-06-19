<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $table = 'cities';
    protected $fillable  = [
        'country_id',
        'name_ar',
        'name_en',
        'old_id',
    ];
    protected $appends = ['name'];
    public function getNameAttribute(){
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }
    public function country()
    {
        return $this->belongsTo(Country::class , 'country_id');
    }
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class , 'city_id');
    }
}

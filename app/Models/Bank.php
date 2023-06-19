<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    protected $table = 'banks';
    protected $fillable = [
        'country_id',
        'name_ar',
        'name_en',
        'useful',
        'account_number',
        'IBAN_number',
        'restaurant_id'
    ];

    public function getNameAttribute(){
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }
    public function country()
    {
        return $this->belongsTo(Country::class , 'country_id');
    }

    public function restaurant(){
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
}

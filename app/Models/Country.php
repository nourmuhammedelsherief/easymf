<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $table = 'countries';
    protected $fillable = [
        'name_ar',
        'name_en',
        'currency_ar',
        'currency_en',
        'code',
        'currency_code',
        'riyal_value',
        'flag',
        'active',
    ];
    public function getCurrencyAttribute(){
        return app()->getLocale() == 'ar' ? $this->currency_ar : $this->currency_en;
    }
    public function getFlagPathAttribute(){
        if(!empty($this->flag))
            return 'uploads/flags/' . $this->flag;
        return null;
    }
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class , 'country_id');
    }
    public function cities()
    {
        return $this->hasMany(City::class , 'country_id');
    }
    public function country_packages()
    {
        return $this->hasMany(CountryPackage::class , 'country_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Service extends Model
{
    // note
    use HasFactory;
    protected $fillable  = [
        'name' , 'price' , 'status'  , 'photo' , 'category_id' , 'description_ar' , 'description_en'
    ];
    public function getImagePathAttribute(){
        return 'uploads/services/' . $this->photo;
    }
    public function getDescriptionAttribute(){
        return app()->getLocale() == 'ar' ? $this->description_ar : $this->description_en;
    }
    public function scopeWithPrice($query , $country_id){
        // $query->leftJoin('service_countries' , 'service_countries.service_id' , 'services.id')->select('services.*' , DB::raw('service_countries.price as real_price '))
    }
    public function subscriptions(){
        return $this->hasMany(ServiceSubscription::class , 'service_id');
    }
    public function category(){
        return $this->belongsTo(CategoryService::class , 'category_id');
    }
    public function getRealPrice( $toRiyal= false  , $country_id = null){
        if($country_id != null){
            $dataPrice = $this->prices()->where('country_id' , $country_id)->first();
        }
        

        
        if(isset($dataPrice)):
            return $toRiyal ? ($dataPrice->price / $dataPrice->country->riyal_value ) : $dataPrice->price;
        endif;
        return $this->price;
    }

    public function getRealCurrency(){
        
        if(isset($this->prices[0])):
            return app()->getLocale() == 'ar' ? $this->prices[0]->country->currency_ar :  $this->prices[0]->country->currency_en;
        endif;
        return app()->getLocale() == 'ar' ? Country::find(2)->currency_ar :Country::find(2)->currency_en ;
    }

    public function prices(){
        return $this->hasMany(ServiceCountry::class , 'service_id');
    }
    public function reports()
    {
        return $this->hasMany(Report::class , 'service_id');
    }

}

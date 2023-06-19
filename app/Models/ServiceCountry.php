<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCountry extends Model
{
    use HasFactory;
    protected $table = 'service_countries';
    protected $fillable =[
        'country_id' , 'service_id' , 'price'
    ];

    public function country(){
        return $this->belongsTo(Country::class , 'country_id');
    }

    public function service(){
        return $this->belongsTo(Service::class , 'service_id');
    }
}

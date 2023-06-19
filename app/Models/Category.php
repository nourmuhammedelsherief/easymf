<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = [
        'name_ar',
        'name_en',
        'type'
    ];
    public static function booted(){
        parent::boot();
        static::addGlobalScope('type' , function($query){
            $query->where('type' , '=' , 'restaurant');
        });
    }

    public function getNameAttribute(){
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }

    public function restaurant_categories()
    {
        return $this->hasMany(RestaurantCategory::class , 'category_id');
    }
}

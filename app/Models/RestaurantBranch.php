<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantBranch extends Model
{
    use HasFactory;
    protected $table = 'restaurant_branches';
    protected $fillable = [
        'restaurant_id',
        'name_ar',
        'name_en',
        'link',
    ];

    public function getNameAttribute(){
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
}

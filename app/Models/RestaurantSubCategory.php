<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantSubCategory extends Model
{
    use HasFactory;
    protected $table = 'restaurant_sub_categories';
    protected $fillable = [
        'menu_category_id',
        'name_ar',
        'name_en'
    ];

    public function restaurant_category()
    {
        return $this->belongsTo(MenuCategory::class , 'menu_category_id');
    }
}

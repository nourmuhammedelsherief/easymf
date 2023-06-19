<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantCategory extends Model
{
    use HasFactory;
    protected $table = 'restaurant_categories';
    protected $fillable = [
        'category_id',
        'restaurant_id',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class , 'category_id');
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
}

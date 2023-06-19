<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantColors extends Model
{
    use HasFactory;

    protected $table = 'restaurant_colors';
    protected $fillable = [
        'restaurant_id',
        'main_heads',
        'icons',
        'options_description',
        'background',
        'product_background',
        'category_background',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
}

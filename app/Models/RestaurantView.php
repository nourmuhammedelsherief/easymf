<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantView extends Model
{
    use HasFactory;
    protected $table = 'restaurant_views';
    protected $fillable = [
        'restaurant_id',
        'views',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
}

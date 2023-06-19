<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyPointPrice extends Model
{
    use HasFactory;
    protected $table = 'restaurant_loyalty_points_prices';
    protected $fillable = [
        'restaurant_id', 'price' , 'points'     
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
}

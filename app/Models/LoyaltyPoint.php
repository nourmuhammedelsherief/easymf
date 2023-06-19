<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyPoint extends Model
{
    use HasFactory;
    protected $table = 'restaruant_loyalty_points';
    protected $fillable = [
        'restaurant_id' , 'user_id' , 'amount'   ,
        'type' , // point , balance
    ]; 

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
}

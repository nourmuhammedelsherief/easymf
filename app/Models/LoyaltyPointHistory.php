<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyPointHistory extends Model
{
    use HasFactory;
    protected $table = 'restaurant_loyalty_points_history';
    protected $fillable = [
        'restaurant_id', 'order_id' , 'user_id' , 'points'   ,
         'is_available',  // true , false
         'order_type' , // gold , table
    ]; 

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class , 'order_id');
    }
    public function tableOrder()
    {
        return $this->belongsTo(TableOrder::class , 'order_id');
    }
}

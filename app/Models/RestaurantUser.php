<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantUser extends Model
{
    use HasFactory;
    protected $table = 'restaurant_users';
    protected $fillable = [
        'restaurant_id',
        'user_id',
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

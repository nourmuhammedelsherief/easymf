<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantControl extends Model
{
    use HasFactory;
    protected $table = 'restaurant_controls';
    protected $fillable = [
        'restaurant_id',
        'admin_id',
        'reason',
        'menu',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class , 'admin_id');
    }
}

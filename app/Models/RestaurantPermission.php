<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantPermission extends Model
{
    use HasFactory;
    protected $table = 'restaurant_permissions';
    protected $fillable = [
        'restaurant_id',
        'permission_id',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class , 'permission_id');
    }
}

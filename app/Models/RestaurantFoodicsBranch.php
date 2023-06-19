<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantFoodicsBranch extends Model
{
    use HasFactory;
    protected $table = 'restaurant_foodics_branches';
    protected $fillable = [
        'restaurant_id',
        'branch_id',
        'name_ar',
        'name_en',
        'foodics_id',
        'phone',
        'latitude',
        'longitude',
        'active',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class , 'branch_id');
    }
}

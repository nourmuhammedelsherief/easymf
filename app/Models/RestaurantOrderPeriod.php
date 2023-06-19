<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantOrderPeriod extends Model
{
    use HasFactory;
    protected $table = 'restaurant_order_periods';
    protected $fillable = [
        'restaurant_id',
        'branch_id',
        'setting_id',
        'period',
        'start_at',
        'end_at',
        'type',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class , 'branch_id');
    }
    public function setting()
    {
        return $this->belongsTo(RestaurantOrderSetting::class , 'setting_id');
    }
    public function days()
    {
        return $this->hasMany(RestaurantOrderPeriodDay::class , 'period_id');
    }
}

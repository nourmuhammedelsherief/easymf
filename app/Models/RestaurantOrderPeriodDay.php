<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantOrderPeriodDay extends Model
{
    use HasFactory;
    protected $table = 'restaurant_order_period_days';
    protected $fillable = [
        'period_id',
        'day_id',
    ];

    public function period()
    {
        return $this->belongsTo(RestaurantOrderPeriod::class , 'period_id');
    }
    public function day()
    {
        return $this->belongsTo(Day::class , 'day_id');
    }
}

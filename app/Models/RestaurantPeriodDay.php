<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantPeriodDay extends Model
{
    use HasFactory;
    protected $table = 'restaurant_period_days';
    protected $fillable = [
        'period_id',
        'day_id',
    ];

    public function day()
    {
        return $this->belongsTo(Day::class , 'day_id');
    }
    public function period()
    {
        return $this->belongsTo(RestaurantPeriod::class , 'period_id');
    }
}

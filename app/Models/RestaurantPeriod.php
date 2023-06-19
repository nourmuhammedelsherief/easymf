<?php

namespace App\Models;

use App\Http\Controllers\RestaurantController\BranchController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantPeriod extends Model
{
    use HasFactory;
    protected $table = 'restaurant_periods';
    protected $fillable = [
        'restaurant_id',
        'branch_id',
        'name',
        'start_at',
        'end_at',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class , 'branch_id');
    }
    public function days()
    {
        return $this->hasMany(RestaurantPeriodDay::class , 'period_id');
    }
}

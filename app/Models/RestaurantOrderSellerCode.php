<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantOrderSellerCode extends Model
{
    use HasFactory;
    protected $table = 'restaurant_order_seller_codes';
    protected $fillable = [
        'restaurant_id',
        'branch_id',
        'seller_code',
        'discount_percentage',
        'start_at',
        'end_at',
    ];
    protected $dates = ['start_at' , 'end_at'];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class  , 'restaurant_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class , 'branch_id');
    }
}

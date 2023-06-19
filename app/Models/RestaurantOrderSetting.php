<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantOrderSetting extends Model
{
    use HasFactory;
    protected $table = 'restaurant_order_settings';
    protected $fillable= [
        'restaurant_id',
        'branch_id',
        'order_type',
        'distance',          // for delivery orders distance
        'takeaway_distance', // for takeaway distance
        'previous_distance', // for previous distance
        'delivery_value',
        'receipt_payment', // true , false
        'online_payment',  // true , false
        'payment_company',  // myFatoourah , tap , express
        'online_token',
        'delivery',        // true , false,
        'takeaway',        // true , false
        'previous',        // true , false
        'table',        // true , false
        'whatsapp_number'  ,
        'bank_transfer',
        'merchant_key',
        'express_password',
        'delivery_payment',
        'takeaway_payment',
        'previous_payment',
        'previous_order_type', // delivery , takeaway , both
        'table_payment',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }

    public function branch(){
        return $this->belongsTo(Branch::class , 'branch_id');
    }
}

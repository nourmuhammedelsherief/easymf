<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Utils;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [
        'restaurant_id',
        'branch_id',
        'table_id',
        'user_id',
        'type',
        'payment_method',    // receipt_payment , online_payment , bank_transfer , loyalty_point
        'status',
        'alt_id',
        'previous_type',
        'period_id',
        'day_id',
        'latitude',
        'longitude',
        'notes',
        'order_price',
        'tax',
        'delivery_value',
        'total_price',
        'seller_code_id',
        'discount_value',
        'invoice_id',
        'foodics_order_id',
        'foodics_status' , // integer
        'whatsapp_branch_id' , 
        'whatsapp_number' , 
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class , 'branch_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
    public function whatsappBranch()
    {
        return $this->belongsTo(WhatsappBranch::class , 'whatsapp_branch_id');
    }
    public function table()
    {
        return $this->belongsTo(Table::class , 'table_id');
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class , 'order_id');
    }
    public function seller_code()
    {
        return $this->belongsTo(RestaurantOrderSellerCode::class , 'seller_code_id');
    }
    public function period()
    {
        return $this->belongsTo(RestaurantOrderPeriod::class , 'period_id');
    }
    public function day()
    {
        return $this->belongsTo(Day::class , 'day_id');
    }
}

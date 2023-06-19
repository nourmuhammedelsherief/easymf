<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $table = 'reports';
    protected $fillable = [
        'restaurant_id',
        'branch_id',
        'service_id',
        'seller_code_id',
        'bank_id',
        'amount',
        'discount',
        'status', // enum(registered, subscribed,renewed)
        'type',   // enum(restaurant,branch,service)
        'invoice_id',
        'created_at',
        'transfer_photo',
        'service_subscription_id',
        'tax_value',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';
    protected $fillable = [
        'bearer_token',
        'sender_name',
        'contact_number',
        'tentative_period',
        'data',
        'active_whatsapp_number',
        'technical_support_number',
        'customer_services_number',
        'tax',
        'branch_service_tentative_period',
    ];

    protected $casts = [
        'data' => 'json' ,
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    protected $table = 'histories';
    protected $fillable = [
        'restaurant_id',
        'package_id',
        'branch_id',
        'bank_id',
        'transfer_photo',
        'invoice_id',
        'operation_date',
        'paid_amount',
        'details',
        'payment_type' , // bank , online
        'is_new' ,
        'type' ,
        'service_id',
        'discount_value',
        'tax_value',
    ];

    protected $casts  = [
        'is_new' => 'boolean' ,
    ];
    protected $dates = ['operation_date'];
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    public function package()
    {
        return $this->belongsTo(Package::class , 'package_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class , 'branch_id');
    }
    public function bank()
    {
        return $this->belongsTo(Bank::class , 'bank_id');
    }

    public function service(){
        return $this->belongsTo(Service::class , 'service_id');
    }

}

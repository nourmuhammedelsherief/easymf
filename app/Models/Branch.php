<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $table = 'branches';
    protected $fillable = [
        'restaurant_id',
        'country_id',
        'city_id',
        'name_ar',
        'name_en',
        'email',
        'phone_number',
        'password',
        'latitude',
        'longitude',
        'views',
        'cart',      // Enum ('true' , 'false')
        'main',      // Enum ('true' , 'false')
        'status',    // ENUM('active','not_active','finished')
        'archive',    // ENUM('true','false')
        'name_barcode' , // the name used for barcode
        'foodics_id',    // use for foodics integration
        'foodics_status', // true , false
        'transfer_photo',
        'foodics_request',
        'invoice_id',
        'delivery',
        'previous',
        'takeaway',
        'table',
        'delivery_distance',
        'takeaway_distance',
        'receipt_payment',
        'online_payment',
        'payment_company',  // myFatoourah , tap
        'online_token',
        'total_tax_price',
        'tax_value',
        'tax',
        'state',
        'tax_number',
        'merchant_key',
        'stop_menu',
        'express_password',
        'table_payment'
    ];
    public function getNameAttribute(){
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class ,  'country_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class , 'city_id');
    }
    public function subscription()
    {
        return $this->hasOne(Subscription::class , 'branch_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class , 'branch_id');
    }
    public function histories()
    {
        return $this->hasMany(History::class , 'branch_id');
    }
    public function reports()
    {
        return $this->hasMany(Report::class , 'branch_id');
    }
    public function service_subscriptions()
    {
        return $this->hasMany(ServiceSubscription::class , 'branch_id');
    }
}

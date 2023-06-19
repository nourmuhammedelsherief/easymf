<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerCode extends Model
{
    use HasFactory;
    protected $table = 'seller_codes';
    protected $fillable = [
        'marketer_id',
        'seller_name',
        'permanent',    // true , false
        'active',
        'percentage',
        'code_percentage',
        'commission',
        'start_at',
        'end_at',
        'country_id',
        'type',
        'discount',
        'used_type' , // code , url
        'custom_url'  , 
        'package_id'
    ];

    public function marketer()
    {
        return $this->belongsTo(Marketer::class , 'marketer_id');
    }
    public function operations()
    {
        return $this->hasMany(MarketerOperation::class , 'seller_code_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class , 'country_id');
    }
    public function package()
    {
        return $this->belongsTo(Package::class , 'package_id');
    }
}

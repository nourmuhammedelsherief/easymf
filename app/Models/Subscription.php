<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $table = 'subscriptions';
    protected $fillable = [
        'package_id',
        'restaurant_id',
        'branch_id',     // every branch has subscription
        'seller_code_id',
        'price',
        'tax_value',
        'discount_value',
        'status',              // active , tentative , finished , tentative_finished
        'transfer_photo',
        'end_at',
        'invoice_id',
        'bank_id',
        'payment_type',       // online , bank
        'type',       // restaurant , branch
        'payment',     // true , false
        'is_new'
    ];

    protected $dates = ['end_at'];
    protected $casts = [
        'is_new' => 'boolean' ,
    ];
    public function getImagePathAttribute(){
        return empty($this->transfer_photo) ? 'uploads/transfers/' . $this->transfer_photo : null ;
    }
    public function package()
    {
        return $this->belongsTo(Package::class , 'package_id');
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class , 'branch_id');
    }
    public function seller_code()
    {
        return $this->belongsTo(SellerCode::class , 'seller_code_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSensitivity extends Model
{
    use HasFactory;
    protected $table = 'product_sensitivities';
    protected $fillable = [
        'product_id',
        'sensitivity_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class , 'product_id');
    }
    public function sensitivity()
    {
        return $this->belongsTo(RestaurantSensitivity::class , 'sensitivity_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodicsDiscount extends Model
{
    use HasFactory;
    protected $table = 'foodics_discounts';
    protected $fillable = [
        'branch_id',
        'foodics_id',
        'name_ar',
        'name_en',
        'amount',
        'branches',
        'categories',
        'products',
        'is_percentage',
        'minimum_product_price',
        'minimum_order_price',
        'maximum_amount',
        'is_taxable', // true ,false
        'order_types',
        'associate_to_all_branches',  // true ,false
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class , 'branch_id');
    }
}

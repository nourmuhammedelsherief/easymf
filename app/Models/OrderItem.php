<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $table = 'order_items';
    protected $fillable = [
        'order_id',
        'product_id',
        'product_count',
        'size_id',
        'price',
        'loyalty_points'
    ];
    public function order()
    {
        return $this->belongsTo(Order::class , 'order_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class , 'product_id');
    }
    public function size()
    {
        return $this->belongsTo(ProductSize::class , 'size_id');
    }
    public function order_item_options()
    {
        return $this->hasMany(OrderItemOption::class , 'order_item_id');
    }
}

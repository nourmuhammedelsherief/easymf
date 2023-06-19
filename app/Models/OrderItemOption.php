<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemOption extends Model
{
    use HasFactory;
    protected $table = 'order_item_options';
    protected $fillable = [
        'order_item_id',
        'option_id',
        'option_count',
    ];

    public function order_item()
    {
        return $this->belongsTo(OrderItem::class , 'order_item_id');
    }
    public function option()
    {
        return $this->belongsTo(Option::class , 'option_id');
    }
}

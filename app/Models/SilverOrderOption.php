<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SilverOrderOption extends Model
{
    use HasFactory;
    protected $table = 'silver_order_options';
    protected $fillable = [
        'silver_order_id',
        'option_id',
        'quantity',
        'price',
    ];
    public function silver_order()
    {
        return $this->belongsTo(SilverOrder::class , 'silver_order_id');
    }
    public function option()
    {
        return $this->belongsTo(Option::class , 'option_id');
    }
}

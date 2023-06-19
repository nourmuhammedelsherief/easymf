<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDay extends Model
{
    use HasFactory;

    protected $table = 'product_days';
    protected $fillable = [
        'product_id',
        'day_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class , 'product_id');
    }
    public function day()
    {
        return $this->belongsTo(Day::class , 'day_id');
    }
}

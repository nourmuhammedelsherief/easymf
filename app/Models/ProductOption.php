<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    use HasFactory;
    protected $table = 'product_options';
    protected $fillable = [
        'option_id',
        'product_id',
        'modifier_id',
        'min',
        'max',
    ];
    public function option()
    {
        return $this->belongsTo(Option::class , 'option_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class , 'product_id');
    }
    public function modifier()
    {
        return $this->belongsTo(Modifier::class , 'modifier_id');
    }
}

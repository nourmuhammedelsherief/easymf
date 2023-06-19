<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    use HasFactory;
    protected $table = 'product_sizes';
    protected $fillable = [
        'name_ar',
        'name_en',
        'price',
        'calories',
        'product_id',
    ];
    public function getNameAttribute(){
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }
    public function product()
    {
        return $this->belongsTo(Product::class , 'product_id');
    }
}

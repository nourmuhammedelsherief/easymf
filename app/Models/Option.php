<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    protected $table = 'options';
    protected $fillable = [
        'name_ar',
        'name_en',
        'modifier_id',
        'restaurant_id',
        'is_active',
        'price',
        'calories',
        'foodics_id',
        'old_id',
    ];

    public function getNameAttribute(){
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    public function modifier()
    {
        return $this->belongsTo(Modifier::class , 'modifier_id');
    }
}

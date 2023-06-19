<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modifier extends Model
{
    use HasFactory;
    protected $table = 'modifiers';
    protected $fillable = [
        'name_ar',
        'name_en',
        'restaurant_id',
        'is_ready',
        'old_id',
        'foodics_id',
        'choose' ,     // one , multiple
    ];
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
}

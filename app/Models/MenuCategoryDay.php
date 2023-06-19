<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuCategoryDay extends Model
{
    use HasFactory;
    protected $table = 'menu_category_days';
    protected $fillable = [
        'menu_category_id',
        'day_id',
    ];

    public function menu_category()
    {
        return $this->belongsTo(MenuCategory::class , 'menu_category_id');
    }
    public function day()
    {
        return $this->belongsTo(Day::class , 'day_id');
    }
}

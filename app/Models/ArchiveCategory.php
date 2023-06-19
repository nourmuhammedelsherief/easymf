<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchiveCategory extends Model
{
    use HasFactory;
    protected $table = 'archive_categories';
    protected $fillable = [
        'name_ar',
        'name_en',
    ];


    public function getNameAttribute(){
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class , 'archive_category_id');
    }
}

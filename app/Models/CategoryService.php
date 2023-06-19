<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CategoryService extends Model
{
    // note
    use HasFactory;
    protected $table = 'categories_service';
    protected $fillable  = [
        'name_en' , 'name_ar' 
    ];
    public function getNameAttribute(){
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }

    public function services(){
        return $this->hasMany(Service::class, 'category_id');
    }
}

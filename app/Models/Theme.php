<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Theme extends Model
{
    // note
    use HasFactory;
    protected $fillable  = [
        'name_en' , 'name_ar' , 'status'  , 'path' , 'sort'
    ];
    public function getNameAttribute(){
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }
    public function restaurants(){
        return $this->hasMany(Restaurant::class , 'theme_id');
    }

}

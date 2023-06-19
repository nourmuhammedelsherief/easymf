<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskCategory extends Category
{
    public static function booted(){
        parent::boot();
        static::addGlobalScope('type' , function($query){
            $query->where('type' , '=' , 'task');
        });
    }
    public function tasks(){
        return $this->hasMany(Task::class , 'category_id');
    }
}

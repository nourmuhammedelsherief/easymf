<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminDetail extends Task
{

    public static function booted(){
        static::addGlobalScope('type' , function($query){
            $query->where('type' , 'profile');
        });
    }

}

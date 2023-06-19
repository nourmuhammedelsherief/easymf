<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensitivity extends Model
{
    use HasFactory;
    protected $table = 'sensitivities' ; 
    protected $fillable = [
        'name_ar'  , 'name_en' , 'details_ar' , 'details_en' , 'photo'
    ];

    public function getImagePathAttribute(){
        return 'uploads/static_sensitivities/' . $this->photo; 
    }

}

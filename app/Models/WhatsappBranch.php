<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappBranch extends Model
{
    use HasFactory;
    protected $table = 'whatsapp_branches';
    protected $fillable = [
        'name_ar',
        'name_en',
        'phone',
        'restaurant_id'
    ];
    public function getNameAttribute(){
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }

    public function restaurant(){
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    
}

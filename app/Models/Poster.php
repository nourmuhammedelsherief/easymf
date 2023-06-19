<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Poster extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_en' , 'name_ar' , 'poster'
    ];
    
    // public static function boot(){
    //     parent::boot();
    //     static::deleting(function($data){
    //         if(Storage::disk('public_storage' )->exists($data->image_path)){
    //             Storage::disk('public_storage' )->delete($data->image_path);
    //         }
    //     });
    // }

    
    public function getImagePathAttribute(){
        return 'uploads/static_posters/' . $this->poster;
    }
}



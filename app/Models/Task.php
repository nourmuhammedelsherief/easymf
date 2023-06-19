<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $table = 'tasks';
    protected $fillable = [
        'category_id' , 'employee_id' , 'description' , 'hours_count' , 'admin_id' , 'admin_name'  , 'status' , 'priority' , 'title' , 'worked_at' , 'type'
    ];

    public static function booted(){
        static::addGlobalScope('type' , function($query){
            $query->where('type' , 'task');
        });
    }

    public function category(){
        return $this->belongsTo(TaskCategory::class , 'category_id');
    }
    public function employee(){
        return $this->belongsTo(Admin::class , 'employee_id');
    }
    public function admin(){
        return $this->belongsTo(Admin::class , 'admin_id');
    }
}

<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;
    protected $table = 'logs';
    protected $fillable = [
        'related_id'  , 'url' , 'method' , 'guard' , 'created_at' , 'data' 
    ];
    public $timestamps = false;
    public $casts = [
        'data' => 'json',
        'created_at' => 'datetime',
    ];
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

}

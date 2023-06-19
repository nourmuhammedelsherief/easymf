<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNote extends Model
{
    use HasFactory;
    protected $table = 'admin_notes';
    protected $fillable = [
        'admin_id',
        'restaurant_id',
        'note',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class , 'admin_id');
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
}

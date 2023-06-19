<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Marketer extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    protected $guard = 'marketer';
    protected $table = 'marketers';
    protected $fillable = [
        'name',
        'email',
        'password',
        'balance',
    ];
    protected $hidden = [
        'password',
    ];

    public function seller_codes()
    {
        return $this->hasMany(SellerCode::class , 'marketer_id');
    }

}

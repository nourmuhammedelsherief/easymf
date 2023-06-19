<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientRequest extends Model
{
    use HasFactory;
    protected $table = 'client_requests' ;
    protected $fillable = [
        'name' , 'income' , 'phone' , 'description' , 'archived'
    ];
    protected $casts  = [
        'archived' => 'boolean' 
    ];
    public function notes(){
        return $this->hasMany(ClientRequestNote::class , 'request_id');
    }
}

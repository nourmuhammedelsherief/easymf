<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientRequestNote extends Model
{
    use HasFactory;
    protected $table = 'client_request_notes' ;
    protected $fillable = [
        'description' , 'request_id' ,
    ];

    public function request(){
        return $this->belongsTo(ClientRequest::class , 'request_id');
    }
}

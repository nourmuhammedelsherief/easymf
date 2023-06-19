<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketerTransfer extends Model
{
    use HasFactory;
    protected $table = 'marketer_transfers';
    protected $fillable = [
        'marketer_id',
        'transfer_photo',
        'amount'
    ];
    public function marketer()
    {
        return $this->belongsTo(Marketer::class , 'marketer_id');
    }
}

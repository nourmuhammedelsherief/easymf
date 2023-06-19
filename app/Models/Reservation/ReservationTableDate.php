<?php

namespace App\Models\Reservation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationTableDate extends Model
{
    use HasFactory;
    protected $table = 'reservation_table_dates';
    protected $fillable = [
        'reservation_table_id',
        'date',
    ];

    public function reservation_table()
    {
        return $this->belongsTo(ReservationTable::class , 'reservation_table_id');
    }
}

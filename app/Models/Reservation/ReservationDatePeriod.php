<?php

namespace App\Models\Reservation;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationDatePeriod extends Model
{
    use HasFactory;
    protected $table ='reservation_date_periods';
    protected $fillable = [
        'date_id' , 'period_id' , 'restaurant_id' , 'table_count' , 'date'
    ];

    public function date(){
        return $this->belongsTo(ReservationTableDate::class , 'date_id');
    }

    public function period(){
        return $this->belongsTo(ReservationTablePeriod::class , 'period_id');
    }

    public function restaurant(){
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
}

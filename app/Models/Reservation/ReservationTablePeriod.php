<?php

namespace App\Models\Reservation;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReservationTablePeriod extends Model
{
    use HasFactory;
    protected $table = 'reservation_table_periods';
    protected $fillable = [
        'reservation_table_id',
        'from',
        'to',
        'status',
    ];
    protected $tasts =[
        'to' => 'time' , 
        'from' => 'time' 
    ];
    protected $appends = [
        'to_string' , 'from_string'
    ];
    public function getToStringAttribute(){
        return Carbon::createFromTimestamp(strtotime($this->to))->translatedFormat('h:i A');
    }
    public function getFromStringAttribute(){
        return Carbon::createFromTimestamp(strtotime($this->from))->translatedFormat('h:i A');
    }
    public function order(){
        return $this->hasMany(ReservationOrder::class , 'period_id');
    }
    public function getIsAvailableAttbibe(){
        if($this->order->count() > 0) return false;
        else return true;
    }
    public function scopeOrdersCount($query , $date , $isPaid =false){
        $temp = '';
        if($isPaid) $temp = 'o.status = "paid" and';
        $query->select('reservation_table_periods.*' , DB::raw('(select count(period_id) from  reservation_orders as o where period_id = reservation_table_periods.id and '.$temp.' date = "'.$date.'") as orders_count'));
    }
    public function orders(){
        return $this->hasMany(ReservationOrder::class , 'period_id');
    }
    // public function getCount
    public function table()
    {
        return $this->belongsTo(ReservationTable::class , 'reservation_table_id');
    }
}

<?php

namespace App\Models\Reservation;

use App\Models\Bank;
use App\Models\Restaurant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReservationOrder extends Model
{
    use HasFactory;
    protected $table = 'reservation_orders';
    protected $fillable = [
        'restaurant_id',
        'user_id',
        'reservation_table_id',
        'period_id',
        'date_id',
        'price',
        'tax',
        'total_price',
        'notes',
        'payment_type',   // bank , online
        'bank_id',
        'transfer_photo',
        'invoice_id',
        'num', 
        'status',        // not_paid , paid , completed , canceled
        'date' , 
        'time_from' , 
        'time_to'  ,
        'is_order' , 
        'user_name' , 'user_phone' , 'type' , 'chairs' , 
        'is_confirm' , 
        'online_payment_fees',
        'people_count' , 'reason' ,  'online_payment_type'
    ];
    public $casts = [
        // 'is_confirm' => 'boolean' 
    ];
    
    protected $appends = [
        'to_string' , 'from_string'
    ];
    public function getToStringAttribute(){
        return Carbon::createFromTimestamp(strtotime($this->time_to))->translatedFormat('h:i A');
    }
    public function getFromStringAttribute(){
        return Carbon::createFromTimestamp(strtotime($this->time_from))->translatedFormat('h:i A');
    }
    public function getImagePathAttribute(){
        return 'uploads/transfers/' . $this->transfer_photo;
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    public static function createNum(){
        $max = ReservationOrder::max('id') ;
        
        do {
            $rand = $max + rand(0.1 , 10) * 111;
        } while (ReservationOrder::where('num' , $rand)->count() > 0);
        return $rand;
    }
    public function table()
    {
        return $this->belongsTo(ReservationTable::class , 'reservation_table_id');
    }
    public function period()
    {
        return $this->belongsTo(ReservationTablePeriod::class , 'period_id');
    }
    public function date()
    {
        return $this->belongsTo(ReservationTableDate::class , 'date_id');
    }
    public function bank()
    {
        return $this->belongsTo(Bank::class , 'bank_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function getPaymentStatusHtml($status = null){
        $status = $status == null ? $this->status : $status;
        if($status == 'paid') return '<span class="badge badge-success">'.trans('dashboard._payment_status.' . $status).'</span>';
        if($status == 'completed') return '<span class="badge badge-success">'.trans('dashboard._payment_status.' . $status).'</span>';
        if($status == 'not_paid') return '<span class="badge badge-secondary">'.trans('dashboard._payment_status.' . $status).'</span>';
        if($status == 'canceled') return '<span class="badge badge-danger">'.trans('dashboard._payment_status.' . $status).'</span>';
        return '';
    }
}

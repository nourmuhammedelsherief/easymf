<?php

namespace App\Models\Reservation;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationTable extends Model
{
    use HasFactory , SoftDeletes;
    protected $table = 'reservation_tables';
    protected $fillable = [
        'restaurant_id',
        'reservation_branch_id',
        'reservation_place_id',
        'status',  // available or not belongs to tables count
        'price',
        'people_count',
        'table_count',
        'is_available' ,'type' , 'chair_min' ,'chair_max' , 'image', 
        'title_en' , 'title_ar' , 'description_ar' , 'description_en' , 
    ];
    protected $casts = [
        'is_available' => 'boolean'
    ];
    public function getImagePathAttribute(){
        return empty($this->image) ? null : asset('uploads/reservation_tables/' . $this->image);
    }
    public function getTitleAttribute(){
        return app()->getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
    public function getDescriptionAttribute(){
        return app()->getLocale() == 'ar' ? $this->description_ar : $this->description_en;
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class ,'restaurant_id');
    }
    public function branch()
    {
        return $this->belongsTo(ReservationBranch::class , 'reservation_branch_id');
    }
    public function place()
    {
        return $this->belongsTo(ReservationPlace::class , 'reservation_place_id');
    }
    public function dates()
    {
        return $this->hasMany(ReservationTableDate::class , 'reservation_table_id');
    }
    public function periods()
    {
        return $this->hasMany(ReservationTablePeriod::class , 'reservation_table_id');
    }
    public function orders()
    {
        return $this->hasMany(ReservationOrder::class , 'reservation_table_id');
    }

    public function images(){
        return $this->morphMany(ReservationTableImage::class , 'typeable');
    }

    public function isExpire(){
        $result = false;
        $dates = $this->dates()->where('date' , '>=' , date('Y-m-d'))->get();
        // check all date expired
        if($dates->count() == 0):
            return true;
        endif;
        $periods = $this->periods;
        
        // check is all date full reservation
        if($this->type == 'table'):
            $count = 0;
            foreach($periods as $temp):
                if($temp->orders()->where('is_order' , 1)->count() >= $this->table_count):
                    $count +=1;
                endif;
            endforeach;
            if($count == $periods->count()):
                return true;
            endif;
        elseif($this->type == 'chair'):
            $count = 0;
            foreach($periods as $temp):
                if($temp->orders()->where('is_order' , 1)->sum('chairs') >= $this->chair_max):
                    $count +=1;
                endif;
            endforeach;
            if($count == $periods->count()):
                return true;
            endif;
        elseif($this->type == 'package'):
            $count = 0;
            foreach($periods as $temp):
                if($temp->orders()->where('is_order' , 1)->count() >= $this->chair_max):
                    $count +=1;
                endif;
            endforeach;
            if($count == $periods->count()):
                return true;
            endif;
        endif;

        return false;
    }
}

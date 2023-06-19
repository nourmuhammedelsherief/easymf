<?php

namespace App\Models\Reservation;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationTableImage extends Model
{
    use HasFactory ;
    protected $table = 'reservation_table_images';
    protected $fillable = [
      'typeable_type' , 'typeable_id' , 'path'
    ];
    public function typeable(){
        return $this->morphTo('typeable');
    }
}

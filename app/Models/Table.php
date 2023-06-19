<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;
    protected $table = 'tables';
    protected $fillable = [
        'restaurant_id',
        'branch_id',
        'name_ar',
        'name_en',
        'name_barcode',
        'code',
        'foodics_id',
        'foodics_branch_id',
        'service_id',
    ];
    public function getNameAttribute(){
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class , 'branch_id');
    }
    public function foodics_branch()
    {
        return $this->belongsTo(RestaurantFoodicsBranch::class , 'foodics_branch_id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class , 'service_id');
    }
}

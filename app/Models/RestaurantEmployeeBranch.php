<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantEmployeeBranch extends Model
{
    use HasFactory;
    protected $table = 'restaurant_employee_branches';
    protected $fillable = [
        'employee_id',
        'branch_id',

    ];
    public function employee(){
        return $this->belongsTo(RestaurantEmployee::class , 'employee_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class , 'branch_id');
    }
}

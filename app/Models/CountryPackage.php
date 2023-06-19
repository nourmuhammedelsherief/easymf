<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryPackage extends Model
{
    use HasFactory;
    protected $table = 'country_packages';
    protected $fillable = [
        'country_id',
        'package_id',
        'price',
        'branch_price',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class , 'country_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class , 'package_id');
    }
}

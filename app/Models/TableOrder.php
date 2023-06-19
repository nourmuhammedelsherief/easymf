<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableOrder extends Model
{
    use HasFactory;
    protected $table = 'table_orders';
    protected $fillable = [
        'restaurant_id',
        'branch_id',
        'table_id',
        'seller_code_id',
        'latitude',
        'longitude',
        'discount_value',
        'status',
        'payment_type',   // online , cash
        'alt_id',
        'notes',
        'tax',
        'order_price',
        'total_price',
        'foodics_order_id',
        'foodics_status' , // integer
        'discount_id',
        'ip',
        'invoice_id',
        'payment_status'  , //true , false
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class , 'restaurant_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class , 'branch_id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class , 'table_id');
    }
    public function seller_code()
    {
        return $this->belongsTo(RestaurantOrderSellerCode::class , 'seller_code_id');
    }
    public function foodics_discount()
    {
        return $this->belongsTo(FoodicsDiscount::class , 'discount_id');
    }
    public function order_items()
    {
        return $this->hasMany(TableOrderItem::class , 'table_order_id');
    }

    public function getStatusHtml(){
        $content = '';
        if($this->status == 'new'){
            $content = '<span class="badge badge-info">'.trans('messages._table_order_status.' . $this->status).'</span>';
        }elseif($this->status == 'active'){
            $content = '<span class="badge badge-primary">'.trans('messages._table_order_status.' . $this->status).'</span>';
        }
        elseif($this->status == 'completed'){
            $content = '<span class="badge badge-success">'.trans('messages._table_order_status.' . $this->status).'</span>';
        }elseif($this->status == 'canceled'){
            $content = '<span class="badge badge-danger">'.trans('messages._table_order_status.' . $this->status).'</span>';
        }elseif($this->status == 'on_table'){
            $content = '<span class="badge badge-warning">'.trans('messages._table_order_status.' . $this->status).'</span>';
        }
        elseif($this->status == 'in_reservation'){
            $content = '<span class="badge badge-warning">'.trans('messages._table_order_status.' . $this->status).'</span>';
        }

        return $content;
    }
}

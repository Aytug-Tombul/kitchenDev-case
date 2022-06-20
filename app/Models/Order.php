<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    public $guarded = [];
    public $timestamps = false;



    public function order_items(){
        return $this->hasMany(OrderItem::class , 'order_id');
    }

}

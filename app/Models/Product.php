<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public $guarded = [];//
    public $fillable = ['code','description','cost','quantity_available'];
    public $timestamps = false;

    public function order_items() {
        return $this->hasMany(OrderItem::class);
    }

}

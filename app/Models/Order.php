<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "customers_id",
        "qty",
        "total"
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customers_id');
    }
    public function OrderDetail()
    {
        return $this->hasMany(OrderDetail::class, 'orders_id');
    }


    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'orders_id');
    }


    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class, 'orders_id');
    }
}

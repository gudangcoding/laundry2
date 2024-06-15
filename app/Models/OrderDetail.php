<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class OrderDetail extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        "orders_id",
        "produks_id",
        "qty",
        "harga",
        "subtotal"
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produks_id');
    }
}

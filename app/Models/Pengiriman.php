<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;
    protected $table = "pengiriman";
    protected $fillable = [
        'orders_id',
        'tgl_kirim',
        'nama_pengirim',
        'nama_penerima',
        'status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id');
    }
}

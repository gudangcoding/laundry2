<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "katagori_id",
        "stok",
        "image",
        "harga",
        "deskripsi",
    ];
public function kategori()
{
    return $this->belongsTo(Kategori::class, 'katagori_id');
}

    
}

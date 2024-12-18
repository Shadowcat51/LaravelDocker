<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class produks extends Model
{
    use HasFactory;

    protected $fillable = ['NamaProduk', 'Harga', 'Stok'];
    protected $primaryKey = 'ProdukID';

    protected $guarded = [];

    public function detailpenjualan()
    {
        return $this->hasMany(detailpenjualans::class, 'ProdukID', 'ProdukID');
    }

    public function penjualan()
    {
        return $this->hasMany(penjualans::class);
    }
}

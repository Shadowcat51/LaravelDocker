<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class penjualans extends Model
{
    use HasFactory;

    protected $fillable = ['TotalHarga', 'PelangganID'];
    protected $primaryKey = 'PenjualanID';

    protected static function boot(){
        parent::boot();

        static::creating(function ($penjualan) {
            $penjualan->TanggalPenjualan = now()->toDateString();
        });
    }

    protected $guarded = [];

    public function pelanggan()
    {
        return $this->belongsTo(pelanggans::class, 'PelangganID', 'PelangganID');
    }

    public function produk()
    {
        return $this->belongsTo(produks::class);
    }

    public function detailpenjualan()
    {
        return $this->hasMany(detailpenjualans::class, 'PenjualanID', 'PenjualanID');
    }
}

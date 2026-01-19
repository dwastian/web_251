<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'kodeproduk';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';


    protected $fillable = [
        'kodeproduk',
        'nama',
        'harga',
        'satuan',
        'gambar',
    ];
    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'kodegudang', 'kodegudang');
    }

    public function detailkirim()
    {
        return $this->hasMany(DetailKirim::class, 'kodeproduk', 'kodeproduk');
    }
}

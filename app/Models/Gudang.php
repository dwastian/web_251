<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gudang extends Model
{
    protected $table = 'gudang';

    protected $primaryKey = 'kodegudang';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';

    protected $fillable = [
        'kodegudang',
        'namagudang',
        'alamat',
        'kontak',
        'kapasitas',
    ];

    public function produk()
    {
        return $this->hasMany(Produk::class, 'kodegudang', 'kodegudang');
    }
}

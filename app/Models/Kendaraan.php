<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    protected $table = 'kendaraan';

    protected $primaryKey = 'nopol';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'nopol',
        'namakendaraan',
        'jeniskendaraan',
        'namadriver',
        'kontakdriver',
        'tahun',
        'kapasitas',
        'foto',
    ];

    public function masterkirim()
    {
        return $this->hasMany(MasterKirim::class, 'nopol', 'nopol');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class masterKirim extends Model
{
    protected $table = 'masterkirim';
    protected $primaryKey = 'kodekirim';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kodekirim',
        'tglkirim',
        'nopol',
        'totalqty',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'nopol', 'nopol');
    }

    public function detailkirim()
    {
        return $this->hasMany(DetailKirim::class, 'kodekirim', 'kodekirim');
    }

}

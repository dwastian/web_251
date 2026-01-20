<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKirim extends Model
{
    protected $table = 'masterkirim';

    protected $primaryKey = 'kodekirim';

    public $incrementing = false;

    public $timestamps = true;

    protected $keyType = 'string';

    protected $fillable = [
        'kodekirim',
        'tglkirim',
        'nopol',
        'totalqty',
        'status',
        'catatan',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'nopol', 'nopol');
    }

    public function detailkirim()
    {
        return $this->hasMany(DetailKirim::class, 'kodekirim', 'kodekirim');
    }

    public function getStatusAttribute($value)
    {
        return ucfirst($value);
    }
}

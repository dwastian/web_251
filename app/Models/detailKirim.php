<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailKirim extends Model
{
    protected $table = 'detailkirim';

    protected $primaryKey = 'id';

    public $incrementing = true;

    public $timestamps = true;

    protected $fillable = [
        'kodekirim',
        'kodeproduk',
        'qty',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'kodeproduk', 'kodeproduk');
    }

    public function masterkirim()
    {
        return $this->belongsTo(MasterKirim::class, 'kodekirim', 'kodekirim');
    }
}

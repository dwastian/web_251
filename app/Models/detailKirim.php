<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detailKirim extends Model
{
    protected $table = 'detailkirim';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
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

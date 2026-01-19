@extends('layouts.app')

@section('title','Detail Kirim')

@section('content')

<h5>Detail Pengiriman</h5>

<div class="card mb-3">
    <div class="card-body">
        <b>Kode:</b> {{ $masterkirim->kodekirim }} <br>
        <b>Tanggal:</b> {{ $masterkirim->tglkirim }} <br>
        <b>Kendaraan:</b> {{ $masterkirim->kendaraan->nopol }} <br>
        <b>Total Qty:</b> {{ $masterkirim->totalqty }} <br>
    </div>
</div>

<h5>Tambah Produk</h5>

<form action="{{ route('detailkirim.store') }}" method="POST">
@csrf

<input type="hidden" name="kodekirim" value="{{ $masterkirim->kodekirim }}">

<div class="row">
    <div class="col-md-6">
         <select name="kodeproduk" class="form-control select2" required>
             <option value="">- Pilih Produk -</option>
             @foreach(\App\Models\Produk::orderBy('nama')->get() as $p)
                 <option value="{{ $p->kodeproduk }}">{{ $p->nama }} ({{ $p->satuan }})</option>
             @endforeach
         </select>
         <span class="text-danger">@error('kodeproduk') {{ $message }} @enderror</span>
     </div>
     <div class="col-md-3">
         <input type="number" name="qty" class="form-control" placeholder="Qty" required>
         <span class="text-danger">@error('qty') {{ $message }} @enderror</span>
     </div>
    <div class="col-md-3">
        <button class="btn btn-primary">Tambah</button>
    </div>
</div>

</form>

<hr>

<h5>Detail Produk</h5>

<table class="table table-bordered datatable">
    <thead>
        <tr>
            <th>Produk</th>
            <th>Satuan</th>
            <th>Qty</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    @foreach($masterkirim->detail as $d)
        <tr>
            <td>{{ $d->produk->nama }}</td>
            <td>{{ $d->produk->satuan }}</td>
            <td>{{ $d->qty }}</td>
            <td>
                <form action="{{ route('detailkirim.destroy',$d->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus detail ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@endsection

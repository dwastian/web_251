@extends('layouts.app')

@section('title','Edit Produk')

@section('content')

<form action="{{ route('produk.update',$produk->kodeproduk) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

<div class="mb-3">
    <label>Kode Produk</label>
    <input type="text" name="kodeproduk" class="form-control" value="{{ $produk->kodeproduk }}" required>
    <span class="text-danger">@error('kodeproduk') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Nama Produk</label>
    <input type="text" name="nama" class="form-control" value="{{ $produk->nama }}" required>
    <span class="text-danger">@error('nama') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Satuan</label>
    <input type="text" name="satuan" class="form-control" value="{{ $produk->satuan }}" required>
    <span class="text-danger">@error('satuan') {{ $message }} @enderror</span>
</div>

 <div class="mb-3">
    <label>Harga</label>
    <input type="number" name="harga" class="form-control" value="{{ $produk->harga }}" required>
    <span class="text-danger">@error('harga') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Gudang</label>
    <select name="kodegudang" class="form-control select2" required>
        <option value="">- Pilih Gudang -</option>
        @foreach(\App\Models\Gudang::orderBy('namagudang')->get() as $g)
            <option value="{{ $g->kodegudang }}" {{ $produk->kodegudang == $g->kodegudang ? 'selected' : '' }}>{{ $g->namagudang }}</option>
        @endforeach
    </select>
    <span class="text-danger">@error('kodegudang') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Gambar</label><br>
    @if($produk->gambar)
        <img src="{{ asset('storage/'.$produk->gambar) }}" height="70" class="mb-2">
    @endif
    <input type="file" name="gambar" class="form-control">
    <span class="text-danger">@error('gambar') {{ $message }} @enderror</span>
</div>

<button class="btn btn-primary">Update</button>
<a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>

</form>

@endsection

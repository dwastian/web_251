@extends('layouts.app')

@section('title','Edit Produk')

@section('content')

<form action="{{ route('produk.update',$produk->kodeproduk) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

<div class="mb-3">
    <label>Nama Produk</label>
    <input type="text" name="nama" class="form-control" value="{{ $produk->nama }}" required>
</div>

<div class="mb-3">
    <label>Satuan</label>
    <input type="text" name="satuan" class="form-control" value="{{ $produk->satuan }}" required>
</div>

<div class="mb-3">
    <label>Harga</label>
    <input type="number" name="harga" class="form-control" value="{{ $produk->harga }}" required>
</div>

<div class="mb-3">
    <label>Gambar</label><br>
    @if($produk->gambar)
        <img src="{{ asset('storage/'.$produk->gambar) }}" height="70" class="mb-2">
    @endif
    <input type="file" name="gambar" class="form-control">
</div>

<button class="btn btn-primary">Update</button>
<a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>

</form>

@endsection

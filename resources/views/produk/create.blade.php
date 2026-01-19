@extends('layouts.app')

@section('title','Tambah Produk')

@section('content')

<form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="mb-3">
    <label>Kode Produk</label>
    <input type="text" name="kodeproduk" class="form-control" required>
</div>

<div class="mb-3">
    <label>Nama Produk</label>
    <input type="text" name="nama" class="form-control" required>
</div>

<div class="mb-3">
    <label>Satuan</label>
    <input type="text" name="satuan" class="form-control" required>
</div>

<div class="mb-3">
    <label>Harga</label>
    <input type="number" name="harga" class="form-control" required>
</div>

<div class="mb-3">
    <label>Gambar</label>
    <input type="file" name="gambar" class="form-control">
</div>

<button class="btn btn-primary">Simpan</button>
<a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>

</form>

@endsection

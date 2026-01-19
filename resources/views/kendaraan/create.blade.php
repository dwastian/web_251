@extends('layouts.app')

@section('title','Tambah Kendaraan')

@section('content')

<form action="{{ route('kendaraan.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="mb-3">
    <label>Nopol</label>
    <input type="text" name="nopol" class="form-control" required>
</div>

<div class="mb-3">
    <label>Merk</label>
    <input type="text" name="merk" class="form-control" required>
</div>

<div class="mb-3">
    <label>Jenis</label>
    <input type="text" name="jenis" class="form-control" required>
</div>

<div class="mb-3">
    <label>Tahun</label>
    <input type="number" name="tahun" class="form-control" required>
</div>

<div class="mb-3">
    <label>Kapasitas</label>
    <input type="number" name="kapasitas" class="form-control" required>
</div>

<div class="mb-3">
    <label>Foto</label>
    <input type="file" name="foto" class="form-control">
</div>

<button class="btn btn-primary">Simpan</button>
<a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">Kembali</a>

</form>

@endsection

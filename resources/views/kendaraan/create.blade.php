@extends('layouts.app')

@section('title','Tambah Kendaraan')

@section('content')

<form action="{{ route('kendaraan.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="mb-3">
    <label>Nopol</label>
    <input type="text" name="nopol" class="form-control" required>
    <span class="text-danger">@error('nopol') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Nama Kendaraan</label>
    <input type="text" name="namakendaraan" class="form-control" required>
    <span class="text-danger">@error('namakendaraan') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Jenis Kendaraan</label>
    <input type="text" name="jeniskendaraan" class="form-control" required>
    <span class="text-danger">@error('jeniskendaraan') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Nama Driver</label>
    <input type="text" name="namadriver" class="form-control" required>
    <span class="text-danger">@error('namadriver') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Tahun</label>
    <input type="number" name="tahun" class="form-control" required>
    <span class="text-danger">@error('tahun') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Kapasitas</label>
    <input type="text" name="kapasitas" class="form-control" required>
    <span class="text-danger">@error('kapasitas') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Foto</label>
    <input type="file" name="foto" class="form-control">
    <span class="text-danger">@error('foto') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Nama Kendaraan</label>
    <input type="text" name="namakendaraan" class="form-control" required>
</div>

<div class="mb-3">
    <label>Jenis Kendaraan</label>
    <input type="text" name="jeniskendaraan" class="form-control" required>
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

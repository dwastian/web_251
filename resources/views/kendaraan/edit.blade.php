@extends('layouts.app')

@section('title','Edit Kendaraan')

@section('content')

<form action="{{ route('kendaraan.update',$kendaraan->nopol) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

<div class="mb-3">
    <label>Merk</label>
    <input type="text" name="merk" class="form-control" value="{{ $kendaraan->merk }}" required>
</div>

<div class="mb-3">
    <label>Jenis</label>
    <input type="text" name="jenis" class="form-control" value="{{ $kendaraan->jenis }}" required>
</div>

<div class="mb-3">
    <label>Tahun</label>
    <input type="number" name="tahun" class="form-control" value="{{ $kendaraan->tahun }}" required>
</div>

<div class="mb-3">
    <label>Kapasitas</label>
    <input type="number" name="kapasitas" class="form-control" value="{{ $kendaraan->kapasitas }}" required>
</div>

<div class="mb-3">
    <label>Foto</label><br>
    @if($kendaraan->foto)
        <img src="{{ asset('storage/'.$kendaraan->foto) }}" height="80" class="mb-2">
    @endif
    <input type="file" name="foto" class="form-control">
</div>

<button class="btn btn-primary">Update</button>
<a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">Kembali</a>

</form>

@endsection

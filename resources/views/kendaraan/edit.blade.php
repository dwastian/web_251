@extends('layouts.app')

@section('title','Edit Kendaraan')

@section('content')

<form action="{{ route('kendaraan.update',$kendaraan->nopol) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

<div class="mb-3">
    <label>Nopol</label>
    <input type="text" name="nopol" class="form-control" value="{{ $kendaraan->nopol }}" required>
    <span class="text-danger">@error('nopol') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Nama Kendaraan</label>
    <input type="text" name="namakendaraan" class="form-control" value="{{ $kendaraan->namakendaraan }}" required>
    <span class="text-danger">@error('namakendaraan') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Jenis Kendaraan</label>
    <input type="text" name="jeniskendaraan" class="form-control" value="{{ $kendaraan->jeniskendaraan }}" required>
    <span class="text-danger">@error('jeniskendaraan') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Nama Driver</label>
    <input type="text" name="namadriver" class="form-control" value="{{ $kendaraan->namadriver }}" required>
    <span class="text-danger">@error('namadriver') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Tahun</label>
    <input type="number" name="tahun" class="form-control" value="{{ $kendaraan->tahun }}" required>
    <span class="text-danger">@error('tahun') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Kapasitas</label>
    <input type="text" name="kapasitas" class="form-control" value="{{ $kendaraan->kapasitas }}" required>
    <span class="text-danger">@error('kapasitas') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Foto</label><br>
    @if($kendaraan->foto)
        <img src="{{ asset('storage/'.$kendaraan->foto) }}" height="80" class="mb-2">
    @endif
    <input type="file" name="foto" class="form-control">
    <span class="text-danger">@error('foto') {{ $message }} @enderror</span>
</div>

<button class="btn btn-primary">Update</button>
<a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">Kembali</a>

</form>

@endsection

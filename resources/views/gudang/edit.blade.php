@extends('layouts.app')

@section('title','Edit Gudang')

@section('content')

<form action="{{ route('gudang.update',$gudang->kodegudang) }}" method="POST">
@csrf
@method('PUT')

<div class="mb-3">
    <label>Kode Gudang</label>
    <input type="text" name="kodegudang" class="form-control" value="{{ $gudang->kodegudang }}" required>
    <span class="text-danger">@error('kodegudang') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Nama Gudang</label>
    <input type="text" name="namagudang" class="form-control" value="{{ $gudang->namagudang }}" required>
    <span class="text-danger">@error('namagudang') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Alamat</label>
    <textarea name="alamat" class="form-control">{{ $gudang->alamat }}</textarea>
    <span class="text-danger">@error('alamat') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Kontak</label>
    <input type="text" name="kontak" class="form-control" value="{{ $gudang->kontak }}">
    <span class="text-danger">@error('kontak') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Kapasitas</label>
    <input type="number" name="kapasitas" class="form-control" value="{{ $gudang->kapasitas }}" required>
    <span class="text-danger">@error('kapasitas') {{ $message }} @enderror</span>
</div>

<button class="btn btn-primary">Update</button>
<a href="{{ route('gudang.index') }}" class="btn btn-secondary">Kembali</a>

</form>

@endsection

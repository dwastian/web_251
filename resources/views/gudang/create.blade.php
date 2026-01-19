@extends('layouts.app')

@section('title','Tambah Gudang')

@section('content')

<form action="{{ route('gudang.store') }}" method="POST">
@csrf

<div class="mb-3">
    <label>Kode Gudang</label>
    <input type="text" name="kodegudang" class="form-control" required>
    <span class="text-danger">@error('kodegudang') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Nama Gudang</label>
    <input type="text" name="namagudang" class="form-control" required>
    <span class="text-danger">@error('namagudang') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Alamat</label>
    <textarea name="alamat" class="form-control"></textarea>
    <span class="text-danger">@error('alamat') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Kontak</label>
    <input type="text" name="kontak" class="form-control">
    <span class="text-danger">@error('kontak') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Kapasitas</label>
    <input type="number" name="kapasitas" class="form-control" required>
    <span class="text-danger">@error('kapasitas') {{ $message }} @enderror</span>
</div>

<button class="btn btn-primary">Simpan</button>
<a href="{{ route('gudang.index') }}" class="btn btn-secondary">Kembali</a>

</form>

@endsection

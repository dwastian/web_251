@extends('layouts.app')

@section('title','Tambah Produk')

@section('content')

<form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="mb-3">
    <label>Kode Produk</label>
    <input type="text" name="kodeproduk" class="form-control" required>
    <span class="text-danger">@error('kodeproduk') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Nama Produk</label>
    <input type="text" name="nama" class="form-control" required>
    <span class="text-danger">@error('nama') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Satuan</label>
    <input type="text" name="satuan" class="form-control" required>
    <span class="text-danger">@error('satuan') {{ $message }} @enderror</span>
</div>

 <div class="mb-3">
    <label>Harga</label>
    <input type="number" name="harga" class="form-control" required>
    <span class="text-danger">@error('harga') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Gudang</label>
    <select name="kodegudang" class="form-control select2" required>
        <option value="">- Pilih Gudang -</option>
        @foreach(\App\Models\Gudang::orderBy('namagudang')->get() as $g)
            <option value="{{ $g->kodegudang }}">{{ $g->namagudang }}</option>
        @endforeach
    </select>
    <span class="text-danger">@error('kodegudang') {{ $message }} @enderror</span>
</div>

<div class="mb-3">
    <label>Gambar</label>
    <input type="file" name="gambar" class="form-control">
    <span class="text-danger">@error('gambar') {{ $message }} @enderror</span>
</div>

<button class="btn btn-primary">Simpan</button>
<a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>

</form>

@endsection

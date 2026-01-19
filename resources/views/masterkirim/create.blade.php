@extends('layouts.app')

@section('title','Buat Master Kirim')

@section('content')

<form method="POST" action="{{ route('masterkirim.store') }}">
@csrf

<div class="mb-3">
    <label>Kode Kirim</label>
    <input type="text" name="kodekirim" class="form-control" required>
</div>

<div class="mb-3">
    <label>Tanggal Kirim</label>
    <input type="date" name="tglkirim" class="form-control" required>
</div>

<div class="mb-3">
    <label>Kendaraan</label>
    <select name="nopol" class="form-control select2" required>
        <option value="">- Pilih Kendaraan -</option>
        @foreach($kendaraan as $k)
            <option value="{{ $k->nopol }}">{{ $k->nopol }} - {{ $k->merk }} ({{ $k->kapasitas }})</option>
        @endforeach
    </select>
</div>

<button class="btn btn-primary">Lanjut Detail</button>
<a href="{{ route('masterkirim.index') }}" class="btn btn-secondary">Batal</a>

</form>

@endsection

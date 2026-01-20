@extends('layouts.app')

@section('title','Edit Kendaraan')

@push('styles')
<style>
    .form-group {
        margin-bottom: 1rem;
    }
    .preview-image {
        max-width: 200px;
        max-height: 150px;
        margin-top: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
        padding: 5px;
    }
</style>
@endpush

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
    <label>Kontak Driver</label>
    <input type="text" name="kontakdriver" class="form-control" value="{{ $kendaraan->kontakdriver }}" required>
    <span class="text-danger">@error('kontakdriver') {{ $message }} @enderror</span>
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
    <label>Foto Kendaraan</label><br>
    @if($kendaraan->foto)
        <div class="mb-2">
            <img src="{{ asset('storage/'.$kendaraan->foto) }}" height="80" class="preview-image">
            <br>
            <small class="text-muted">Foto saat ini</small>
        </div>
    @endif
    <input type="file" name="foto" class="form-control" accept="image/*" id="foto-input">
    <small class="text-muted">Kosongkan jika tidak ingin mengubah foto. Format: JPG, PNG, GIF. Maksimal: 2MB</small>
    <span class="text-danger">@error('foto') {{ $message }} @enderror</span>
</div>

<button class="btn btn-primary" id="submit-btn">
    <i class="fa fa-save"></i> Update
</button>
<a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">
    <i class="fa fa-arrow-left"></i> Kembali
</a>

</form>

@push('scripts')
<script>
document.getElementById('foto-input').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
    if (preview) {
        preview.innerHTML = '';
    }
    
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'preview-image';
            if (preview) {
                preview.appendChild(img);
            }
        }
        
        reader.readAsDataURL(e.target.files[0]);
    }
});

document.querySelector('form').addEventListener('submit', function() {
    const submitBtn = document.getElementById('submit-btn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Menyimpan...';
});
</script>
@endpush

@endsection

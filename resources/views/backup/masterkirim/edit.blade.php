@extends('layouts.app')

@section('title', 'Edit Master Kirim')

@section('content')

    <form method="POST" action="{{ route('masterkirim.update', $masterkirim->kodekirim) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Kode Kirim</label>
            <input type="text" name="kodekirim" class="form-control" value="{{ $masterkirim->kodekirim }}" required>
            <span class="text-danger">@error('kodekirim') {{ $message }} @enderror</span>
        </div>

        <div class="mb-3">
            <label>Tanggal Kirim</label>
            <input type="date" name="tglkirim" class="form-control" value="{{ $masterkirim->tglkirim }}" required>
            <span class="text-danger">@error('tglkirim') {{ $message }} @enderror</span>
        </div>

        <div class="mb-3">
            <label>Kendaraan</label>
            <select name="nopol" class="form-control" required>
                <option value="">- Pilih Kendaraan -</option>
                @foreach($kendaraan as $k)
                    <option value="{{ $k->nopol }}" {{ $masterkirim->nopol == $k->nopol ? 'selected' : '' }}>
                        {{ $k->nopol }} - {{ $k->namakendaraan }} ({{ $k->kapasitas }})
                    </option>
                @endforeach
            </select>
            <span class="text-danger">@error('nopol') {{ $message }} @enderror</span>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('masterkirim.show', $masterkirim->kodekirim) }}" class="btn btn-secondary">Kembali</a>

    </form>

@endsection
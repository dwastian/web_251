@extends('layouts.app')

@section('title','Master Kirim')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>Data Master Kirim</h4>
    <a href="{{ route('masterkirim.create') }}" class="btn btn-primary">
        <i class="fa fa-plus"></i> Buat Pengiriman
    </a>
</div>

<table class="table table-bordered datatable">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Tanggal</th>
            <th>Kendaraan</th>
            <th>Total Qty</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    @foreach($masterKirim as $m)
        <tr>
            <td>{{ $m->kodekirim }}</td>
            <td>{{ $m->tglkirim }}</td>
            <td>{{ $m->kendaraan->nopol ?? '-' }}</td>
            <td>{{ $m->totalqty }}</td>
            <td>
                <a href="{{ route('masterkirim.show',$m->kodekirim) }}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i></a>
                <form action="{{ route('masterkirim.destroy',$m->kodekirim) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus master kirim ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@endsection

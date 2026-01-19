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
                <button onclick="confirmDelete('{{ route('masterkirim.destroy',$m->kodekirim) }}')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@endsection

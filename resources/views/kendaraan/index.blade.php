@extends('layouts.app')

@section('title','Kendaraan')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>Data Kendaraan</h4>
    <a href="{{ route('kendaraan.create') }}" class="btn btn-primary">
        <i class="fa fa-plus"></i> Tambah Kendaraan
    </a>
</div>

<table class="table table-bordered datatable">
    <thead>
        <tr>
            <th>Nopol</th>
            <th>Merk</th>
            <th>Jenis</th>
            <th>Tahun</th>
            <th>Kapasitas</th>
            <th>Foto</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    @foreach($kendaraan as $k)
        <tr>
            <td>{{ $k->nopol }}</td>
            <td>{{ $k->merk }}</td>
            <td>{{ $k->jenis }}</td>
            <td>{{ $k->tahun }}</td>
            <td>{{ $k->kapasitas }}</td>
            <td>
                @if($k->foto)
                    <img src="{{ asset('storage/'.$k->foto) }}" height="50">
                @else
                    <span class="text-muted">Tidak ada</span>
                @endif
            </td>
            <td>
                <a href="{{ route('kendaraan.edit',$k->nopol) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                <form action="{{ route('kendaraan.destroy',$k->nopol) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kendaraan ini?')">
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

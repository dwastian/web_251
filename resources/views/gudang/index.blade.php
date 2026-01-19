@extends('layouts.app')

@section('title','Gudang')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>Data Gudang</h4>
    <a href="{{ route('gudang.create') }}" class="btn btn-primary">
        <i class="fa fa-plus"></i> Tambah Gudang
    </a>
</div>

<table class="table table-bordered datatable">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama Gudang</th>
            <th>Kontak</th>
            <th>Kapasitas</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    @foreach($gudang as $g)
        <tr>
            <td>{{ $g->kodegudang }}</td>
            <td>{{ $g->namagudang }}</td>
            <td>{{ $g->kontak }}</td>
            <td>{{ $g->kapasitas }}</td>
            <td>
                <a href="{{ route('gudang.edit',$g->kodegudang) }}" class="btn btn-sm btn-warning">
                    <i class="fa fa-edit"></i>
                </a>
                <button onclick="confirmDelete('{{ route('gudang.destroy',$g->kodegudang) }}')"
                        class="btn btn-sm btn-danger">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@endsection

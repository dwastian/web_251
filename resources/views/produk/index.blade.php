@extends('layouts.app')

@section('title','Produk')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>Data Produk</h4>
    <a href="{{ route('produk.create') }}" class="btn btn-primary">
        <i class="fa fa-plus"></i> Tambah Produk
    </a>
</div>

<table class="table table-bordered datatable">
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th>Satuan</th>
            <th>Harga</th>
            <th>Gudang</th>
            <th>Gambar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    @foreach($produk as $p)
        <tr>
            <td>{{ $p->kodeproduk }}</td>
            <td>{{ $p->nama }}</td>
            <td>{{ $p->satuan }}</td>
            <td>{{ number_format($p->harga,0,',','.') }}</td>
            <td>{{ $p->gudang->namagudang }}</td>
                <td>
                    @if($p->gambar)
                    <img src="{{ asset('storage/'.$p->gambar) }}" height="50">
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td>
                <a href="{{ route('produk.edit',$p->kodeproduk) }}" class="btn btn-warning btn-sm">
                    <i class="fa fa-edit"></i>
                </a>
                <form action="{{ route('produk.destroy',$p->kodeproduk) }}" method="POST" style="display:inline;" class="delete-form" data-item-name="{{ $p->nama }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@endsection

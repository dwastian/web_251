@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Detail Produk</h3>

    <table class="table">
        <tr><th>Kode Produk</th><td>{{ $produk->kodeproduk }}</td></tr>
        <tr><th>Nama</th><td>{{ $produk->nama }}</td></tr>
        <tr><th>Satuan</th><td>{{ $produk->satuan }}</td></tr>
        <tr><th>Harga</th><td>Rp {{ number_format($produk->harga) }}</td></tr>
        <tr>
            <th>Gambar</th>
            <td>
                @if($produk->gambar)
                    <img src="{{ asset('storage/'.$produk->gambar) }}" width="120">
                @else -
                @endif
            </td>
        </tr>
    </table>

    <a href="{{ route('produk.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
</div>
@endsection

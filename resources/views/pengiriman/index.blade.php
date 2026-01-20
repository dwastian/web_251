@extends('layouts.app')

@section('title','Daftar Pengiriman Barang')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Daftar Pengiriman Barang</h4>
    <a href="{{ route('pengiriman.create') }}" class="btn btn-primary">
        <i class="fa fa-plus"></i> Buat Pengiriman Baru
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered datatable">
            <thead>
                <tr>
                    <th>Kode Pengiriman</th>
                    <th>Tanggal Kirim</th>
                    <th>Kendaraan</th>
                    <th>Driver</th>
                    <th>Total Qty</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($pengiriman as $p)
                <tr>
                    <td>
                        <span class="badge bg-primary">{{ $p->kodekirim }}</span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($p->tglkirim)->format('d/m/Y') }}</td>
                    <td>
                        @if($p->kendaraan)
                            {{ $p->kendaraan->nopol }} - {{ $p->kendaraan->namakendaraan }}
                            <br>
                            <small class="text-muted">{{ $p->kendaraan->jeniskendaraan }} ({{ $p->kendaraan->kapasitas }})</small>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($p->kendaraan)
                            {{ $p->kendaraan->namadriver }}
                            <br>
                            <small class="text-muted">{{ $p->kendaraan->kontakdriver }}</small>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-info">{{ $p->totalqty }}</span>
                    </td>
                    <td>
                        @php
                            $statusColor = $p->status == 'draft' ? 'warning' : ($p->status == 'confirmed' ? 'success' : 'secondary');
                        @endphp
                        <span class="badge bg-{{ $statusColor }}">{{ $p->status }}</span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('pengiriman.show',$p->kodekirim) }}" class="btn btn-success btn-sm">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="{{ route('pengiriman.edit',$p->kodekirim) }}" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('pengiriman.destroy',$p->kodekirim) }}" method="POST" style="display:inline;" class="delete-form" data-item-name="pengiriman {{ $p->kodekirim }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
@extends('layouts.app')

@section('title', 'Master Kirim')

@section('content')

    <div class="d-flex justify-content-between mb-3 align-items-center">
        <h4>Data Master Kirim (Riwayat)</h4>
        <div class="btn-group">
            <button type="button" id="btn-bulk-delete" class="btn btn-outline-danger d-none" onclick="bulkDelete()">
                <i class="fa fa-trash me-1"></i> Hapus Terpilih (<span id="selected-count">0</span>)
            </button>
            <a href="{{ route('pengiriman.create') }}" class="btn btn-primary ms-2">
                <i class="fa fa-plus"></i> Buat Pengiriman
            </a>
        </div>
    </div>

    <table class="table table-bordered datatable">
        <thead>
            <tr>
                <th width="40" class="text-center">
                    <input type="checkbox" class="form-check-input" id="select-all">
                </th>
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
                    <td class="text-center">
                        <input type="checkbox" class="form-check-input row-checkbox" value="{{ $m->kodekirim }}">
                    </td>
                    <td>{{ $m->kodekirim }}</td>
                    <td>{{ $m->tglkirim }}</td>
                    <td>{{ $m->kendaraan->nopol ?? '-' }}</td>
                    <td>{{ $m->totalqty }}</td>
                    <td>
                        <a href="{{ route('pengiriman.show', $m->kodekirim) }}" class="btn btn-success btn-sm"><i
                                class="fa fa-eye"></i></a>
                        <a href="{{ route('pengiriman.edit', $m->kodekirim) }}" class="btn btn-warning btn-sm"><i
                                class="fa fa-edit"></i></a>
                        <form action="{{ route('pengiriman.destroy', $m->kodekirim) }}" method="POST" style="display:inline;"
                            class="delete-form" data-item-name="pengiriman {{ $m->kodekirim }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @push('scripts')
        <script>
            $(document).ready(function () {
                let selectedIds = new Set();

                $('#select-all').on('click', function () {
                    const isChecked = $(this).prop('checked');
                    $('.row-checkbox').prop('checked', isChecked);
                    updateSelectedIds();
                });

                $(document).on('click', '.row-checkbox', function () {
                    updateSelectedIds();
                    const allChecked = $('.row-checkbox').length === $('.row-checkbox:checked').length;
                    $('#select-all').prop('checked', allChecked);
                });

                function updateSelectedIds() {
                    selectedIds.clear();
                    $('.row-checkbox:checked').each(function () {
                        selectedIds.add($(this).val());
                    });

                    const count = selectedIds.size;
                    $('#selected-count').text(count);
                    if (count > 0) {
                        $('#btn-bulk-delete').removeClass('d-none');
                    } else {
                        $('#btn-bulk-delete').addClass('d-none');
                    }
                }

                window.bulkDelete = function () {
                    const ids = Array.from(selectedIds);
                    if (ids.length === 0) return;

                    if (confirm(`Apakah Anda yakin ingin menghapus ${ids.length} pengiriman terpilih?`)) {
                        fetch('/api/pengiriman/bulk-delete', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ ids: ids })
                        })
                            .then(async res => {
                                const data = await res.json();
                                if (!res.ok) throw new Error(data.message || 'Gagal menghapus data.');
                                return data;
                            })
                            .then(data => {
                                alert(data.message);
                                location.reload();
                            })
                            .catch(err => {
                                console.error(err);
                                alert(err.message);
                            });
                    }
                };
            });
        </script>
    @endpush

@endsection
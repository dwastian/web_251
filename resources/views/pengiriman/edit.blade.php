@extends('layouts.app')

@section('title', 'Edit Pengiriman')

@push('styles')
    <style>
        .driver-info {
            background: #e8f5e8;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }

        .driver-info h6 {
            color: #155724;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .detail-section {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            margin-top: 20px;
        }

        .product-select {
            margin-bottom: 15px;
        }

        .add-product-section {
            background: #ffffff;
            border: 2px dashed #007bff;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }

        .detail-table {
            margin-top: 20px;
        }

        .product-row {
            transition: background-color 0.3s;
        }

        .product-row:hover {
            background-color: #f8f9fa;
        }

        .qty-input {
            width: 80px;
            text-align: center;
        }

        .status-badge {
            font-size: 12px;
            padding: 4px 8px;
        }

        .new-product-row {
            animation: slideDownFade 0.4s ease-out;
        }

        @keyframes slideDownFade {
            0% {
                transform: translateY(-20px);
                opacity: 0;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .highlight-merge {
            background-color: #fff3cd !important;
            animation: pulseMerge 1.5s ease-in-out;
        }

        @keyframes pulseMerge {

            0%,
            100% {
                background-color: #fff3cd;
            }

            50% {
                background-color: #ffeaa7;
            }
        }
    </style>
@endpush

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center gap-3">
            <h4 class="mb-0">Edit Pengiriman: {{ $pengiriman->kodekirim }}</h4>
            <span
                class="status-badge badge bg-{{ $pengiriman->status == 'draft' ? 'warning' : ($pengiriman->status == 'Confirmed' ? 'success' : 'secondary') }}">
                {{ $pengiriman->status }}
            </span>
        </div>
        <a href="{{ route('pengiriman.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form id="pengiriman-form">

        <!-- Informasi Pengiriman -->
        <div class="card mb-5">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa fa-info-circle"></i> Informasi Pengiriman</h5>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 space-y-3">
                    <div class="">
                        <label>Kode Pengiriman</label>
                        <input type="text" name="kodekirim" class="form-control" value="{{ $pengiriman->kodekirim }}"
                            readonly>
                        <span class="text-danger">@error('kodekirim')
                            {{ $message }}
                        @enderror</span>
                    </div>
                    <div class="">
                        <label>Tanggal Kirim</label>
                        <input type="date" name="tglkirim" class="form-control" value="{{ $pengiriman->tglkirim }}" {{ $pengiriman->status == 'Confirmed' ? 'readonly' : 'required' }}>
                        <span class="text-danger">@error('tglkirim')
                            {{ $message }}
                        @enderror</span>
                    </div>
                    <div class="">
                        <label>Pilih Kendaraan</label>
                        <select name="nopol" class="form-control" id="nopol-select" {{ $pengiriman->status == 'Confirmed' ? 'disabled' : 'required' }}>
                            <option value="">- Pilih Kendaraan -</option>
                            @foreach ($kendaraan as $k)
                                <option value="{{ $k->nopol }}" data-capacity="{{ $k->kapasitas }}" data-unit="{{ $k->satuan }}"
                                    {{ $pengiriman->nopol == $k->nopol ? 'selected' : '' }}>
                                    {{ $k->nopol }} - {{ $k->namakendaraan }} ({{ $k->kapasitas }} {{ $k->satuan }})
                                </option>
                            @endforeach
                        </select>
                        <span class="text-danger">@error('nopol')
                            {{ $message }}
                        @enderror</span>
                    </div>
                    <div class="">
                        <label>Nama Driver</label>
                        <input type="text" name="namadriver" id="namadriver" class="form-control"
                            value="{{ $pengiriman->kendaraan->namadriver ?? '-' }}" readonly disabled>
                    </div>
                </div>

                <!-- Driver Info (Auto Display) -->
                <div id="driver-info" class="driver-info">
                    <h6><i class="fa fa-user"></i> Informasi Driver</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Nama Driver:</strong> <span
                                id="driver-name">{{ $pengiriman->kendaraan->namadriver ?? '-' }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Kontak Driver:</strong> <span
                                id="driver-contact">{{ $pengiriman->kendaraan->kontakdriver ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <strong>Jenis Kendaraan:</strong> <span
                                id="vehicle-type">{{ $pengiriman->kendaraan->jeniskendaraan ?? '-' }}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Tahun:</strong> <span
                                id="vehicle-year">{{ $pengiriman->kendaraan->tahun ?? '-' }}</span>
                        </div>
                        <div class="col-md-3">
                            <strong>Kapasitas:</strong> <span
                                id="vehicle-capacity">{{ $pengiriman->kendaraan->kapasitas ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <label>Catatan</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan pengiriman..."
                            {{ $pengiriman->status == 'Confirmed' ? 'readonly' : '' }}>{{ old('catatan', $pengiriman->catatan) }}</textarea>
                        <span class="text-danger">@error('catatan') {{ $message }} @enderror</span>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Detail Item Pengiriman -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fa fa-info-circle"></i> Detail Pengiriman</h5>
        </div>
        <div class="card-body">
            @if ($pengiriman->status == 'Confirmed')
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i>
                    Pengiriman yang sudah dikonfirmasi tidak dapat diubah. Produk tidak dapat ditambahkan atau dihapus.
                </div>
            @endif
            <div class="table-responsive detail-table">
                <table class="table border border-slate-200">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Nama Produk</th>
                            <th>Satuan</th>
                            <th>Kuantitas Kirim</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="product-rows">
                        <!-- Items will be populated by JS -->
                    </tbody>
                    <tfoot>
                        <tr class="bg-light fw-bold">
                            <td colspan="3" class="text-end">Total Kuantitas Kirim:</td>
                            <td class="text-center"><span id="total-qty-display">0</span></td>
                            <td></td>
                        </tr>
                        <tr id="capacity-warning-row" class="table-danger d-none">
                            <td colspan="5" class="text-center">
                                <i class="fa fa-exclamation-triangle"></i>
                                <strong>Peringatan!</strong> Total kuantitas (<span id="warn-total-qty">0</span>)
                                melebihi kapasitas kendaraan (<span id="warn-capacity">0</span> <span
                                    id="warn-unit"></span>).
                            </td>
                        </tr>
                        @if ($pengiriman->status != 'Confirmed')
                            <tr>
                                <td colspan="5">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-muted small">
                                            Kapasitas Kendaraan: <span id="vehicle-capacity-info">-</span>
                                        </div>
                                        <button type="button" id="btn-add-row" onclick="addProdukRow()"
                                            class="btn btn-success btn-sm">
                                            <i class="fa fa-plus"></i> Tambah Baris
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4 gap-2">
        @if ($pengiriman->status != 'Confirmed')
            <button type="button" id="btn-save-draft" class="btn btn-warning btn-lg">
                <i class="fa fa-save"></i> Simpan Draft
            </button>
            <button type="button" id="btn-confirm-save" class="btn btn-success btn-lg">
                <i class="fa fa-check"></i> Konfirmasi & Simpan
            </button>
            <button type="button" id="btn-cancel" class="btn btn-danger btn-lg">
                <i class="fa fa-times"></i> Batal
            </button>
        @else
            <button type="button" id="btn-revert-draft" class="btn btn-warning btn-lg">
                <i class="fa fa-undo"></i> Kembali ke Draft
            </button>
            <button type="button" id="btn-delete-confirmed" class="btn btn-danger btn-lg">
                <i class="fa fa-trash"></i> Hapus Pengiriman
            </button>
        @endif
        <a href="{{ route('pengiriman.index') }}" class="btn btn-secondary btn-lg">
            <i class="fa fa-list"></i> Lihat Daftar
        </a>
    </div>

    @push('scripts')
        <script>
            function getProductInfo(selectElement) {
                const kodeproduk = selectElement.value;
                const row = $(selectElement).closest('tr');

                if (kodeproduk) {
                    // Check duplicate
                    let duplicateFound = false;
                    const qtyToAdd = parseInt(row.find('input[name="kuantitas[]"]').val()) || 1;

                    $('#product-rows tr').not(row).each(function () {
                        const existingSelect = $(this).find('select[name="produk[]"]');
                        if (existingSelect.val() === kodeproduk) {
                            const existingQtyInput = $(this).find('input[name="kuantitas[]"]').first(); // Use .first() to ensure we get the correct input
                            const currentQty = parseInt(existingQtyInput.val()) || 0;
                            existingQtyInput.val(currentQty + qtyToAdd);

                            $(this).addClass('highlight-merge');
                            setTimeout(() => $(this).removeClass('highlight-merge'), 1500);

                            row.remove();
                            duplicateFound = true;
                            return false; // Break out of .each loop
                        }
                    });

                    if (!duplicateFound) {
                        $.get('/api/produk/get-produk/' + kodeproduk)
                            .done(function (d) {
                                const data = d.data;
                                row.find('input[name="nama[]"]').val(data.nama);
                                row.find('input[name="satuan[]"]').val(data.satuan);
                            })
                            .fail(function () {
                                alert('Gagal memuat informasi produk.');
                            });
                    }
                } else {
                    row.find('input[name="nama[]"]').val('');
                    row.find('input[name="satuan[]"]').val('');
                }
            }

            function removeProdukRow(btn) {
                $(btn).closest('tr').remove();
                validateCapacity();
            }

            function validateCapacity() {
                let totalQty = 0;
                $('input[name="kuantitas[]"]').each(function () {
                    totalQty += parseInt($(this).val()) || 0;
                });

                $('#total-qty-display').text(totalQty);

                const selectedVehicle = $('#nopol-select option:selected');
                const capacity = parseInt(selectedVehicle.data('capacity')) || 0;
                const unit = selectedVehicle.data('unit') || '';

                if (capacity > 0) {
                    $('#vehicle-capacity-info').text(`${capacity} ${unit}`);
                    if (totalQty >= capacity) {
                        $('#capacity-warning-row').removeClass('d-none');
                        $('#warn-total-qty').text(totalQty);
                        $('#warn-capacity').text(capacity);
                        $('#warn-unit').text(unit);
                        $('#btn-add-row').prop('disabled', true);

                        if (totalQty > capacity) {
                            $('#btn-confirm-save, #btn-save-draft').prop('disabled', true);
                        } else {
                            $('#btn-confirm-save, #btn-save-draft').prop('disabled', false);
                        }
                    } else {
                        $('#capacity-warning-row').addClass('d-none');
                        $('#btn-add-row').prop('disabled', false);
                        $('#btn-confirm-save, #btn-save-draft').prop('disabled', false);
                    }
                } else {
                    $('#vehicle-capacity-info').text('-');
                    $('#capacity-warning-row').addClass('d-none');
                    $('#btn-add-row').prop('disabled', false);
                    $('#btn-confirm-save, #btn-save-draft').prop('disabled', false);
                }
            }

            $(document).on('input', 'input[name="kuantitas[]"]', function () {
                validateCapacity();
            });

            function addProdukRow(item = null) {
                // Check current capacity before adding manually
                if (!item) {
                    const selectedVehicle = $('#nopol-select option:selected');
                    const capacity = parseInt(selectedVehicle.data('capacity')) || 0;
                    let totalQty = 0;
                    $('input[name="kuantitas[]"]').each(function () {
                        totalQty += parseInt($(this).val()) || 0;
                    });

                    if (capacity > 0 && totalQty >= capacity) {
                        alert('Kapasitas kendaraan sudah tercapai!');
                        return;
                    }
                }
                const isConfirmed = "{{ $pengiriman->status }}" === 'Confirmed';
                const newRow = `
                                                                                                                                <tr class="product-row new-product-row">
                                                                                                                                    <td>
                                                                                                                                        <select name="produk[]" class="form-control product-select" onchange="getProductInfo(this)" ${isConfirmed ? 'disabled' : 'required'}>
                                                                                                                                            <option value="">- Pilih Produk -</option>
                                                                                                                                            @foreach (\App\Models\Produk::orderBy('nama')->get() as $p)
                                                                                                                                                <option value="{{ $p->kodeproduk }}" ${item && item.kodeproduk == '{{ $p->kodeproduk }}' ? 'selected' : ''}>
                                                                                                                                                    {{ $p->kodeproduk }} - {{ $p->nama }}
                                                                                                                                                </option>
                                                                                                                                            @endforeach
                                                                                                                                        </select>
                                                                                                                                    </td>
                                                                                                                                    <td>
                                                                                                                                        <input type="text" name="nama[]" class="form-control" value="${item ? item.produk.nama : ''}" readonly>
                                                                                                                                    </td>
                                                                                                                                    <td>
                                                                                                                                        <input type="text" name="satuan[]" class="form-control" value="${item ? item.produk.satuan : ''}" readonly>
                                                                                                                                    </td>
                                                                                                                                    <td>
                                                                                                                                        <input type="number" name="kuantitas[]" class="form-control" min="1" value="${item ? item.qty : 1}" ${isConfirmed ? 'readonly' : 'required'}>
                                                                                                                                    </td>
                                                                                                                                    <td>
                                                                                                                                        ${!isConfirmed ? `
                                                                                                                                            <button type="button" onclick="removeProdukRow(this)" class="btn btn-danger btn-sm remove-product-btn">
                                                                                                                                                <i class="fa fa-trash"></i> Hapus
                                                                                                                                            </button>
                                                                                                                                        ` : '-'}
                                                                                                                                    </td>
                                                                                                                                </tr>
                                                                                                                            `;
                $('#product-rows').append(newRow);
            }

            $(document).ready(function () {
                // Populate existing items
                const existingItems = @json($pengiriman->detailkirim->load('produk'));
                if (existingItems.length > 0) {
                    existingItems.forEach(item => addProdukRow(item));
                }
                validateCapacity();

                // Vehicle selection
                $('#nopol-select').on('change', function () {
                    const nopol = $(this).val();
                    validateCapacity();
                    if (nopol) {
                        $.get('/api/pengiriman/get-vehicle-info/' + nopol)
                            .done(function (d) {
                                const data = d.data;
                                if (data.namadriver) {
                                    $('#namadriver').val(data.namadriver);
                                }
                            });
                    } else {
                        $('#namadriver').val('');
                    }
                });

                // Save actions
                $('#btn-save-draft, #btn-confirm-save').on('click', function () {
                    const action = $(this).attr('id') === 'btn-save-draft' ? 'save_draft' : 'confirm_save';
                    const btnText = $(this).html();
                    const btn = $(this);

                    if (action === 'confirm_save' && !confirm('Apakah Anda yakin ingin mengonfirmasi pengiriman ini?')) {
                        return;
                    }

                    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');

                    const formData = {
                        kodekirim: $('input[name="kodekirim"]').val(),
                        tglkirim: $('input[name="tglkirim"]').val(),
                        nopol: $('#nopol-select').val(),
                        action: action,
                        produk: [],
                        kuantitas: []
                    };
                    $('#product-rows tr').each(function () {
                        const p = $(this).find('select[name="produk[]"]').val();
                        const q = $(this).find('input[name="kuantitas[]"]').val();
                        if (p) {
                            formData.produk.push(p);
                            formData.kuantitas.push(q);
                        }
                    });

                    fetch('/api/pengiriman/update/{{ $pengiriman->kodekirim }}', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(formData)
                    })
                        .then(res => res.json().then(data => ({ status: res.status, body: data })))
                        .then(res => {
                            if (res.status >= 200 && res.status < 300) {
                                window.location.href = '/pengiriman';
                            } else {
                                btn.prop('disabled', false).html(btnText);
                                alert(res.body.message || 'Gagal menyimpan pengiriman.');
                            }
                        })
                        .catch(err => {
                            btn.prop('disabled', false).html(btnText);
                            alert('Kesalahan jaringan.');
                        });
                });

                // Cancel action
                $('#btn-cancel').on('click', function () {
                    if (confirm('Apakah Anda yakin ingin membatalkan? Pengiriman draft akan dihapus.')) {
                        fetch('/api/pengiriman/{{ $pengiriman->kodekirim }}/save-pengiriman', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ action: 'cancel' })
                        })
                            .then(() => window.location.href = '/pengiriman');
                    }
                });

                // Revert to Draft action
                $('#btn-revert-draft').on('click', function () {
                    if (confirm('Apakah Anda yakin ingin mengembalikan pengiriman ini ke status Draft?')) {
                        const btn = $(this);
                        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');

                        fetch('/api/pengiriman/update-status/{{ $pengiriman->kodekirim }}/revert-to-draft', {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                            .then(res => res.json())
                            .then(data => {
                                alert(data.message);
                                window.location.reload();
                            })
                            .catch(err => {
                                btn.prop('disabled', false).html('<i class="fa fa-undo"></i> Kembali ke Draft');
                                alert('Gagal mengubah status.');
                            });
                    }
                });

                // Delete Confirmed action
                $('#btn-delete-confirmed').on('click', function () {
                    if (confirm('Apakah Anda yakin ingin menghapus pengiriman yang sudah dikonfirmasi ini? Tindakan ini tidak dapat dibatalkan.')) {
                        const btn = $(this);
                        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menghapus...');

                        fetch('/api/pengiriman/{{ $pengiriman->kodekirim }}', {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                            .then(res => res.json())
                            .then(data => {
                                alert(data.message);
                                window.location.href = '/pengiriman';
                            })
                            .catch(err => {
                                btn.prop('disabled', false).html('<i class="fa fa-trash"></i> Hapus Pengiriman');
                                alert('Gagal menghapus pengiriman.');
                            });
                    }
                });
            });
        </script>
    @endpush

@endsection
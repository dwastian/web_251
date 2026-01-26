@extends('layouts.app')

@section('title', 'Buat Pengiriman Baru')

@push('styles')
    <style>
        .driver-info {
            background: #e8f5e8;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            display: none;
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
        <h4>Buat Pengiriman Baru</h4>
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
                        <input type="text" name="kodekirim" class="form-control" value="{{ old('kodekirim') }}"
                            placeholder="Contoh: KIR2024010001" required>
                        <span class="text-danger">
                            @error('kodekirim')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>
                    <div class="">
                        <label>Tanggal Kirim</label>
                        <input type="date" name="tglkirim" class="form-control" value="{{ old('tglkirim') }}" required>
                        <span class="text-danger">
                            @error('tglkirim')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>
                    <div class="">
                        <label>Pilih Kendaraan</label>
                        <select name="nopol" class="form-control" id="nopol-select" required>
                            <option value="">- Pilih Kendaraan -</option>
                            @foreach ($kendaraan as $k)
                                <option value="{{ $k->nopol }}" data-capacity="{{ $k->kapasitas }}" data-unit="{{ $k->satuan }}"
                                    {{ old('nopol') == $k->nopol ? 'selected' : '' }}>
                                    {{ $k->nopol }} - {{ $k->namakendaraan }} ({{ $k->kapasitas }} {{ $k->satuan }})
                                </option>
                            @endforeach
                        </select>
                        <span class="text-danger">
                            @error('nopol')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>
                    <div class="">
                        <label>Nama Driver</label>
                        <input type="text" name="namadriver" id="namadriver" class="form-control" readonly disabled>
                    </div>
                </div>


            </div>
        </div>

        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fa fa-info-circle"></i> Detail Pengiriman</h5>
            </div>
            <div class="card-body">
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
                        <tr>
                            <td colspan="5" class="">
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
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="flex items-center justify-center mt-4 gap-2">
            <button type="submit" name="submit_form" value="create" class="btn btn-primary btn-lg">
                <i class="fa fa-save"></i> Buat Pengiriman
            </button>
            <a href="{{ route('pengiriman.index') }}" class="btn btn-secondary btn-lg">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>

    @push('scripts')
        <script>
            function getProductInfo(selectElement) {
                const kodeproduk = selectElement.value;
                const row = $(selectElement).closest('tr');

                if (kodeproduk) {
                    // Check if this product is already selected in another row
                    let duplicateFound = false;
                    const qtyToAdd = parseInt(row.find('input[name="kuantitas[]"]').val()) || 1;

                    $('#product-rows tr').not(row).each(function () {
                        const existingSelect = $(this).find('select[name="produk[]"]');
                        if (existingSelect.val() === kodeproduk) {
                            // Found duplicate - merge quantities
                            const existingQtyInput = $(this).find('input[name="kuantitas[]"]');
                            const currentQty = parseInt(existingQtyInput.val()) || 0;
                            existingQtyInput.val(currentQty + qtyToAdd);

                            // Highlight the merged row
                            $(this).addClass('highlight-merge');
                            setTimeout(() => $(this).removeClass('highlight-merge'), 1500);

                            // Remove the current row since we merged
                            row.remove();

                            duplicateFound = true;
                            return false; // break out of each loop
                        }
                    });

                    if (!duplicateFound) {
                        // No duplicate found, load product info
                        $.get('/api/produk/get-produk/' + kodeproduk)
                            .done(function (data) {
                                row.find('input[name="nama[]"]').val(data.data.nama);
                                row.find('input[name="satuan[]"]').val(data.data.satuan);
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
                            $('button[type="submit"]').prop('disabled', true);
                        } else {
                            $('button[type="submit"]').prop('disabled', false);
                        }
                    } else {
                        $('#capacity-warning-row').addClass('d-none');
                        $('#btn-add-row').prop('disabled', false);
                        $('button[type="submit"]').prop('disabled', false);
                    }
                } else {
                    $('#vehicle-capacity-info').text('-');
                    $('#capacity-warning-row').addClass('d-none');
                    $('#btn-add-row').prop('disabled', false);
                    $('button[type="submit"]').prop('disabled', false);
                }
            }

            $(document).on('input', 'input[name="kuantitas[]"]', function () {
                validateCapacity();
            });

            function addProdukRow() {
                // Check current capacity before adding
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
                // Always add a new row at the top - users can select products and quantities as needed
                const newRow = `
                                                                                                                                                            <tr class="product-row new-product-row">
                                                                                                                                                                <td>
                                                                                                                                                                    <select name="produk[]" class="form-control product-select" onchange="getProductInfo(this)" required>
                                                                                                                                                                        <option value="">- Pilih Produk -</option>
                                                                                                                                                                        @foreach ($produk as $p)
                                                                                                                                                                            <option value="{{ $p->kodeproduk }}" data-nama="{{ $p->nama }}" data-satuan="{{ $p->satuan }}">
                                                                                                                                                                                {{ $p->kodeproduk }} - {{ $p->nama }}
                                                                                                                                                                            </option>
                                                                                                                                                                        @endforeach
                                                                                                                                                                    </select>
                                                                                                                                                                </td>
                                                                                                                                                                <td>
                                                                                                                                                                    <input type="text" name="nama[]" class="form-control" readonly>
                                                                                                                                                                </td>
                                                                                                                                                                <td>
                                                                                                                                                                    <input type="text" name="satuan[]" class="form-control" readonly>
                                                                                                                                                                </td>
                                                                                                                                                                <td>
                                                                                                                                                                    <input type="number" name="kuantitas[]" class="form-control" min="1" value="1" required>
                                                                                                                                                                </td>
                                                                                                                                                                <td>
                                                                                                                                                                        <button type="button" onclick="removeProdukRow(this)" class="btn btn-danger btn-sm remove-product-btn">
                                                                                                                                                                            <i class="fa fa-trash"></i> Hapus
                                                                                                                                                                        </button>
                                                                                                                                                                    </td>
                                                                                                                                                            </tr>
                                                                                                                                                        `;
                // Insert at the top instead of bottom
                $('#product-rows').prepend(newRow);
            }

            $(document).ready(function () {
                // Vehicle selection handler
                $('#nopol-select').on('change', function () {
                    const nopol = $(this).val();
                    validateCapacity();

                    if (nopol) {
                        // AJAX get vehicle info
                        $.get('/api/pengiriman/get-vehicle-info/' + nopol)
                            .done(function (data) {
                                if (data.data.namadriver) {
                                    $('#namadriver').val(data.data.namadriver);
                                } else {

                                }
                            })
                            .fail(function () {
                                alert('Gagal memuat informasi kendaraan.');
                            });
                    } else {
                        $('#namadriver').val('');
                    }
                });

                // Form submission handlers
                $('#pengiriman-form').on('submit', function (e) {
                    e.preventDefault();

                    const submitBtn = $(this).find('button[type="submit"]');
                    submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

                    // Collect form data
                    const formData = {
                        kodekirim: $('input[name="kodekirim"]').val(),
                        tglkirim: $('input[name="tglkirim"]').val(),
                        nopol: $('#nopol-select').val(),
                        produk: [],
                        kuantitas: []
                    };

                    // Collect product data
                    $('#product-rows tr').each(function () {
                        const produk = $(this).find('select[name="produk[]"]').val();
                        const qty = $(this).find('input[name="kuantitas[]"]').val();
                        if (produk) {
                            formData.produk.push(produk);
                            formData.kuantitas.push(parseInt(qty) || 1);
                        }
                    });

                    fetch('/api/pengiriman', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(formData)
                    })
                        .then(response => response.json().then(data => ({ status: response.status, body: data })))
                        .then(({ status, body }) => {
                            if (status >= 200 && status < 300) {
                                // Success - redirect to /pengiriman
                                window.location.href = '/pengiriman';
                            } else {
                                // Error
                                submitBtn.prop('disabled', false).html('<i class="fa fa-save"></i> Buat Pengiriman');

                                if (body.errors) {
                                    // Validation errors
                                    let errorMsg = 'Terjadi kesalahan:\n';
                                    Object.keys(body.errors).forEach(key => {
                                        errorMsg += '- ' + body.errors[key].join('\n- ') + '\n';
                                    });
                                    alert(errorMsg);
                                } else {
                                    alert(body.message || 'Gagal membuat pengiriman.');
                                }
                            }
                        })
                        .catch(error => {
                            submitBtn.prop('disabled', false).html('<i class="fa fa-save"></i> Buat Pengiriman');
                            alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
                            console.error('Error:', error);
                        });
                });
            });
        </script>
    @endpush

@endsection
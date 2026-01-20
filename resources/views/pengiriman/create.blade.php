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
    </style>
@endpush

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Buat Pengiriman Baru</h4>
        <a href="{{ route('pengiriman.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form id="pengiriman-form" method="POST" action="{{ route('pengiriman.store') }}">
        @csrf

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
                                <option value="{{ $k->nopol }}" {{ old('nopol') == $k->nopol ? 'selected' : '' }}>
                                    {{ $k->nopol }} - {{ $k->namakendaraan }} ({{ $k->kapasitas }})
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
                        <tr>
                            <td colspan="5" class="">
                                <div class="flex w-full justify-end">
                                    <button type="button" onclick="addProdukRow()" class="btn btn-success btn-sm">
                                        <i class="fa fa-plus"></i> Tambah Baris
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
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
                    $.get('/produk/get-produk/' + kodeproduk)
                        .done(function(data) {
                            row.find('input[name="nama[]"]').val(data.data.nama);
                            row.find('input[name="satuan[]"]').val(data.data.satuan);
                        })
                        .fail(function() {
                            alert('Gagal memuat informasi produk.');
                        });
                } else {
                    row.find('input[name="nama[]"]').val('');
                    row.find('input[name="satuan[]"]').val('');
                }
            }

            function removeProdukRow(btn) {
                $(btn).closest('tr').remove();
            }

            function addProdukRow() {
                const newRow = `
                    <tr class="product-row">
                        <td>
                            <select name="produk[]" class="form-control product-select" onchange="getProductInfo(this)" required>
                                <option value="">- Pilih Produk -</option>
                                @foreach ($produk as $p)
                                    <option value="{{ $p->kodeproduk }}">
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
                            <input type="number" name="kuantitas[]" class="form-control" min="1" required>
                        </td>
                        <td>
                                <button type="button" onclick="removeProdukRow(this)" class="btn btn-danger btn-sm remove-product-btn">
                                    <i class="fa fa-trash"></i> Hapus
                                </button>
                            </td>
                    </tr>
                `;
                $('#product-rows').append(newRow);
            }

            $(document).ready(function() {
                $('')
                // Vehicle selection handler
                $('#nopol-select').on('change', function() {
                    const nopol = $(this).val();

                    if (nopol) {
                        // AJAX get vehicle info
                        $.get('/pengiriman/get-vehicle-info/' + nopol)
                            .done(function(data) {
                                if (data.namadriver) {
                                    $('#namadriver').val(data.namadriver);
                                } else {

                                }
                            })
                            .fail(function() {
                                $('#driver-info').html(
                                    '<div class="alert alert-danger">Gagal memuat informasi kendaraan.</div>'
                                ).show();
                            });
                    } else {
                        $('#driver-info').hide();
                    }
                });

                // Form submission
                $('#pengiriman-form').on('submit', function() {
                    const submitBtn = $(this).find('button[type="submit"]');
                    submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
                });
            });
        </script>
    @endpush

@endsection

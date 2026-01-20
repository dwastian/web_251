@extends('layouts.app')

@section('title','Edit Pengiriman')

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
    .detail-table {
        margin-top: 20px;
    }
    .action-buttons {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }
    .btn-lg {
        padding: 10px 20px;
        font-size: 16px;
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
</style>
@endpush

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Edit Pengiriman: {{ $pengiriman->kodekirim }}</h4>
    <div>
        <span class="status-badge badge bg-{{ $pengiriman->status == 'draft' ? 'warning' : ($pengiriman->status == 'confirmed' ? 'success' : 'secondary') }}">
            {{ $pengiriman->status }}
        </span>
    </div>
</div>

<form id="pengiriman-form" method="POST" action="{{ route('pengiriman.update', $pengiriman->kodekirim) }}">
    @csrf
    @method('PUT')
    
    <!-- Informasi Pengiriman -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fa fa-info-circle"></i> Informasi Pengiriman</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label>Kode Pengiriman</label>
                    <input type="text" name="kodekirim" class="form-control" value="{{ $pengiriman->kodekirim }}" required>
                    <span class="text-danger">@error('kodekirim') {{ $message }} @enderror</span>
                </div>
                <div class="col-md-4">
                    <label>Tanggal Kirim</label>
                    <input type="date" name="tglkirim" class="form-control" value="{{ $pengiriman->tglkirim }}" required>
                    <span class="text-danger">@error('tglkirim') {{ $message }} @enderror</span>
                </div>
                <div class="col-md-4">
                    <label>Pilih Kendaraan</label>
                    <select name="nopol" class="form-control select2" id="nopol-select" required>
                        <option value="">- Pilih Kendaraan -</option>
                        @foreach($kendaraan as $k)
                            <option value="{{ $k->nopol }}" {{ $pengiriman->nopol == $k->nopol ? 'selected' : '' }}>
                                {{ $k->nopol }} - {{ $k->namakendaraan }} ({{ $k->kapasitas }})
                            </option>
                        @endforeach
                    </select>
                    <span class="text-danger">@error('nopol') {{ $message }} @enderror</span>
                </div>
            </div>

            <!-- Driver Info (Auto Display) -->
            <div id="driver-info" class="driver-info">
                <h6><i class="fa fa-user"></i> Informasi Driver</h6>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Nama Driver:</strong> <span id="driver-name">{{ $pengiriman->kendaraan->namadriver ?? '-' }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Kontak Driver:</strong> <span id="driver-contact">{{ $pengiriman->kendaraan->kontakdriver ?? '-' }}</span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <strong>Jenis Kendaraan:</strong> <span id="vehicle-type">{{ $pengiriman->kendaraan->jeniskendaraan ?? '-' }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Tahun:</strong> <span id="vehicle-year">{{ $pengiriman->kendaraan->tahun ?? '-' }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Kapasitas:</strong> <span id="vehicle-capacity">{{ $pengiriman->kendaraan->kapasitas ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <label>Catatan</label>
                    <textarea name="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan pengiriman...">{{ old('catatan', $pengiriman->catatan) }}</textarea>
                    <span class="text-danger">@error('catatan') {{ $message }} @enderror</span>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Detail Item Pengiriman -->
<div class="detail-section">
    <h5><i class="fa fa-box"></i> Detail Item Pengiriman</h5>
    
    <!-- Add Product Form -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h6 class="mb-0"><i class="fa fa-plus"></i> Tambah Produk</h6>
        </div>
        <div class="card-body">
            <form id="add-product-form">
                @csrf
                <input type="hidden" name="kodekirim" value="{{ $pengiriman->kodekirim }}">
                
                <div class="row">
                    <div class="col-md-6">
                        <label>Pilih Produk</label>
                        <select name="kodeproduk" class="form-control select2" id="product-select" required>
                            <option value="">- Pilih Produk -</option>
                            @foreach(\App\Models\Produk::orderBy('nama')->get() as $p)
                                <option value="{{ $p->kodeproduk }}">{{ $p->nama }} ({{ $p->satuan }}) - Stok: {{ \App\Models\DetailKirim::where('kodeproduk', $p->kodeproduk)->sum('qty') ?? 0 }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Kuantitas</label>
                        <input type="number" name="qty" class="form-control" id="qty-input" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-plus"></i> Tambah
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Detail Table -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="fa fa-list"></i> Detail Barang</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive detail-table">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Kode</th>
                            <th>Nama Produk</th>
                            <th>Satuan</th>
                            <th>Kuantitas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="detail-tbody">
                        @foreach($pengiriman->detailkirim as $detail)
                            <tr class="product-row" data-id="{{ $detail->id }}">
                                <td>{{ $detail->kodeproduk }}</td>
                                <td>{{ $detail->produk->nama }}</td>
                                <td>{{ $detail->produk->satuan }}</td>
                                <td>
                                    <input type="number" class="form-control qty-input" value="{{ $detail->qty }}" 
                                           data-id="{{ $detail->id }}" data-original="{{ $detail->qty }}">
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-warning edit-qty" data-id="{{ $detail->id }}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-detail" data-id="{{ $detail->id }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <tr id="empty-row" @if($pengiriman->detailkirim->count() > 0) style="display:none;" @endif>
                            <td colspan="5" class="text-center text-muted">
                                <i class="fa fa-info-circle"></i> Belum ada detail barang
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Summary -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <h5>Ringkasan:</h5>
                    <ul>
                        <li>Total Jenis Barang: <strong>{{ $pengiriman->detailkirim->count() }}</strong> jenis</li>
                        <li>Total Kuantitas: <strong id="total-qty">{{ $pengiriman->totalqty }}</strong></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5>Status Pengiriman:</h5>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> 
                        Pengiriman saat ini dalam status <strong>{{ $pengiriman->status }}</strong>
                        @if($pengiriman->status == 'draft')
                            <br>Anda dapat mengubah detail barang atau menyimpan sebagai draft.
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fixed Action Buttons -->
<div class="action-buttons">
    <div class="card">
        <div class="card-body">
            <div class="btn-group-vertical">
                <form method="POST" action="{{ route('pengiriman.save-pengiriman', $pengiriman->kodekirim) }}" style="display:inline;">
                    @csrf
                    <button type="submit" name="action" value="save_draft" class="btn btn-warning btn-lg">
                        <i class="fa fa-save"></i> Simpan Draft
                    </button>
                    <button type="submit" name="action" value="confirm_save" class="btn btn-success btn-lg">
                        <i class="fa fa-check"></i> Konfirmasi & Simpan
                    </button>
                </form>
                <form method="POST" action="{{ route('pengiriman.save-pengiriman', $pengiriman->kodekirim) }}" style="display:inline;">
                    @csrf
                    <button type="submit" name="action" value="cancel" class="btn btn-danger btn-lg"
                            onclick="return confirm('Apakah Anda yakin ingin membatalkan pengiriman ini? Semua detail barang akan dihapus.')">
                        <i class="fa fa-times"></i> Batal
                    </button>
                </form>
                <a href="{{ route('pengiriman.index') }}" class="btn btn-secondary btn-lg">
                    <i class="fa fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Vehicle selection handler
    $('#nopol-select').on('change', function() {
        const nopol = $(this).val();
        
        if (nopol) {
            $('#driver-info').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Memuat informasi driver...</div>').show();
            
            $.get('/pengiriman/get-vehicle-info/' + nopol)
                .done(function(data) {
                    if (data.namadriver) {
                        $('#driver-info').html(`
                            <h6><i class="fa fa-user"></i> Informasi Driver</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Nama Driver:</strong> <span id="driver-name">${data.namadriver}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Kontak Driver:</strong> <span id="driver-contact">${data.kontakdriver}</span>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <strong>Jenis Kendaraan:</strong> <span id="vehicle-type">${data.jeniskendaraan}</span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Tahun:</strong> <span id="vehicle-year">${data.tahun}</span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Kapasitas:</strong> <span id="vehicle-capacity">${data.kapasitas}</span>
                                </div>
                            </div>
                        `).show();
                    } else {
                        $('#driver-info').hide();
                    }
                })
                .fail(function() {
                    $('#driver-info').html('<div class="alert alert-danger">Gagal memuat informasi kendaraan.</div>').show();
                });
        } else {
            $('#driver-info').hide();
        }
    });

    // Initialize with current vehicle
    $('#nopol-select').trigger('change');

    // Add product form submission
    $('#add-product-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        const submitBtn = $(this).find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menambah...');
        
        $.ajax({
            url: '/pengiriman/add-product',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    location.reload(); // Simple reload for now
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Gagal menambah produk', 'error');
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fa fa-plus"></i> Tambah');
            }
        });
    });

    // Edit quantity
    $('.edit-qty').on('click', function() {
        const id = $(this).data('id');
        const input = $(`.qty-input[data-id="${id}"]`);
        const original = input.data('original');
        
        input.prop('readonly', false).css('background-color', '#fff');
        $(this).html('<i class="fa fa-save"></i>').removeClass('btn-warning').addClass('btn-success');
        $(this).off('click').on('click', function() {
            saveQuantity(id);
        });
    });

    // Save quantity function
    function saveQuantity(id) {
        const input = $(`.qty-input[data-id="${id}"]`);
        const newQty = parseInt(input.val());
        const original = parseInt(input.data('original'));
        
        if (newQty < 1) {
            Swal.fire('Error', 'Kuantitas minimal 1', 'error');
            return;
        }
        
        if (newQty === original) {
            input.prop('readonly', true).css('background-color', '#f8f9fa');
            $(`.edit-qty[data-id="${id}"]`).html('<i class="fa fa-edit"></i>').removeClass('btn-success').addClass('btn-warning');
            return;
        }
        
        $.ajax({
            url: `/pengiriman/update-detail-qty/${id}`,
            method: 'PUT',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                qty: newQty
            },
            success: function(response) {
                if (response.success) {
                    input.data('original', newQty);
                    $('#total-qty').text(response.total_qty);
                    Swal.fire('Success', response.message, 'success');
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Gagal update kuantitas', 'error');
            }
        });
    }

    // Delete detail
    $('.delete-detail').on('click', function() {
        const id = $(this).data('id');
        
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus item ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/pengiriman/remove-detail/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $(`.product-row[data-id="${id}"]`).remove();
                            $('#total-qty').text(response.total_qty);
                            
                            // Show empty row if no items left
                            if ($('#detail-tbody tr').length === 1) {
                                $('#empty-row').show();
                            }
                            
                            Swal.fire('Success', response.message, 'success');
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal hapus item', 'error');
                    }
                });
            }
        });
    });

    // Form submission handlers
    $('#pengiriman-form').on('submit', function() {
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
    });
});
</script>
@endpush

@endsection
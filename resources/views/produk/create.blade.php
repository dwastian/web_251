@extends('layouts.app')

@section('title', 'Tambah Produk Baru')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Tambah Produk Baru</h4>
        <a href="{{ route('produk.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form id="produk-form" enctype="multipart/form-data">
        @csrf
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fa fa-box"></i> Informasi Produk</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Kode Produk</label>
                        <input type="text" name="kodeproduk" class="form-control" placeholder="Contoh: PRD001" required>
                        <div class="invalid-feedback" id="error-kodeproduk"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Produk" required>
                        <div class="invalid-feedback" id="error-nama"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Satuan</label>
                        <input type="text" name="satuan" class="form-control" placeholder="Contoh: Pcs, Box, Kg" required>
                        <div class="invalid-feedback" id="error-satuan"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Harga</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga" class="form-control" placeholder="0" required>
                        </div>
                        <div class="invalid-feedback d-block" id="error-harga"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Gudang</label>
                        <select name="kodegudang" class="form-control" required>
                            <option value="">- Pilih Gudang -</option>
                            @foreach(\App\Models\Gudang::orderBy('namagudang')->get() as $g)
                                <option value="{{ $g->kodegudang }}">{{ $g->namagudang }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="error-kodegudang"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Gambar Produk</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*" id="gambar-input">
                        <div class="invalid-feedback" id="error-gambar"></div>
                        <div id="image-preview-container" class="mt-3 d-none">
                            <img id="image-preview" src="#" alt="Preview" class="img-fluid rounded border"
                                style="max-height: 150px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4 pt-2">
            <button type="submit" id="btn-save" class="btn btn-primary btn-lg px-5">
                <i class="fa fa-save me-2"></i> Simpan Produk
            </button>
        </div>
    </form>

    @push('scripts')
        <script>
            $(document).ready(function () {
                // Image Preview
                $('#gambar-input').on('change', function () {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            $('#image-preview').attr('src', e.target.result);
                            $('#image-preview-container').removeClass('d-none');
                        }
                        reader.readAsDataURL(file);
                    }
                });

                // Handle Form Submission
                $('#produk-form').on('submit', function (e) {
                    e.preventDefault();

                    const btn = $('#btn-save');
                    const originalHtml = btn.html();

                    // Reset errors
                    $('.form-control, .form-select').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Menyimpan...');

                    const formData = new FormData(this);

                    fetch('/api/produk', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                        .then(async res => {
                            const data = await res.json();
                            if (!res.ok) {
                                if (res.status === 422) {
                                    Object.keys(data.errors).forEach(key => {
                                        const field = $(`[name="${key}"]`);
                                        field.addClass('is-invalid');
                                        $(`#error-${key}`).text(data.errors[key][0]);
                                    });
                                    throw new Error('Validation failed');
                                }
                                throw new Error(data.message || 'Gagal menyimpan produk');
                            }
                            return data;
                        })
                        .then(data => {
                            alert(data.message);
                            window.location.href = '{{ route("produk.index") }}';
                        })
                        .catch(err => {
                            console.error(err);
                            if (err.message !== 'Validation failed') {
                                alert(err.message);
                            }
                            btn.prop('disabled', false).html(originalHtml);
                        });
                });
            });
        </script>
    @endpush
@endsection
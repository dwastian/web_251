@extends('layouts.app')

@section('title', 'Edit Gudang')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Edit Gudang: {{ $gudang->kodegudang }}</h4>
        <a href="{{ route('gudang.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form id="gudang-form">
        @csrf
        @method('PUT')
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fa fa-warehouse"></i> Informasi Gudang</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Kode Gudang</label>
                        <input type="text" name="kodegudang" class="form-control" value="{{ $gudang->kodegudang }}"
                            placeholder="Contoh: GD001" required>
                        <div class="invalid-feedback" id="error-kodegudang"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Gudang</label>
                        <input type="text" name="namagudang" class="form-control" value="{{ $gudang->namagudang }}"
                            placeholder="Nama Gudang" required>
                        <div class="invalid-feedback" id="error-namagudang"></div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat Lengkap Gudang"
                            required>{{ $gudang->alamat }}</textarea>
                        <div class="invalid-feedback" id="error-alamat"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kontak</label>
                        <input type="text" name="kontak" class="form-control" value="{{ $gudang->kontak }}"
                            placeholder="Nomor Telepon/PIC" required>
                        <div class="invalid-feedback" id="error-kontak"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kapasitas</label>
                        <input type="number" name="kapasitas" class="form-control" value="{{ $gudang->kapasitas }}"
                            placeholder="Kapasitas Gudang" required>
                        <div class="invalid-feedback" id="error-kapasitas"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4 pt-2">
            <button type="submit" id="btn-save" class="btn btn-warning btn-lg px-5">
                <i class="fa fa-save me-2 text-dark"></i> Update Gudang
            </button>
        </div>
    </form>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#gudang-form').on('submit', function (e) {
                    e.preventDefault();

                    const btn = $('#btn-save');
                    const originalHtml = btn.html();

                    // Reset errors
                    $('.form-control').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Menyimpan...');

                    const formData = new FormData(this);
                    const data = Object.fromEntries(formData.entries());

                    fetch(`/api/gudang/${data.kodegudang}`, {
                        method: 'POST', // Using POST with _method=PUT for broader compatibility (some servers/proxies struggle with literal PUT)
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            ...data,
                            _method: 'PUT'
                        })
                    })
                        .then(async res => {
                            const resData = await res.json();
                            if (!res.ok) {
                                if (res.status === 422) {
                                    Object.keys(resData.errors).forEach(key => {
                                        $(`[name="${key}"]`).addClass('is-invalid');
                                        $(`#error-${key}`).text(resData.errors[key][0]);
                                    });
                                    throw new Error('Validation failed');
                                }
                                throw new Error(resData.message || 'Gagal menyimpan gudang');
                            }
                            return resData;
                        })
                        .then(resData => {
                            alert(resData.message);
                            window.location.href = '{{ route("gudang.index") }}';
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
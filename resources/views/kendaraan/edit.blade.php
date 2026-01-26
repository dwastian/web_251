@extends('layouts.app')

@section('title', 'Edit Kendaraan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Edit Kendaraan: {{ $kendaraan->nopol }}</h4>
        <a href="{{ route('kendaraan.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form id="vehicle-form" enctype="multipart/form-data">
        @csrf
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fa fa-truck text-dark"></i> Informasi Kendaraan</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nomor Polisi (Nopol)</label>
                        <input type="text" name="nopol" class="form-control" value="{{ $kendaraan->nopol }}" readonly
                            placeholder="Contoh: B 1234 ABC" required>
                        <div class="invalid-feedback" id="error-nopol"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Kendaraan</label>
                        <input type="text" name="namakendaraan" class="form-control" value="{{ $kendaraan->namakendaraan }}"
                            placeholder="Nama Kendaraan" required>
                        <div class="invalid-feedback" id="error-namakendaraan"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Jenis Kendaraan</label>
                        <input type="text" name="jeniskendaraan" class="form-control"
                            value="{{ $kendaraan->jeniskendaraan }}" placeholder="Contoh: Box, Wingbox, Flatdeck" required>
                        <div class="invalid-feedback" id="error-jeniskendaraan"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tahun</label>
                        <input type="number" name="tahun" class="form-control" value="{{ $kendaraan->tahun }}" min="1900"
                            max="{{ date('Y') + 1 }}" required>
                        <div class="invalid-feedback" id="error-tahun"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Driver</label>
                        <input type="text" name="namadriver" class="form-control" value="{{ $kendaraan->namadriver }}"
                            placeholder="Nama Lengkap Driver" required>
                        <div class="invalid-feedback" id="error-namadriver"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kontak Driver</label>
                        <input type="text" name="kontakdriver" class="form-control" value="{{ $kendaraan->kontakdriver }}"
                            placeholder="Nomor Telepon/WA" required>
                        <div class="invalid-feedback" id="error-kontakdriver"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kapasitas</label>
                        <input type="text" name="kapasitas" class="form-control" value="{{ $kendaraan->kapasitas }}"
                            placeholder="Contoh: 5 Ton, 10 CBM" required>
                        <div class="invalid-feedback" id="error-kapasitas"></div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Foto Kendaraan</label>
                        <input type="file" name="foto" class="form-control" accept="image/*" id="foto-input">
                        <div class="invalid-feedback" id="error-foto"></div>

                        <div class="mt-3">
                            @if($kendaraan->foto)
                                <div id="current-image-container">
                                    <p class="small text-muted mb-1">Foto Saat Ini:</p>
                                    <img src="{{ asset('storage/' . $kendaraan->foto) }}" class="img-fluid rounded border mb-2"
                                        style="max-height: 100px;">
                                </div>
                            @endif
                            <div id="image-preview-container" class="d-none">
                                <p class="small text-primary mb-1">Preview Foto Baru:</p>
                                <img id="image-preview" src="#" alt="Preview" class="img-fluid rounded border"
                                    style="max-height: 150px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4 pt-2">
            <button type="submit" id="btn-save" class="btn btn-warning btn-lg px-5">
                <i class="fa fa-save me-2 text-dark"></i> Update Kendaraan
            </button>
        </div>
    </form>

    @push('scripts')
        <script>
            $(document).ready(function () {
                // Image Preview
                $('#foto-input').on('change', function () {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            $('#image-preview').attr('src', e.target.result);
                            $('#image-preview-container').removeClass('d-none');
                            $('#current-image-container').addClass('opacity-50');
                        }
                        reader.readAsDataURL(file);
                    }
                });

                // Handle Form Submission
                $('#vehicle-form').on('submit', function (e) {
                    e.preventDefault();

                    const btn = $('#btn-save');
                    const originalHtml = btn.html();

                    // Reset errors
                    $('.form-control').removeClass('is-invalid');
                    $('.invalid-feedback').text('');

                    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Menyimpan...');

                    const formData = new FormData(this);

                    fetch('/api/kendaraan/update/{{ $kendaraan->nopol }}', {
                        method: 'POST', // Using POST for multipart/form-data
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
                                    // Validation errors
                                    Object.keys(data.errors).forEach(key => {
                                        $(`[name="${key}"]`).addClass('is-invalid');
                                        $(`#error-${key}`).text(data.errors[key][0]);
                                    });
                                    throw new Error('Validation failed');
                                }
                                throw new Error(data.message || 'Gagal menyimpan kendaraan');
                            }
                            return data;
                        })
                        .then(data => {
                            alert(data.message);
                            window.location.href = '{{ route("kendaraan.index") }}';
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
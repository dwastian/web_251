@extends('layouts.app')

@section('title', 'Kendaraan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Data Kendaraan</h4>
        <div class="btn-group">
            <button type="button" id="btn-bulk-delete" class="btn btn-outline-danger d-none" onclick="bulkDelete()">
                <i class="fa fa-trash me-1"></i> Hapus Terpilih (<span id="selected-count">0</span>)
            </button>
            <a href="{{ route('kendaraan.create') }}" class="btn btn-primary ms-2">
                <i class="fa fa-plus me-1"></i> Tambah Kendaraan
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="kendaraan-table">
                    <thead class="table-light">
                        <tr>
                            <th width="40" class="text-center">
                                <input type="checkbox" autofocus class="form-check-input" id="select-all">
                            </th>
                            <th>Nopol</th>
                            <th>Nama Kendaraan</th>
                            <th>Jenis</th>
                            <th>Driver</th>
                            <th>Tahun</th>
                            <th>Kapasitas</th>
                            <th>Foto</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="kendaraan-rows">
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                                Sedang memuat data...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div id="pagination-info" class="text-muted small">
                    Showing 0 to 0 of 0 entries
                </div>
                <nav id="pagination-nav">
                    <!-- Pagination buttons will be rendered here -->
                </nav>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let currentPage = 1;
            let selectedIds = new Set();

            $(document).ready(function () {
                loadData(currentPage);

                $('#select-all').on('click', function () {
                    const isChecked = $(this).prop('checked');
                    $('.row-checkbox').prop('checked', isChecked);
                    updateSelectedIds();
                });

                $(document).on('click', '.row-checkbox', function () {
                    updateSelectedIds();

                    const totalCheckboxes = $('.row-checkbox').length;
                    const checkedCheckboxes = $('.row-checkbox:checked').length;
                    $('#select-all').prop('checked', totalCheckboxes === checkedCheckboxes && totalCheckboxes > 0);
                });
            });

            function updateSelectedIds() {
                $('.row-checkbox').each(function () {
                    const id = $(this).val();
                    if ($(this).prop('checked')) {
                        selectedIds.add(id);
                    } else {
                        selectedIds.delete(id);
                    }
                });

                const count = selectedIds.size;
                $('#selected-count').text(count);
                if (count > 0) {
                    $('#btn-bulk-delete').removeClass('d-none');
                } else {
                    $('#btn-bulk-delete').addClass('d-none');
                }
            }

            function loadData(page = 1) {
                currentPage = page;
                $('#kendaraan-rows').html('<tr><td colspan="9" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary me-2"></div> Sedang memuat data...</td></tr>');
                $('#select-all').prop('checked', false);

                fetch(`/api/kendaraan?page=${page}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                    .then(res => res.json())
                    .then(res => {
                        const rows = res.data.map(k => {
                            const foto = k.foto
                                ? `<img src="/storage/${k.foto}" height="50" class="rounded shadow-sm border" style="object-fit: cover; width: 50px;">`
                                : `<span class="badge bg-light text-muted border py-2">No Photo</span>`;

                            return `
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input row-checkbox" value="${k.nopol}" ${selectedIds.has(k.nopol) ? 'checked' : ''}>
                                        </td>
                                        <td class="fw-bold text-primary">${k.nopol}</td>
                                        <td>${k.namakendaraan}</td>
                                        <td><span class="badge bg-info text-dark">${k.jeniskendaraan}</span></td>
                                        <td>
                                            <div class="fw-bold">${k.namadriver}</div>
                                            <small class="text-muted">${k.kontakdriver || '-'}</small>
                                        </td>
                                        <td>${k.tahun}</td>
                                        <td><span class="badge bg-secondary">${k.kapasitas}</span></td>
                                        <td>${foto}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="/kendaraan/${k.nopol}/edit" class="btn btn-warning btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <button type="button" onclick="deleteKendaraan('${k.nopol}')" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                `;
                        }).join('');

                        $('#kendaraan-rows').html(rows || '<tr><td colspan="9" class="text-center py-4 text-muted">Tidak ada data kendaraan.</td></tr>');

                        $('#pagination-info').text(`Showing ${res.from || 0} to ${res.to || 0} of ${res.total} entries`);
                        renderPagination(res);
                    })
                    .catch(err => {
                        console.error(err);
                        $('#kendaraan-rows').html('<tr><td colspan="9" class="text-center py-4 text-danger">Gagal memuat data.</td></tr>');
                    });
            }

            function renderPagination(data) {
                if (data.last_page <= 1) {
                    $('#pagination-nav').empty();
                    return;
                }

                let html = '<ul class="pagination pagination-sm mb-0">';
                html += `<li class="page-item ${data.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" onclick="event.preventDefault(); loadData(${data.current_page - 1})">Previous</a></li>`;

                for (let i = 1; i <= data.last_page; i++) {
                    if (i === 1 || i === data.last_page || (i >= data.current_page - 1 && i <= data.current_page + 1)) {
                        html += `<li class="page-item ${i === data.current_page ? 'active' : ''}"><a class="page-link" href="#" onclick="event.preventDefault(); loadData(${i})">${i}</a></li>`;
                    } else if (i === data.current_page - 2 || i === data.current_page + 2) {
                        html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                }

                html += `<li class="page-item ${data.current_page === data.last_page ? 'disabled' : ''}"><a class="page-link" href="#" onclick="event.preventDefault(); loadData(${data.current_page + 1})">Next</a></li>`;
                html += '</ul>';
                $('#pagination-nav').html(html);
            }

            function deleteKendaraan(nopol) {
                if (confirm(`Apakah Anda yakin ingin menghapus kendaraan ${nopol}?`)) {
                    fetch(`/api/kendaraan/${nopol}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            alert(data.message);
                            loadData(currentPage);
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Gagal menghapus data.');
                        });
                }
            }

            function bulkDelete() {
                const ids = Array.from(selectedIds);
                if (ids.length === 0) return;

                if (confirm(`Apakah Anda yakin ingin menghapus ${ids.length} kendaraan terpilih?`)) {
                    fetch('/api/kendaraan/bulk-delete', {
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
                            selectedIds.clear();
                            $('#selected-count').text(0);
                            $('#btn-bulk-delete').addClass('d-none');
                            loadData(currentPage);
                        })
                        .catch(err => {
                            console.error(err);
                            alert(err.message);
                        });
                }
            }
        </script>
    @endpush
@endsection
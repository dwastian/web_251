@extends('layouts.app')

@section('title', 'Daftar Pengiriman Barang')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Daftar Pengiriman Barang</h4>
        <a href="{{ route('pengiriman.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Buat Pengiriman Baru
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered" id="pengiriman-table">
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
                <tbody id="pengiriman-rows">
                    <tr>
                        <td colspan="7" class="text-center">Sedang memuat data...</td>
                    </tr>
                </tbody>
            </table>

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

            $(document).ready(function () {
                loadData(currentPage);
            });

            function loadData(page = 1) {
                currentPage = page;
                $('#pengiriman-rows').html('<tr><td colspan="7" class="text-center">Sedang memuat data...</td></tr>');

                fetch(`/api/pengiriman?page=${page}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                    .then(res => res.json())
                    .then(res => {
                        const rows = res.data.map(p => {
                            const statusColor = p.status === 'Draft' ? 'warning' : (p.status === 'Confirmed' ? 'success' : 'secondary');
                            const tgl = new Date(p.tglkirim).toLocaleDateString('id-ID');

                            let kendaraanInfo = '-';
                            let driverInfo = '-';
                            if (p.kendaraan) {
                                kendaraanInfo = `${p.kendaraan.nopol} - ${p.kendaraan.namakendaraan}<br><small class="text-muted">${p.kendaraan.jeniskendaraan} (${p.kendaraan.kapasitas})</small>`;
                                driverInfo = `${p.kendaraan.namadriver}<br><small class="text-muted">${p.kendaraan.kontakdriver || '-'}</small>`;
                            }

                            let actions = `
                                                        <div class="btn-group" role="group">
                                                            ${p.status !== 'Confirmed' ? `
                                                                <a href="/pengiriman/${p.kodekirim}/edit" class="btn btn-warning btn-sm">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                            ` : ''}
                                                            <button type="button" onclick="deletePengiriman('${p.kodekirim}')" class="btn btn-danger btn-sm">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    `;

                            return `
                                                        <tr>
                                                            <td><span class="badge bg-primary">${p.kodekirim}</span></td>
                                                            <td>${tgl}</td>
                                                            <td>${kendaraanInfo}</td>
                                                            <td>${driverInfo}</td>
                                                            <td><span class="badge bg-info">${p.totalqty}</span></td>
                                                            <td><span class="badge bg-${statusColor}">${p.status}</span></td>
                                                            <td>${actions}</td>
                                                        </tr>
                                                    `;
                        }).join('');

                        $('#pengiriman-rows').html(rows || '<tr><td colspan="7" class="text-center">Tidak ada data.</td></tr>');

                        // Update Pagination Info
                        $('#pagination-info').text(`Showing ${res.from || 0} to ${res.to || 0} of ${res.total} entries`);

                        // Render Pagination Nav
                        renderPagination(res);
                    })
                    .catch(err => {
                        console.error(err);
                        $('#pengiriman-rows').html('<tr><td colspan="7" class="text-center text-danger">Gagal memuat data.</td></tr>');
                    });
            }

            function renderPagination(data) {
                let html = '<ul class="pagination pagination-sm mb-0">';

                // Previous button
                html += `
                                    <li class="page-item ${data.current_page === 1 ? 'disabled' : ''}">
                                        <a class="page-link" href="#" onclick="event.preventDefault(); loadData(${data.current_page - 1})">Previous</a>
                                    </li>
                                `;

                // Page numbers
                // Simple version: showing 5 pages around current
                let startPage = Math.max(1, data.current_page - 2);
                let endPage = Math.min(data.last_page, startPage + 4);
                if (endPage - startPage < 4) {
                    startPage = Math.max(1, endPage - 4);
                }

                for (let i = startPage; i <= endPage; i++) {
                    html += `
                                        <li class="page-item ${i === data.current_page ? 'active' : ''}">
                                            <a class="page-link" href="#" onclick="event.preventDefault(); loadData(${i})">${i}</a>
                                        </li>
                                    `;
                }

                // Next button
                html += `
                                    <li class="page-item ${data.current_page === data.last_page ? 'disabled' : ''}">
                                        <a class="page-link" href="#" onclick="event.preventDefault(); loadData(${data.current_page + 1})">Next</a>
                                    </li>
                                `;

                html += '</ul>';
                $('#pagination-nav').html(html);
            }

            function deletePengiriman(kodekirim) {
                if (confirm(`Apakah Anda yakin ingin menghapus pengiriman ${kodekirim}?`)) {
                    fetch(`/api/pengiriman/${kodekirim}`, {
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
        </script>
    @endpush

@endsection
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/css/adminlte.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="layout-fixed bg-light">

    <div class="wrapper">

        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg bg-green-900">
            <ul class="nav flex w-full nav-sidebar">
                <li class="nav-item">
                    <a href="/" class="nav-link"><i class="fa text-white fa-home"></i><span
                            class="ms-2 text-white">Dashboard</span></a>
                </li>

                <li class="nav-item">
                    <a href="/produk" class="nav-link"><i class="fa text-white fa-box"></i><span
                            class="ms-2 text-white">Produk</span></a>
                </li>

                <li class="nav-item">
                    <a href="/gudang" class="nav-link"><i class="fa text-white fa-warehouse"></i><span
                            class="ms-2 text-white">Gudang</span></a>
                </li>

                <li class="nav-item">
                    <a href="/kendaraan" class="nav-link"><i class="fa text-white fa-truck-pickup"></i><span
                            class="ms-2 text-white">Kendaraan</span></a>
                </li>

                <li class="nav-item">
                    <a href="/pengiriman" class="nav-link"><i class="fa text-white fa-shipping-fast"></i><span
                            class="ms-2 text-white">Pengiriman Barang</span></a>
                </li>


            </ul>
        </nav>


        <!-- Sidebar -->


        <!-- CONTENT -->
        <main class="content-wrapper p-4">
            @yield('content')
        </main>
    </div>

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AdminLTE -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/js/adminlte.min.js"></script>

    <!-- Datatables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @push('scripts')
        <script>
            const successMessage = @json(session('success'));
            const errorMessage = @json(session('error'));
        </script>
    @endpush

    @stack('scripts')
</body>

</html>
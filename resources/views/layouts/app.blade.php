<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Dashboard')</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0/dist/css/adminlte.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css"/>

    @stack('styles')
</head>
<body class="layout-fixed bg-light">

<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3 text-white" href="/">
            Sistem Pengiriman
        </a>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <div class="sidebar p-2">
            <nav>
                <ul class="nav nav-pills nav-sidebar flex-column">

                    <li class="nav-item">
                        <a href="/" class="nav-link"><i class="fa fa-home"></i><span class="ms-2">Dashboard</span></a>
                    </li>

                    <li class="nav-item">
                        <a href="/produk" class="nav-link"><i class="fa fa-box"></i><span class="ms-2">Produk</span></a>
                    </li>

                    <li class="nav-item">
                        <a href="/gudang" class="nav-link"><i class="fa fa-warehouse"></i><span class="ms-2">Gudang</span></a>
                    </li>

                    <li class="nav-item">
                        <a href="/kendaraan" class="nav-link"><i class="fa fa-truck-pickup"></i><span class="ms-2">Kendaraan</span></a>
                    </li>

                    <li class="nav-item">
                        <a href="/masterkirim" class="nav-link"><i class="fa fa-truck"></i><span class="ms-2">Master Kirim</span></a>
                    </li>


                </ul>
            </nav>
        </div>
    </aside>

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

<!-- Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@stack('scripts')
</body>
</html>

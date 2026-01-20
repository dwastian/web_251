@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="grid grid-cols-2 gap-2 bg-primary p-3 rounded-3xl text-white h-full">
                    <div class="inner">
                        <h3>{{ $countProduk }}</h3>
                        <p>Produk</p>
                    </div>
                    <div class="text-5xl flex justify-end h-full w-full"><i class="fa fa-box"></i></div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="grid grid-cols-2 gap-2 bg-amber-800 p-3 rounded-3xl text-white h-full">
                    <div class="inner">
                        <h3>{{ $countGudang }}</h3>
                        <p>Gudang</p>
                    </div>
                    <div class="text-5xl flex justify-end h-full w-full"><i class="fa fa-warehouse"></i></div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="grid grid-cols-2 gap-2 bg-orange-600 p-3 rounded-3xl text-white h-full">
                    <div class="inner">
                        <h3>{{ $countKendaraan }}</h3>
                        <p>Kendaraan</p>
                    </div>
                    <div class="text-5xl flex justify-end h-full w-full"><i class="fa fa-truck"></i></div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="grid grid-cols-2 gap-2 bg-red-700 p-3 rounded-3xl text-white h-full">
                    <div class="inner">
                        <h3>{{ $countKirim }}</h3>
                        <p>Pengiriman</p>
                    </div>
                    <div class="text-5xl flex justify-end h-full w-full"><i class="fa fa-shipping-fast"></i></div>
                </div>
            </div>

        </div>
    </div>


@endsection

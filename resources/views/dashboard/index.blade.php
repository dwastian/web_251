@extends('layouts.app')

@section('title','Dashboard')

@section('content')

<div class="container-fluid">
    <div class="row">

        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $countProduk }}</h3>
                    <p>Produk</p>
                </div>
                <div class="icon"><i class="fa fa-box"></i></div>
                <a href="{{ route('produk.index') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $countGudang }}</h3>
                    <p>Gudang</p>
                </div>
                <div class="icon"><i class="fa fa-warehouse"></i></div>
                <a href="{{ route('gudang.index') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $countKendaraan }}</h3>
                    <p>Kendaraan</p>
                </div>
                <div class="icon"><i class="fa fa-truck"></i></div>
                <a href="{{ route('kendaraan.index') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $countKirim }}</h3>
                    <p>Master Kirim</p>
                </div>
                <div class="icon"><i class="fa fa-shipping-fast"></i></div>
                <a href="{{ route('masterkirim.index') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

    </div>
</div>


@endsection


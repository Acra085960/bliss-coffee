@extends('layouts.app')

@section('content')
<div class="row">
  <!-- TOTAL PESANAN -->
  <div class="col-lg-4 col-md-6 col-12">
    <div class="small-box bg-info">
      <div class="inner">
        <h3>{{ $totalOrders }}</h3>
        <p>Total Pesanan</p>
      </div>
      <div class="icon">
        <i class="fas fa-shopping-cart"></i>
      </div>
    </div>
  </div>

  <!-- MENU AKTIF -->
  <div class="col-lg-4 col-md-6 col-12">
    <div class="small-box bg-success">
      <div class="inner">
        <h3>{{ $activeMenus }}</h3>
        <p>Menu Aktif</p>
      </div>
      <div class="icon">
        <i class="fas fa-coffee"></i>
      </div>
    </div>
  </div>

 <!-- STOK -->
<div class="col-lg-4 col-md-6 col-12">
  <div class="small-box {{ $isStockLow ? 'bg-warning' : 'bg-info' }}">
    <div class="inner">
      <h3>{{ $stockSum }}</h3>
      <p>
        {{ $isStockLow ? 'Stok Hampir Habis' : 'Total Jumlah Stok' }}
      </p>
    </div>
    <div class="icon">
      @if($isStockLow)
        <i class="fas fa-exclamation-triangle"></i>
      @else
        <i class="fas fa-box"></i>
      @endif
    </div>
  </div>
</div>

<!-- Card Section -->
<div class="card mt-3">
  <div class="card-header">
    <h3 class="card-title">Pesanan Terbaru</h3>
  </div>
  <div class="card-body">
    <p>Belum ada pesanan masuk.</p>
  </div>
</div>
@endsection

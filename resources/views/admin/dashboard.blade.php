@extends('layouts.app')

@section('content')
<div class="row">
  <!-- TOTAL PESANAN -->
  <div class="col-lg-4 col-md-6 col-12">
    <div class="small-box bg-info">
      <div class="inner">
        <h3>12</h3>
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
        <h3>5</h3>
        <p>Menu Aktif</p>
      </div>
      <div class="icon">
        <i class="fas fa-coffee"></i>
      </div>
    </div>
  </div>

  <!-- STOK HABIS -->
  <div class="col-lg-4 col-md-6 col-12">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>2</h3>
        <p>Stok Hampir Habis</p>
      </div>
      <div class="icon">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
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

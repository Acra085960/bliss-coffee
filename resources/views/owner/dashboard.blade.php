<!-- resources/views/owner/dashboard.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Owner Dashboard') }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card text-white bg-primary mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Total Revenue</h5>
                                    <p class="card-text">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Total Orders</h5>
                                    <p class="card-text">{{ $totalOrders }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-info mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Total Menus</h5>
                                    <p class="card-text">{{ $totalMenus }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-warning mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Employees</h5>
                                    <p class="card-text">{{ $totalEmployees }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Profit Analysis</div>
                                <div class="card-body">
                                    <p><strong>Revenue:</strong> Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                                    <p><strong>Expenses:</strong> Rp {{ number_format($expenses, 0, ',', '.') }}</p>
                                    <p><strong>Profit:</strong> Rp {{ number_format($totalRevenue - $expenses, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

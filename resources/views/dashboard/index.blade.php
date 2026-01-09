@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <!-- Today Sales -->
    <div class="col-md-3">
        <div class="card card-stats bg-primary text-white h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Today's Sales</h6>
                        <h3 class="mb-0">${{ number_format($stats['today_sales'], 2) }}</h3>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                        <i class="fas fa-dollar-sign fa-lg"></i>
                    </div>
                </div>
                <div class="mt-3 small">
                    <span class="fw-bold">{{ $stats['today_orders'] }}</span> orders today
                </div>
            </div>
        </div>
    </div>
    
    <!-- Month Sales -->
    <div class="col-md-3">
        <div class="card card-stats bg-success text-white h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">This Month</h6>
                        <h3 class="mb-0">${{ number_format($stats['month_sales'], 2) }}</h3>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                        <i class="fas fa-chart-line fa-lg"></i>
                    </div>
                </div>
                <div class="mt-3 small">
                    <span class="fw-bold">{{ $stats['month_orders'] }}</span> orders this month
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Products -->
    <div class="col-md-3">
        <div class="card card-stats bg-info text-white h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50">Total Products</h6>
                        <h3 class="mb-0">{{ \App\Models\Product::count() }}</h3>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                        <i class="fas fa-box fa-lg"></i>
                    </div>
                </div>
                <div class="mt-3 small">
                    In {{ \App\Models\Category::count() }} Categories
                </div>
            </div>
        </div>
    </div>
    
    <!-- Total Customers -->
    <div class="col-md-3">
        <div class="card card-stats bg-warning text-dark h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-black-50">Total Customers</h6>
                        <h3 class="mb-0">{{ \App\Models\Customer::count() }}</h3>
                    </div>
                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                </div>
                <div class="mt-3 small">
                    Registered customers
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Low Stock Alert -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th class="text-center">Stock</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts as $product)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $product->name }}</div>
                                    <small class="text-muted">{{ $product->sku }}</small>
                                </td>
                                <td>{{ $product->category->name }}</td>
                                <td class="text-center">
                                    <span class="badge bg-danger">{{ $product->stock }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    No low stock items found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0"><i class="fas fa-clock me-2"></i>Recent Orders</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->customer->name ?? 'Walk-in Customer' }}</td>
                                <td class="fw-bold">${{ number_format($order->total, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'canceled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    No orders found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

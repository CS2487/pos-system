@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid p-4">

    <!-- Stats Grid -->
    <div class="row g-4 mb-5">
        
        <!-- Today Sales -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stats-card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stats-label">Today's Sales</p>
                            <h3 class="stats-value">${{ number_format($stats['today_sales'], 2) }}</h3>
                        </div>
                        <div class="stats-icon-bg">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                    <div class="mt-3 d-flex align-items-center stats-sub text-success">
                        <i class="fas fa-shopping-cart me-1"></i>
                        <span>{{ $stats['today_orders'] }} orders</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today Purchases -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stats-card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stats-label">Today's Purchases</p>
                            <h3 class="stats-value">${{ number_format($stats['today_purchases'], 2) }}</h3>
                        </div>
                        <div class="stats-icon-bg">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                    </div>
                    <div class="mt-3 d-flex align-items-center stats-sub">
                        <i class="fas fa-box me-1"></i>
                        <span>{{ $stats['today_purchases_count'] }} items</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Month Sales -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stats-card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stats-label">This Month Sales</p>
                            <h3 class="stats-value">${{ number_format($stats['month_sales'], 2) }}</h3>
                        </div>
                        <div class="stats-icon-bg">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                    <div class="mt-3 d-flex align-items-center stats-sub text-success">
                        <i class="fas fa-arrow-up me-1"></i>
                        <span>{{ $stats['month_orders'] }} sales</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Month Purchases -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stats-card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stats-label">Month Purchases</p>
                            <h3 class="stats-value">${{ number_format($stats['month_purchases'], 2) }}</h3>
                        </div>
                        <div class="stats-icon-bg">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                    </div>
                    <div class="mt-3 d-flex align-items-center stats-sub">
                        <span>{{ $stats['month_purchases_count'] }} invoices</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Products -->
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card stats-card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stats-label">Total Products</p>
                            <h3 class="stats-value">{{ $stats['total_products'] }}</h3>
                        </div>
                        <div class="stats-icon-bg">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                    <div class="mt-3 d-flex align-items-center stats-sub">
                        <i class="fas fa-tags me-1"></i>
                        <span>{{ $stats['total_categories'] }} categories</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card stats-card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stats-label">Total Customers</p>
                            <h3 class="stats-value">{{ \App\Models\Customer::count() }}</h3>
                        </div>
                        <div class="stats-icon-bg">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="mt-3 d-flex align-items-center stats-sub">
                        <span>Registered</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Suppliers -->
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card stats-card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="stats-label">Total Suppliers</p>
                            <h3 class="stats-value">{{ $stats['total_suppliers'] }}</h3>
                        </div>
                        <div class="stats-icon-bg">
                            <i class="fas fa-truck"></i>
                        </div>
                    </div>
                    <div class="mt-3 d-flex align-items-center stats-sub">
                        <span>Active partners</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Tables Row -->
    <div class="row g-4">
        
        <!-- Low Stock Alert -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom border-light">
                    <h5 class="card-title mb-0 text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockProducts as $product)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $product->name }}</div>
                                        <small class="text-muted">{{ $product->sku }}</small>
                                    </td>
                                    <td>{{ $product->category->name }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $product->stock }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-primary">Edit</a>
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
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-bottom border-light">
                    <h5 class="card-title mb-0" style="color: var(--text-main)">
                        <i class="fas fa-clock me-2" style="color: var(--primary)"></i>Recent Orders
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->customer->name ?? 'Walk-in Customer' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'canceled' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold">
                                        ${{ number_format($order->total, 2) }}
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
</div>

<style>
    /* استعادة الألوان الأصلية الخاصة بك مع تحسين الاستجابة */
    .stats-card {
        background: #fff;
        border-radius: 12px !important;
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
        border-color: var(--primary) !important;
    }
    
    .stats-icon-bg {
        width: 48px;
        height: 48px;
        background: rgba(49, 65, 88, 0.1); /* اللون الأصلي من كودك */
        color: var(--primary);             /* اللون الأصلي من كودك */
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        transition: all 0.3s;
    }

    .stats-card:hover .stats-icon-bg {
        background: var(--primary);
        color: #fff;
    }
    
    .stats-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #888;
        display: block;
        margin-bottom: 5px;
        letter-spacing: 0.5px;
    }
    
    .stats-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 5px;
        line-height: 1.2;
    }
    
    .stats-sub {
        font-size: 0.8rem;
        color: #999;
        font-weight: 500;
    }
</style>
@endsection
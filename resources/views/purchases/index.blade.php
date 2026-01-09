@extends('layouts.admin')

@section('title', 'Purchases')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Purchase History</h5>
        <a href="{{ route('purchases.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>New Purchase
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Supplier</th>
                        <th>Total Amount</th>
                        <th>Items Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->date->format('Y-m-d') }}</td>
                        <td>{{ $purchase->supplier->name }}</td>
                        <td class="fw-bold">${{ number_format($purchase->total_amount, 2) }}</td>
                        <td>{{ $purchase->items->sum('quantity') }} items</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-outline-info"><i class="fas fa-eye"></i></a>
                                <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this purchase? Stock will NOT be reverted automatically (manual adjustment required).')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">No purchases found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">{{ __('messages.order_history') }}</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('messages.order_id') }}</th>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('messages.customers') }}</th>
                        <th>{{ __('messages.cashier') }}</th>
                        <th>{{ __('messages.total') }}</th>
                        <th>{{ __('messages.payment_method') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $order->customer->name ?? __('messages.walk_in_customer') }}</td>
                        <td>{{ $order->user->name ?? 'Unknown' }}</td>
                        <td class="fw-bold">${{ number_format($order->total, 2) }}</td>
                        <td>
                            <i class="fas fa-{{ $order->payment_method === 'cash' ? 'money-bill-wave' : 'credit-card' }} text-muted me-1"></i>
                            {{ __('messages.'.$order->payment_method) }}
                        </td>
                        <td>
                            @if($order->is_return)
                                <span class="badge bg-danger">{{ __('messages.order_returned') }}</span>
                            @else
                                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'canceled' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-info me-1" title="{{ __('messages.details') }}"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('orders.print', $order->id) }}" target="_blank" class="btn btn-outline-secondary me-1" title="{{ __('messages.print') }}"><i class="fas fa-print"></i></a>
                                <a href="{{ route('orders.invoice', $order->id) }}" class="btn btn-outline-primary me-1" title="{{ __('messages.invoice') }}"><i class="fas fa-file-pdf"></i></a>
                                
                                @if(!$order->is_return && $order->status !== 'canceled')
                                <form action="{{ route('orders.return', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.return_order') }}?')">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-warning me-1" title="{{ __('messages.return_order') }}"><i class="fas fa-undo"></i></button>
                                </form>
                                @endif

                                @if(auth()->user()->role === 'admin')
                                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.confirm_delete_order') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="{{ __('messages.delete') }}"><i class="fas fa-trash"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">{{ __('messages.no_orders') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

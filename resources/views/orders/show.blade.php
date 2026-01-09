@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Order #{{ $order->id }}</h5>
                <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'canceled' ? 'danger' : 'warning') }} fs-6">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h6 class="text-muted">Customer:</h6>
                        <h5>{{ $order->customer->name ?? 'Walk-in Customer' }}</h5>
                        @if($order->customer)
                            <div>{{ $order->customer->phone }}</div>
                        @endif
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <h6 class="text-muted">{{ __('messages.order_info') }}:</h6>
                        <div>{{ __('messages.date') }}: {{ $order->created_at->format('d M, Y H:i') }}</div>
                        <div>{{ __('messages.cashier') }}: {{ $order->user->name ?? 'Unknown' }}</div>
                        <div class="mt-2 text-primary fw-bold">
                            {{ __('messages.payment_method') }}: {{ __('messages.'.$order->payment_method) }}
                        </div>
                    </div>
                </div>

                @if($order->is_return)
                <div class="alert alert-danger d-flex align-items-center mb-4">
                    <i class="fas fa-undo fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading mb-0">{{ __('messages.order_returned') }}</h5>
                        <small>{{ $order->updated_at->format('d M, Y H:i') }}</small>
                    </div>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('messages.products') }}</th>
                                <th class="text-end">{{ __('messages.quantity') }}</th>
                                <th class="text-end">{{ __('messages.price') }}</th>
                                <th class="text-end">{{ __('messages.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td class="text-end">{{ $item->quantity }}</td>
                                <td class="text-end">${{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-end">${{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                            
                            <tr>
                                <td colspan="3" class="text-end">{{ __('messages.subtotal') }}:</td>
                                <td class="text-end">${{ number_format($order->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end">{{ __('messages.tax') }}:</td>
                                <td class="text-end">${{ number_format($order->tax, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end">{{ __('messages.discount') }}:</td>
                                <td class="text-end text-danger">-${{ number_format($order->discount, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end fw-bold fs-5">{{ __('messages.total') }}:</td>
                                <td class="text-end fw-bold fs-5 text-primary">${{ number_format($order->total, 2) }}</td>
                            </tr>
                            @if($order->payment_method === 'cash')
                            <tr class="table-light">
                                <td colspan="3" class="text-end">{{ __('messages.received_amount') }}:</td>
                                <td class="text-end">${{ number_format($order->received_amount, 2) }}</td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="3" class="text-end">{{ __('messages.change_amount') }}:</td>
                                <td class="text-end text-success fw-bold">${{ number_format($order->change_amount, 2) }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white d-flex justify-content-between">
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> {{ __('messages.cancel') }}
                </a>
                <div class="btn-group">
                    <a href="{{ route('orders.print', $order->id) }}" target="_blank" class="btn btn-secondary">
                        <i class="fas fa-print me-1"></i> {{ __('messages.print') }}
                    </a>
                    <a href="{{ route('orders.invoice', $order->id) }}" class="btn btn-primary">
                        <i class="fas fa-file-pdf me-1"></i> {{ __('messages.invoice') }}
                    </a>
                    @if(!$order->is_return && $order->status !== 'canceled')
                    <form action="{{ route('orders.return', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.return_order') }}?')">
                        @csrf
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-undo me-1"></i> {{ __('messages.return_order') }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

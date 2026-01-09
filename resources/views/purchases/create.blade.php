@extends('layouts.admin')

@section('title', 'New Purchase')

@section('content')
<form action="{{ route('purchases.store') }}" method="POST" id="purchase-form">
    @csrf
    <div class="row">
        <!-- Left: Purchase Header -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Purchase Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', date('Y-m-d')) }}" required>
                        @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold">Total Amount:</span>
                        <span class="fw-bold fs-5 text-primary" id="grand-total">$0.00</span>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Complete Purchase</button>
                    <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary w-100 mt-2">Cancel</a>
                </div>
            </div>
        </div>

        <!-- Right: Items -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Items</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-item">
                        <i class="fas fa-plus me-1"></i> Add Row
                    </button>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0" id="items-table">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40%">Product</th>
                                <th style="width: 20%">Quantity</th>
                                <th style="width: 20%">Unit Price</th>
                                <th style="width: 15%">Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="items-body">
                            <!-- Items will be added here -->
                        </tbody>
                    </table>
                    @error('items') <div class="alert alert-danger m-3">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>
</form>

<template id="row-template">
    <tr>
        <td>
            <select name="items[INDEX][product_id]" class="form-select form-select-sm product-select" required>
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" name="items[INDEX][quantity]" class="form-control form-select-sm qty-input" min="1" value="1" required>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <span class="input-group-text">$</span>
                <input type="number" name="items[INDEX][unit_price]" class="form-control price-input" step="0.01" min="0" value="0.00" required>
            </div>
        </td>
        <td class="row-total">$0.00</td>
        <td>
            <button type="button" class="btn btn-sm text-danger btn-remove"><i class="fas fa-trash"></i></button>
        </td>
    </tr>
</template>

@push('scripts')
<script>
    let rowIndex = 0;
    
    document.getElementById('btn-add-item').addEventListener('click', function() {
        addItemRow();
    });

    // Add initial row
    addItemRow();

    function addItemRow() {
        const template = document.getElementById('row-template');
        const clone = template.content.cloneNode(true);
        const tbody = document.getElementById('items-body');
        
        // Update names with unique index
        const inputs = clone.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.name = input.name.replace('INDEX', rowIndex);
        });
        
        // Add event listeners for calculation
        const row = clone.querySelector('tr');
        const qtyInput = row.querySelector('.qty-input');
        const priceInput = row.querySelector('.price-input');
        const removeBtn = row.querySelector('.btn-remove');
        
        qtyInput.addEventListener('input', () => calculateRow(row));
        priceInput.addEventListener('input', () => calculateRow(row));
        removeBtn.addEventListener('click', () => {
             if(tbody.children.length > 1) {
                 row.remove(); 
                 calculateGrandTotal();
             }
        });
        
        tbody.appendChild(clone);
        rowIndex++;
    }

    function calculateRow(row) {
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const total = qty * price;
        
        row.querySelector('.row-total').textContent = '$' + total.toFixed(2);
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('#items-body tr').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            grandTotal += qty * price;
        });
        
        document.getElementById('grand-total').textContent = '$' + grandTotal.toFixed(2);
    }
</script>
@endpush
@endsection

@extends('layouts.admin')

@section('title', 'POS - Point of Sale')

@section('content')
<div class="row h-100">
    <!-- Left: Product List -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <form action="{{ route('pos.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('messages.search_placeholder') }}" value="{{ request('search') }}" autofocus>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    @if(request('search'))
                        <a href="{{ route('pos.index') }}" class="btn btn-outline-secondary">{{ __('messages.clear') }}</a>
                    @endif
                </form>
            </div>
            <div class="card-body overflow-auto" style="height: calc(100vh - 250px);">
                <div class="row g-3">
                    @forelse($products as $product)
                    <div class="col-md-3 col-6">
                        <div class="card h-100 product-card {{ $product->stock <= 0 ? 'opacity-50' : 'cursor-pointer' }}" 
                             @if($product->stock > 0)
                             onclick="addToCart({{ json_encode($product) }})"
                             @endif
                             >
                            <div class="card-body text-center p-3">
                                <div class="bg-light rounded mb-2 d-flex align-items-center justify-content-center" style="height: 100px;">
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="img-fluid" style="max-height: 100px;">
                                    @else
                                        <i class="fas fa-box fa-3x text-muted"></i>
                                    @endif
                                </div>
                                <h6 class="card-title text-truncate mb-1" title="{{ $product->name }}">{{ $product->name }}</h6>
                                <div class="fw-bold text-primary">${{ number_format($product->price, 2) }}</div>
                                <small class="text-{{ $product->stock > 10 ? 'success' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                    {{ __('messages.stock') }}: {{ $product->stock }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <h4 class="text-muted">{{ __('messages.no_products') }}</h4>
                        <p>{{ __('messages.try_searching_different') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Cart/Checkout -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">{{ __('messages.current_order') }}</h5>
            </div>
            <div class="card-body p-0 d-flex flex-column" style="height: calc(100vh - 250px);">
                <!-- Cart Items -->
                <div class="flex-grow-1 overflow-auto p-3" id="cart-items">
                    <!-- Cart items will be injected here via JS -->
                    <div class="text-center py-5 text-muted" id="empty-cart-msg">
                        <i class="fas fa-shopping-basket fa-3x mb-3"></i>
                        <p>{{ __('messages.cart_empty') }}</p>
                    </div>
                </div>

                <!-- Totals & Checkout -->
                <div class="border-top p-3 bg-light">
                    <form action="{{ route('pos.store') }}" method="POST" id="checkout-form">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label small">{{ __('messages.customer_optional') }}</label>
                            <select name="customer_id" class="form-select form-select-sm">
                                <option value="">{{ __('messages.walk_in_customer') }}</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-between mb-1">
                            <span>{{ __('messages.subtotal') }}:</span>
                            <span class="fw-bold" id="total-subtotal">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>{{ __('messages.tax') }} (15%):</span>
                            <span class="fw-bold" id="total-tax">$0.00</span>
                            <input type="hidden" name="tax" id="input-tax" value="0">
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>{{ __('messages.discount') }}:</span>
                            <span class="fw-bold text-danger" id="total-discount">-$0.00</span>
                            <input type="hidden" name="discount" id="input-discount" value="0">
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2 mt-2 mb-3">
                            <span class="fs-5 fw-bold">{{ __('messages.total') }}:</span>
                            <span class="fs-5 fw-bold text-primary" id="final-total">$0.00</span>
                        </div>
                        
                        <!-- Hidden inputs for items -->
                        <div id="form-items-container"></div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-secondary" onclick="clearCart()">{{ __('messages.cancel') }}</button>
                            <button type="button" class="btn btn-success btn-lg" id="btn-checkout" onclick="openCheckoutModal()" disabled>
                                {{ __('messages.checkout') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">{{ __('messages.payment') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <div class="text-center mb-4">
                    <div class="text-muted small mb-1">{{ __('messages.total') }}</div>
                    <h1 class="fw-bold text-primary mb-0" id="modal-total-display">$0.00</h1>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">{{ __('messages.payment_method') }}</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="modal_payment_method" id="pay_cash" value="cash" checked onchange="togglePaymentInputs()">
                            <label class="btn btn-outline-primary w-100 py-3" for="pay_cash">
                                <i class="fas fa-money-bill-wave d-block mb-1"></i>
                                {{ __('messages.cash') }}
                            </label>
                        </div>
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="modal_payment_method" id="pay_card" value="card" onchange="togglePaymentInputs()">
                            <label class="btn btn-outline-primary w-100 py-3" for="pay_card">
                                <i class="fas fa-credit-card d-block mb-1"></i>
                                {{ __('messages.card') }}
                            </label>
                        </div>
                    </div>
                </div>

                <div id="cash-inputs">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">{{ __('messages.received_amount') }}</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white border-end-0">$</span>
                            <input type="number" step="0.01" class="form-control border-start-0 ps-0" id="modal-received-amount" oninput="calculateChange()">
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded d-flex justify-content-between align-items-center">
                        <span class="fw-bold">{{ __('messages.change_amount') }}:</span>
                        <h3 class="mb-0 text-success fw-bold" id="modal-change-display">$0.00</h3>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn btn-primary btn-lg px-5" onclick="submitOrder()">{{ __('messages.complete_order') }}</button>
            </div>
        </div>
    </div>
</div>

<template id="cart-item-template">
    <div class="d-flex align-items-center justify-content-between border-bottom py-2 cart-item">
        <div class="flex-grow-1">
            <h6 class="mb-0 product-name">Product Name</h6>
            <small class="text-muted product-price">$0.00</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-outline-danger btn-decrease">-</button>
            <span class="fw-bold px-2 qty-display">1</span>
            <button type="button" class="btn btn-sm btn-outline-success btn-increase">+</button>
        </div>
        <div class="ms-3 fw-bold product-subtotal">$0.00</div>
        <button type="button" class="btn btn-sm text-danger ms-2 btn-remove">
            <i class="fas fa-times"></i>
        </button>
    </div>
</template>

@push('scripts')
<script>
    let cart = [];
    const taxRate = 0.15; // 15%
    
    function addToCart(product) {
        const existingItem = cart.find(item => item.id === product.id);
        
        if (existingItem) {
            if (existingItem.quantity < product.stock) {
                existingItem.quantity++;
            } else {
                alert('{{ __('messages.max_stock_reached') }}');
            }
        } else {
            cart.push({
                id: product.id,
                name: product.name,
                price: parseFloat(product.price),
                quantity: 1,
                stock: product.stock
            });
        }
        
        updateCartUI();
    }

    function removeFromCart(id) {
        cart = cart.filter(item => item.id !== id);
        updateCartUI();
    }

    function updateQuantity(id, change) {
        const item = cart.find(item => item.id === id);
        if (item) {
            const newQty = item.quantity + change;
            if (newQty > 0 && newQty <= item.stock) {
                item.quantity = newQty;
            } else if (newQty > item.stock) {
                alert('{{ __('messages.max_stock_reached') }}');
            }
            updateCartUI();
        }
    }

    function clearCart() {
        if(confirm('{{ __('messages.clear_cart_confirm') }}')) {
            cart = [];
            updateCartUI();
        }
    }

    function updateCartUI() {
        const cartContainer = document.getElementById('cart-items');
        const emptyMsg = document.getElementById('empty-cart-msg');
        const template = document.getElementById('cart-item-template');
        const formItemsContainer = document.getElementById('form-items-container');
        const checkoutBtn = document.getElementById('btn-checkout');
        
        cartContainer.innerHTML = '';
        formItemsContainer.innerHTML = '';
        
        if (cart.length === 0) {
            cartContainer.appendChild(emptyMsg);
            checkoutBtn.disabled = true;
            updateTotals();
            return;
        }
        
        checkoutBtn.disabled = false;
        
        cart.forEach((item, index) => {
            const clone = template.content.cloneNode(true);
            const el = clone.querySelector('.cart-item');
            
            el.querySelector('.product-name').textContent = item.name;
            el.querySelector('.product-price').textContent = '$' + item.price.toFixed(2);
            el.querySelector('.qty-display').textContent = item.quantity;
            el.querySelector('.product-subtotal').textContent = '$' + (item.price * item.quantity).toFixed(2);
            
            el.querySelector('.btn-decrease').onclick = () => updateQuantity(item.id, -1);
            el.querySelector('.btn-increase').onclick = () => updateQuantity(item.id, 1);
            el.querySelector('.btn-remove').onclick = () => removeFromCart(item.id);
            
            cartContainer.appendChild(clone);
            
            // Add hidden inputs for form submission
            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = `items[${index}][product_id]`;
            inputId.value = item.id;
            
            const inputQty = document.createElement('input');
            inputQty.type = 'hidden';
            inputQty.name = `items[${index}][quantity]`;
            inputQty.value = item.quantity;
            
            const inputPrice = document.createElement('input');
            inputPrice.type = 'hidden';
            inputPrice.name = `items[${index}][unit_price]`;
            inputPrice.value = item.price;
            
            formItemsContainer.appendChild(inputId);
            formItemsContainer.appendChild(inputQty);
            formItemsContainer.appendChild(inputPrice);
        });
        
        updateTotals();
    }

    function updateTotals() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const tax = subtotal * taxRate;
        const total = subtotal + tax;
        
        document.getElementById('total-subtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('total-tax').textContent = '$' + tax.toFixed(2);
        document.getElementById('final-total').textContent = '$' + total.toFixed(2);
        document.getElementById('modal-total-display').textContent = '$' + total.toFixed(2);
        
        document.getElementById('input-tax').value = tax.toFixed(2);
    }

    function openCheckoutModal() {
        const modal = new bootstrap.Modal(document.getElementById('checkoutModal'));
        const totalStr = document.getElementById('final-total').textContent.replace('$', '');
        document.getElementById('modal-received-amount').value = totalStr;
        calculateChange();
        modal.show();
    }

    function togglePaymentInputs() {
        const method = document.querySelector('input[name="modal_payment_method"]:checked').value;
        const cashInputs = document.getElementById('cash-inputs');
        if (method === 'card') {
            cashInputs.classList.add('d-none');
        } else {
            cashInputs.classList.remove('d-none');
        }
    }

    function calculateChange() {
        const total = parseFloat(document.getElementById('final-total').textContent.replace('$', ''));
        const received = parseFloat(document.getElementById('modal-received-amount').value) || 0;
        const change = Math.max(0, received - total);
        document.getElementById('modal-change-display').textContent = '$' + change.toFixed(2);
    }

    function submitOrder() {
        const form = document.getElementById('checkout-form');
        const method = document.querySelector('input[name="modal_payment_method"]:checked').value;
        const received = parseFloat(document.getElementById('modal-received-amount').value) || 0;
        const total = parseFloat(document.getElementById('final-total').textContent.replace('$', ''));
        
        if (method === 'cash' && received < total) {
            alert('Received amount must be greater than or equal to total.');
            return;
        }

        // Add hidden inputs for payment fields
        const inputMethod = document.createElement('input');
        inputMethod.type = 'hidden';
        inputMethod.name = 'payment_method';
        inputMethod.value = method;
        
        const inputReceived = document.createElement('input');
        inputReceived.type = 'hidden';
        inputReceived.name = 'received_amount';
        inputReceived.value = method === 'cash' ? received : total;
        
        const inputChange = document.createElement('input');
        inputChange.type = 'hidden';
        inputChange.name = 'change_amount';
        inputChange.value = method === 'cash' ? Math.max(0, received - total) : 0;
        
        form.appendChild(inputMethod);
        form.appendChild(inputReceived);
        form.appendChild(inputChange);
        
        form.submit();
    }
</script>
@endpush

@push('styles')
<style>
    .cursor-pointer { cursor: pointer; }
    .product-card:hover { transform: translateY(-2px); transition: transform 0.2s; border-color: #0d6efd; }
</style>
@endpush
@endsection

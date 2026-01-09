<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected ProductRepositoryInterface $productRepository,
        protected CustomerRepositoryInterface $customerRepository
    ) {}

    /**
     * Display the POS interface.
     */
    public function index(Request $request)
    {
        $query = $request->get('search');
        $products = $query 
            ? $this->productRepository->search($query)
            : $this->productRepository->all();
        
        $customers = $this->customerRepository->all();

        return view('pos.index', compact('products', 'customers'));
    }

    /**
     * Process a sale/order from POS.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'tax' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card',
            'received_amount' => 'required|numeric|min:0',
            'change_amount' => 'required|numeric|min:0',
        ]);

        $orderData = [
            'customer_id' => $request->customer_id,
            'user_id' => auth()->id(),
            'tax' => $request->tax,
            'discount' => $request->discount,
            'payment_method' => $request->payment_method,
            'received_amount' => $request->received_amount,
            'change_amount' => $request->change_amount,
        ];

        $order = $this->orderService->createOrder($orderData, $request->items);

        return redirect()->route('pos.index')
            ->with('success', 'Sale completed successfully. Order #' . $order->id);
    }
}

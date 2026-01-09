<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Services\PurchaseService;
use App\Repositories\Contracts\PurchaseRepositoryInterface;
use App\Repositories\Contracts\SupplierRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;

class PurchaseController extends Controller
{
    public function __construct(
        protected PurchaseService $purchaseService,
        protected PurchaseRepositoryInterface $purchaseRepository,
        protected SupplierRepositoryInterface $supplierRepository,
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function index()
    {
        $purchases = $this->purchaseRepository->all();
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = $this->supplierRepository->all();
        $products = $this->productRepository->all();
        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(PurchaseRequest $request)
    {
        $purchaseData = [
            'supplier_id' => $request->supplier_id,
            'date' => $request->date,
        ];

        $this->purchaseService->createPurchase($purchaseData, $request->items);

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase created successfully and stock updated.');
    }

    public function show(int $id)
    {
        $purchase = $this->purchaseRepository->find($id);
        return view('purchases.show', compact('purchase'));
    }

    public function destroy(int $id)
    {
        $this->purchaseRepository->delete($id);

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase deleted successfully.');
    }
}

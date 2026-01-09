<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Repositories\Contracts\SupplierRepositoryInterface;

class SupplierController extends Controller
{
    public function __construct(
        protected SupplierRepositoryInterface $supplierRepository
    ) {}

    public function index()
    {
        $suppliers = $this->supplierRepository->all();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(SupplierRequest $request)
    {
        $this->supplierRepository->create($request->validated());

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    public function show(int $id)
    {
        $supplier = $this->supplierRepository->find($id);
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(int $id)
    {
        $supplier = $this->supplierRepository->find($id);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(SupplierRequest $request, int $id)
    {
        $this->supplierRepository->update($id, $request->validated());

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->supplierRepository->delete($id);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
}

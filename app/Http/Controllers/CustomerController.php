<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(
        protected CustomerRepositoryInterface $customerRepository
    ) {}

    public function index(Request $request)
    {
        $query = $request->get('search');
        $customers = $query 
            ? $this->customerRepository->search($query)
            : $this->customerRepository->all();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(CustomerRequest $request)
    {
        $this->customerRepository->create($request->validated());

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function show(int $id)
    {
        $customer = $this->customerRepository->find($id);
        return view('customers.show', compact('customer'));
    }

    public function edit(int $id)
    {
        $customer = $this->customerRepository->find($id);
        return view('customers.edit', compact('customer'));
    }

    public function update(CustomerRequest $request, int $id)
    {
        $this->customerRepository->update($id, $request->validated());

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->customerRepository->delete($id);

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}

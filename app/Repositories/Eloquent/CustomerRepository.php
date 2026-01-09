<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function all(): Collection
    {
        return Customer::all();
    }

    public function find(int $id): ?Model
    {
        return Customer::with('orders')->find($id);
    }

    public function create(array $data): Model
    {
        return Customer::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $customer = Customer::find($id);
        return $customer ? $customer->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $customer = Customer::find($id);
        return $customer ? $customer->delete() : false;
    }

    public function search(string $query): Collection
    {
        return Customer::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->get();
    }
}

<?php

namespace App\Repositories\Eloquent;

use App\Models\Supplier;
use App\Repositories\Contracts\SupplierRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function all(): Collection
    {
        return Supplier::all();
    }

    public function find(int $id): ?Model
    {
        return Supplier::with('purchases')->find($id);
    }

    public function create(array $data): Model
    {
        return Supplier::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $supplier = Supplier::find($id);
        return $supplier ? $supplier->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $supplier = Supplier::find($id);
        return $supplier ? $supplier->delete() : false;
    }
}

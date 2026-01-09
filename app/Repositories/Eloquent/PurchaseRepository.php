<?php

namespace App\Repositories\Eloquent;

use App\Models\Purchase;
use App\Repositories\Contracts\PurchaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PurchaseRepository implements PurchaseRepositoryInterface
{
    public function all(): Collection
    {
        return Purchase::with(['supplier', 'items.product'])->latest()->get();
    }

    public function find(int $id): ?Model
    {
        return Purchase::with(['supplier', 'items.product'])->find($id);
    }

    public function create(array $data): Model
    {
        return Purchase::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $purchase = Purchase::find($id);
        return $purchase ? $purchase->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $purchase = Purchase::find($id);
        return $purchase ? $purchase->delete() : false;
    }
}

<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class OrderRepository implements OrderRepositoryInterface
{
    public function all(): Collection
    {
        return Order::with(['customer', 'user', 'items.product'])
            ->latest()
            ->get();
    }

    public function find(int $id): ?Model
    {
        return Order::with(['customer', 'user', 'items.product'])->find($id);
    }

    public function create(array $data): Model
    {
        return Order::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $order = Order::find($id);
        return $order ? $order->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $order = Order::find($id);
        return $order ? $order->delete() : false;
    }

    public function getByStatus(string $status): Collection
    {
        return Order::where('status', $status)
            ->with(['customer', 'user', 'items.product'])
            ->latest()
            ->get();
    }

    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return Order::whereBetween('created_at', [$startDate, $endDate])
            ->with(['customer', 'user', 'items.product'])
            ->latest()
            ->get();
    }

    public function getRecent(int $limit, ?int $userId = null): Collection
    {
        $query = Order::with(['customer', 'user', 'items.product'])->latest();

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->take($limit)->get();
    }
}

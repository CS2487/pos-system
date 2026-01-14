<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface OrderRepositoryInterface
{
    public function all(): LengthAwarePaginator;
    
    public function find(int $id): ?Model;
    
    public function create(array $data): Model;
    
    public function update(int $id, array $data): bool;
    
    public function delete(int $id): bool;
    
    public function getByStatus(string $status): LengthAwarePaginator;
    
    public function getByDateRange(string $startDate, string $endDate): LengthAwarePaginator;

    public function getRecent(int $limit, ?int $userId = null): Collection;
}

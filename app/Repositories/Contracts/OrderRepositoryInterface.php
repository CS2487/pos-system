<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface OrderRepositoryInterface
{
    public function all(): Collection;
    
    public function find(int $id): ?Model;
    
    public function create(array $data): Model;
    
    public function update(int $id, array $data): bool;
    
    public function delete(int $id): bool;
    
    public function getByStatus(string $status): Collection;
    
    public function getByDateRange(string $startDate, string $endDate): Collection;
}

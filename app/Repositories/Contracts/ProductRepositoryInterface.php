<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface ProductRepositoryInterface
{
    public function all(): Collection;
    
    public function find(int $id): ?Model;
    
    public function create(array $data): Model;
    
    public function update(int $id, array $data): bool;
    
    public function delete(int $id): bool;
    
    public function search(string $query): Collection;
    
    public function lowStock(int $threshold = 10): Collection;
    
    public function updateStock(int $productId, int $quantity): bool;
}

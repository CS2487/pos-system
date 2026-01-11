<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\ProductService;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;

class DashboardController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected ProductService $productService,
        protected ProductRepositoryInterface $productRepository,
        protected OrderRepositoryInterface $orderRepository
    ) {}

    /**
     * Display the dashboard with statistics.
     */
    public function index()
    {
        $user = auth()->user();
        $userId = $user->role === 'admin' ? null : $user->id;

        $stats = $this->orderService->getSalesStats($userId);
        $lowStockProducts = $this->productService->getLowStockProducts(10);
        $topProducts = $this->productService->getTopProducts(5);
        $recentOrders = $this->orderRepository->getRecent(10, $userId);

        return view('dashboard.index', compact('stats', 'lowStockProducts', 'topProducts', 'recentOrders'));
    }
}

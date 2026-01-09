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
        $stats = $this->orderService->getSalesStats();
        $lowStockProducts = $this->productService->getLowStockProducts(10);
        $topProducts = $this->productService->getTopProducts(5);
        $recentOrders = $this->orderRepository->all()->take(10);

        return view('dashboard.index', compact('stats', 'lowStockProducts', 'topProducts', 'recentOrders'));
    }
}

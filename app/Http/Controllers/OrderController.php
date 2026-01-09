<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Services\OrderService;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected OrderService $orderService
    ) {}

    public function index()
    {
        $orders = $this->orderRepository->all();
        return view('orders.index', compact('orders'));
    }

    public function show(int $id)
    {
        $order = $this->orderRepository->find($id);
        return view('orders.show', compact('order'));
    }

    public function update(OrderRequest $request, int $id)
    {
        $this->orderRepository->update($id, $request->validated());

        return redirect()->route('orders.index')
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->orderRepository->delete($id);

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }

    public function invoice(int $id)
    {
        $order = $this->orderRepository->find($id);
        
        $pdf = Pdf::loadView('orders.invoice', compact('order'));
        
        return $pdf->download('invoice-'.$order->id.'.pdf');
    }

    public function returnOrder(int $id)
    {
        try {
            $order = Order::findOrFail($id);
            $this->orderService->returnOrder($order);

            return redirect()->route('orders.index')
                ->with('success', 'Order #' . $id . ' has been returned successfully.');
        } catch (\Exception $e) {
            return redirect()->route('orders.index')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function print(int $id)
    {
        $order = $this->orderRepository->find($id);
        return view('orders.print', compact('order'));
    }
}

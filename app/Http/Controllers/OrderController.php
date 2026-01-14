<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\OrderService;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    use AuthorizesRequests;
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
        $this->authorize('view', $order);
        return view('orders.show', compact('order'));
    }

    public function update(UpdateOrderRequest $request, int $id) 
    { 
        $order = $this->orderRepository->find($id);
        $this->authorize('update', $order);

        // Security: Only pass strictly validated data and the ID from the route
        $data = $request->validated();
        $data['id'] = $id; 

        $this->orderService->updateOrder($data);
        return redirect()->route('orders.index')
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(int $id)
    {
        $order = $this->orderRepository->find($id);
        $this->authorize('delete', $order);
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

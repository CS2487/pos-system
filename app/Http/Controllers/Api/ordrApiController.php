<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;

class ordrApiController extends Controller
{
    // تعريف المتغير ليكون متاحاً داخل الدوال الأخرى
    protected OrderService $orderService;

    // هنا يحدث الحقن التلقائي
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function create(Request $request)
    {
        $this->orderService->createOrder($request->all(), $request->items);
    }

    public function update(Request $request)
    {
        $order = $this->orderService->updateOrder($request->all());
        if ($order > 0) {
            return response()->json([
                'message' => 'Order updated successfully.', 200]);
        } else {
            return response()->json([
                'message' => '', 403,
            ]);
        }
    }
}

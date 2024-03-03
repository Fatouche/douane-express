<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\OrderFromRequest;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;

class OrdersController extends Controller
{

    public function getAll()
    {
        return Order::OrderBy('created_at', 'desc')->get();
    }

    public function add(OrderFromRequest $request)
    {
        try {
            $order = $request->validated();
            $order = $request->merge(['order_number' => date('YmdHis') . mt_rand(100000, 999999)])->all();
            $newOrder = Order::create($order);
            return response()->json([
                'message' => 'order created successfully !',
                'order' => $newOrder
            ], 201);
        } catch (\Exception $err) {
            return response()->json(['message' => $err], 404);
        }
    }

    public function getOne(string $id)
    {
        $order = Order::with('services')->find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        $total_price = $order->services->sum('pivot.total_price');
        return response()->json([
            'order' => $order,
            'total_price' => $total_price
        ], 201);
    }

    public function update(OrderFromRequest $request, string $id)
    {
        try {
            $order = Order::find($id);

            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }
            $order->update($request->validated());
        } catch (\Exception $err) {
            return $err;
        }
    }

    public function delete(string $id)
    {
        try {
            $order = Order::find($id);

            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            $order->delete();
            return response()->json([
                'message' => 'Order removed successfully !'
            ], 200);
        } catch (\Exception $err) {
            return $err;
        }
    }
}

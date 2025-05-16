<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Events\OrderPlaced;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        DB::beginTransaction();

        try {
            $order = Order::create();

            foreach ($validated['products'] as $item) {
                $product = Product::findOrFail($item['id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                $product->decrement('stock', $item['quantity']);

                $order->products()->attach($product->id, ['quantity' => $item['quantity']]);
            }

            DB::commit();

            event(new OrderPlaced($order));

            return response()->json(['message' => 'Order placed successfully.', 'order_id' => $order->id], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function show($id)
    {
        $order = Order::with('products')->findOrFail($id);

        $total = $order->products->sum(function ($product) {
            return $product->pivot->quantity * $product->price;
        });

        return response()->json([
            'id' => $order->id,
            'created_at' => $order->created_at,
            'products' => $order->products->map(function ($product) {
                return [
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $product->pivot->quantity,
                    'subtotal' => $product->pivot->quantity * $product->price,
                ];
            }),
            'total' => $total
        ]);
    }
}

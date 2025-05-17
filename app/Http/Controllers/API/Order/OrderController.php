<?php

namespace App\Http\Controllers\API\Order;

use App\Events\OrderPlaced;
use App\Http\Controllers\API\Validator;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\StoreOrderRequest;
use App\Http\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->get(); // TODO Paginate

        return response()->json(OrderResource::collection($orders));
    }

    public function store(StoreOrderRequest $request)
    {
        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => auth()->id()
            ]);

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                $product->decrement('stock', $item['quantity']);

                $order->products()->attach($product->id, ['quantity' => $item['quantity']]); // Todo store price and total ...
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
        $order = Order::where('user_id', auth()->id())->with('products')->findOrFail($id);

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

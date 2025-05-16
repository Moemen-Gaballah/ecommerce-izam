<?php

namespace App\Http\Controllers\API\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $key = 'products_' . md5(json_encode($request->all()));
        $products = Cache::remember($key, 60, function () use ($request) {
            $query = Product::query();

            // TODO Refactor
            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->filled('min_price')) {
                $query->where('price', '>=', $request->min_price);
            }

            if ($request->filled('max_price')) {
                $query->where('price', '<=', $request->max_price);
            }

            return $query->paginate(10);
        });

        return $this->sendResponse($products);
    }
}

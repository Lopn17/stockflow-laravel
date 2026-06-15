<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::search($query)
            ->with('category')
            ->take(5)
            ->get()
            ->map(fn($p) => [
                'type'     => 'product',
                'id'       => $p->id,
                'label'    => $p->name,
                'sublabel' => $p->sku,
                'url'      => route('products.show', $p),
            ]);

        $categories = Category::where('name', 'like', "%{$query}%")
            ->take(3)
            ->get()
            ->map(fn($c) => [
                'type'     => 'category',
                'id'       => $c->id,
                'label'    => $c->name,
                'sublabel' => 'Category',
                'url'      => route('categories.index', ['search' => $c->name]),
            ]);

        $suppliers = Supplier::where('company_name', 'like', "%{$query}%")
            ->take(3)
            ->get()
            ->map(fn($s) => [
                'type'     => 'supplier',
                'id'       => $s->id,
                'label'    => $s->company_name,
                'sublabel' => 'Supplier',
                'url'      => route('suppliers.show', $s),
            ]);

        return response()->json(
            $products->merge($categories)->merge($suppliers)->values()
        );
    }
}
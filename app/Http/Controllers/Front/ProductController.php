<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ThemeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private readonly ThemeService $theme) {}

    public function index(Request $request): View
    {
        $query = Product::active()->latest();

        if ($request->filled('category')) {
            $query->where('category', $request->string('category')->toString());
        }

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where('name', 'like', '%'.$search.'%');
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Product::active()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view($this->theme->view('products.index'), compact('products', 'categories'));
    }

    public function show(Product $product): View
    {
        abort_if(! $product->is_active, 404);

        $relatedProducts = Product::active()
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view($this->theme->view('products.show'), compact('product', 'relatedProducts'));
    }
}

<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use App\Services\ThemeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cart,
        private readonly ThemeService $theme,
    ) {}

    public function index(): View
    {
        $items = $this->cart->all();
        $total = $this->cart->total();

        return view($this->theme->view('cart.index'), compact('items', 'total'));
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        abort_if(! $product->is_active, 404);

        $qty = max(1, (int) $request->input('quantity', 1));

        $this->cart->add(
            productId: $product->id,
            name: $product->name,
            slug: $product->slug,
            price: (float) $product->price,
            image: $product->getFirstImageUrl(),
            qty: $qty,
        );

        return redirect()->route('cart.index')
            ->with('success', "{$product->name} berhasil ditambahkan ke keranjang.");
    }

    public function update(Request $request, int $productId): RedirectResponse
    {
        $qty = (int) $request->input('quantity', 1);

        $this->cart->updateQuantity($productId, $qty);

        return redirect()->route('cart.index')
            ->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function remove(int $productId): RedirectResponse
    {
        $this->cart->remove($productId);

        return redirect()->route('cart.index')
            ->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}

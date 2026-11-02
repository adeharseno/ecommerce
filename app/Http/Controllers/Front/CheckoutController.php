<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\StoreCheckoutRequest;
use App\Http\Requests\Front\UploadPaymentProofRequest;
use App\Models\Transaction;
use App\Services\CartService;
use App\Services\InvoiceService;
use App\Services\ThemeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cart,
        private readonly ThemeService $theme,
        private readonly InvoiceService $invoiceService,
    ) {}

    public function index(): View|RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang Anda kosong.');
        }

        $items = $this->cart->all();
        $total = $this->cart->total();

        return view($this->theme->view('checkout.index'), compact('items', 'total'));
    }

    public function store(StoreCheckoutRequest $request): RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang Anda kosong.');
        }

        // Snapshot the cart before clearing it inside the transaction
        $cartItems = $this->cart->all();
        $totalAmount = $this->cart->total();

        try {
            $invoiceNumber = DB::transaction(function () use ($request, $cartItems, $totalAmount): string {
                $invoiceNumber = $this->invoiceService->generate();

                $transaction = Transaction::create([
                    ...$request->safe()->only([
                        'customer_name',
                        'customer_phone',
                        'shipping_address',
                    ]),
                    'invoice_number' => $invoiceNumber,
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                ]);

                $itemRows = array_map(fn (array $item): array => [
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ], $cartItems);

                // Bulk insert is more efficient than N individual queries
                $transaction->items()->insert($itemRows);

                return $invoiceNumber;
            });

            $this->cart->clear();

            return redirect()->route('checkout.success', $invoiceNumber);
        } catch (Throwable $e) {
            Log::error('Checkout failed', [
                'error' => $e->getMessage(),
                'cart' => $cartItems,
            ]);

            return redirect()->route('checkout.index')
                ->with('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
        }
    }

    public function success(string $invoice): View
    {
        $transaction = Transaction::with('items')
            ->where('invoice_number', $invoice)
            ->firstOrFail();

        return view($this->theme->view('checkout.success'), compact('transaction'));
    }

    public function uploadProof(UploadPaymentProofRequest $request, string $invoice): RedirectResponse
    {
        $transaction = Transaction::where('invoice_number', $invoice)
            ->where('status', 'pending')
            ->firstOrFail();

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $transaction->update([
            'payment_proof' => $path,
            'status' => 'paid',
        ]);

        return redirect()->route('checkout.success', $invoice)
            ->with('success', 'Bukti pembayaran berhasil diunggah. Pesanan Anda sedang diproses.');
    }
}

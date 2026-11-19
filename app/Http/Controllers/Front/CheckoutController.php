<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\StoreCheckoutRequest;
use App\Http\Requests\Front\UploadPaymentProofRequest;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\CartService;
use App\Services\InvoiceService;
use App\Services\ThemeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;
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
        $bank = config('sumatra-payment.bank', []);

        return view($this->theme->view('checkout.index'), compact('items', 'total', 'bank'));
    }

    public function store(StoreCheckoutRequest $request): RedirectResponse
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang Anda kosong.');
        }

        $cartItems = $this->cart->all();
        $bank = config('sumatra-payment.bank', []);

        try {
            $invoiceNumber = DB::transaction(function () use ($request, $cartItems, $bank): string {
                // 1. Stock validation with row lock (prevents race conditions)
                $productIds = array_column($cartItems, 'id');
                $products = Product::whereIn('id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $totalAmount = 0.0;
                $itemRows = [];

                foreach ($cartItems as $item) {
                    $productId = $item['id'];
                    $qty = (int) $item['quantity'];

                    /** @var Product|null $product */
                    $product = $products->get($productId);

                    if (! $product || ! $product->is_active) {
                        throw new RuntimeException("Produk '{$item['name']}' saat ini tidak tersedia.");
                    }

                    if ($product->stock < $qty) {
                        throw new RuntimeException("Stok untuk produk '{$product->name}' tidak mencukupi (tersedia: {$product->stock}).");
                    }

                    // Decrement stock atomically
                    $product->decrement('stock', $qty);

                    // Recalculate price using actual DB price to prevent client tampering
                    $subtotal = (float) $product->price * $qty;
                    $totalAmount += $subtotal;

                    $itemRows[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $qty,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // 2. Generate unique invoice number with lockForUpdate sequence
                $invoiceNumber = $this->invoiceService->generate();

                // 3. Create Transaction with initial pending status and official bank details
                $transaction = Transaction::create([
                    'customer_name' => $request->validated('customer_name'),
                    'customer_phone' => $request->validated('customer_phone'),
                    'shipping_address' => $request->validated('shipping_address'),
                    'invoice_number' => $invoiceNumber,
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                    'bank_name' => $bank['name'] ?? 'BRI',
                    'account_holder' => $bank['account_holder'] ?? 'AKRATAMA SADAWIRA GRUP',
                ]);

                // 4. Attach items
                foreach ($itemRows as &$row) {
                    $row['transaction_id'] = $transaction->id;
                }
                unset($row);

                $transaction->items()->insert($itemRows);

                return $invoiceNumber;
            });

            // 5. Store session flag to authorize customer on success page (PII security)
            session()->put('order_access_'.$invoiceNumber, true);
            session()->put('last_order_invoice', $invoiceNumber);

            // 6. Clear cart ONLY after successful transaction commit
            $this->cart->clear();

            return redirect()->route('checkout.success', $invoiceNumber);
        } catch (Throwable $e) {
            Log::error('Checkout failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $errorMessage = $e instanceof RuntimeException
                ? $e->getMessage()
                : 'Terjadi kesalahan saat memproses pesanan. Silakan periksa kembali data Anda.';

            return redirect()->route('checkout.index')
                ->withInput()
                ->with('error', $errorMessage);
        }
    }

    public function success(Request $request, string $invoice): View|RedirectResponse
    {
        $transaction = Transaction::with('items')
            ->where('invoice_number', $invoice)
            ->firstOrFail();

        $sessionKey = 'order_access_'.$invoice;
        $isAuthorized = (bool) session($sessionKey) || auth()->check();

        // Allow customer to unlock details with their phone number if accessed from new device/session
        if (! $isAuthorized && $request->filled('verify_phone')) {
            $normalizePhone = function (?string $phone): string {
                $digits = preg_replace('/\D/', '', (string) $phone);
                if (str_starts_with($digits, '62')) {
                    $digits = substr($digits, 2);
                }

                return ltrim($digits, '0');
            };

            $normInput = $normalizePhone($request->input('verify_phone'));
            $normActual = $normalizePhone($transaction->customer_phone);

            if (! empty($normInput) && ($normInput === $normActual || str_ends_with($normActual, $normInput) || str_ends_with($normInput, $normActual))) {
                session()->put($sessionKey, true);
                $isAuthorized = true;
            } else {
                return back()->with('error', 'Nomor telepon tidak cocok dengan data pesanan.');
            }
        }

        $displayTransaction = clone $transaction;

        if (! $isAuthorized) {
            // Mask sensitive customer PII to prevent data leaks to arbitrary visitors
            $name = $transaction->customer_name;
            $displayTransaction->customer_name = strlen($name) > 3 ? Str::mask($name, '*', 2, -1) : Str::mask($name, '*', 1);
            $displayTransaction->customer_phone = Str::mask($transaction->customer_phone, '*', 3, -3);
            $displayTransaction->shipping_address = 'Alamat disamarkan demi privasi pelanggan.';
        }

        $bank = config('sumatra-payment.bank', []);

        return view($this->theme->view('checkout.success'), [
            'transaction' => $displayTransaction,
            'isAuthorized' => $isAuthorized,
            'bank' => $bank,
        ]);
    }

    public function uploadProof(UploadPaymentProofRequest $request, string $invoice): RedirectResponse
    {
        $transaction = Transaction::where('invoice_number', $invoice)
            ->where('status', 'pending')
            ->firstOrFail();

        // Prevent unauthorized visitors from uploading payment proof
        if (! session('order_access_'.$invoice) && ! auth()->check()) {
            return redirect()->route('checkout.success', $invoice)
                ->with('error', 'Sesi Anda telah berakhir. Harap verifikasi nomor telepon untuk mengunggah bukti pembayaran.');
        }

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        // Status remains 'pending' awaiting admin verification (do not automatically mark as paid)
        $transaction->update([
            'payment_proof' => $path,
        ]);

        return redirect()->route('checkout.success', $invoice)
            ->with('success', 'Bukti pembayaran berhasil diunggah. Admin akan segera memverifikasi pembayaran Anda.');
    }
}

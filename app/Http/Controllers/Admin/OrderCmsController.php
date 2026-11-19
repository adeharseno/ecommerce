<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class OrderCmsController extends Controller
{
    private const STATUSES = ['pending', 'paid', 'processing', 'completed', 'cancelled'];

    private const TRANSITIONS = [
        'pending' => ['paid', 'cancelled'],
        'paid' => ['processing'],
        'processing' => ['completed'],
        'completed' => [],
        'cancelled' => [],
    ];

    private function model(): string
    {
        $model = (string) config('sumatra-orders.model');
        abort_unless(class_exists($model) && is_subclass_of($model, Model::class), 503, 'Model transaksi belum dikonfigurasi.');

        return $model;
    }

    private function order(string $invoice)
    {
        $model = $this->model();

        // Invoice is the checkout route's existing public identifier.
        return $model::query()->where('invoice_number', $invoice)->firstOrFail();
    }

    public function index(Request $request)
    {
        $filters = $request->validate([
            'status' => ['nullable', Rule::in(self::STATUSES)],
            'q' => ['nullable', 'string', 'max:100'],
        ]);
        $model = $this->model();
        $orders = $model::query()
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->when($filters['q'] ?? null, function ($query, $term) {
                $query->where(function ($nested) use ($term) {
                    $nested->where('invoice_number', 'like', '%'.$term.'%')
                        ->orWhere('customer_name', 'like', '%'.$term.'%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();
        $counts = $model::query()->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    public function show(string $invoice)
    {
        $order = $this->order($invoice)->load('items');
        $transitions = self::TRANSITIONS[$order->status] ?? [];
        $proofColumn = (string) config('sumatra-orders.proof_column');
        $proofPath = $order->getAttribute($proofColumn);
        $proofExists = is_string($proofPath) && $proofPath !== '' && Storage::disk(config('sumatra-orders.proof_disk'))->exists($proofPath);

        return view('admin.orders.show', compact('order', 'transitions', 'proofExists'));
    }

    public function updateStatus(Request $request, string $invoice)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(self::STATUSES)],
            'bank_verified' => ['nullable', 'accepted'],
        ]);
        $model = $this->model();
        DB::transaction(function () use ($model, $invoice, $validated, $request) {
            $order = $model::query()->where('invoice_number', $invoice)->lockForUpdate()->firstOrFail();
            abort_unless(in_array($validated['status'], self::TRANSITIONS[$order->status] ?? [], true), 422, 'Perubahan status tidak diizinkan.');
            if ($validated['status'] === 'paid') {
                abort_unless($request->boolean('bank_verified'), 422, 'Cocokkan mutasi rekening bank terlebih dahulu.');
            }
            $order->status = $validated['status'];
            $order->save();
        });

        return redirect()->route('admin.orders.show', ['invoice' => $invoice])->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function proof(string $invoice)
    {
        $order = $this->order($invoice);
        $path = $order->getAttribute((string) config('sumatra-orders.proof_column'));
        abort_unless(is_string($path) && $path !== '', 404);
        $disk = Storage::disk(config('sumatra-orders.proof_disk'));
        abort_unless($disk->exists($path), 404);

        // Serve through the authenticated admin route, not via a publicly linked URL.
        return $disk->response($path, null, ['Content-Disposition' => 'inline']);
    }
}

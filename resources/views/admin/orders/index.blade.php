@extends('admin.layouts.app')
@section('title', 'Pesanan / Penjualan')
@section('page_title', 'Pesanan / Penjualan')
@section('content')
<div class="space-y-6">
    <div class="bg-white border border-slate-200 rounded-2xl p-6">
        <h2 class="text-xl font-bold text-slate-900">Daftar Penjualan</h2>
        <p class="text-sm text-slate-500 mt-1">Pantau checkout, verifikasi transfer bank, dan proses pengiriman.</p>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mt-5">
            @foreach (['pending' => 'Menunggu bayar', 'paid' => 'Sudah dibayar', 'processing' => 'Diproses', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $key => $label)
                <a href="{{ route('admin.orders.index', ['status' => $key]) }}" class="rounded-xl border border-slate-200 p-3 hover:bg-slate-50">
                    <p class="text-xs text-slate-500">{{ $label }}</p><p class="text-2xl font-bold mt-1">{{ number_format($counts[$key] ?? 0) }}</p>
                </a>
            @endforeach
        </div>
    </div>
    <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col sm:flex-row gap-3">
        <input aria-label="Cari invoice atau pelanggan" name="q" value="{{ request('q') }}" placeholder="Cari invoice / pelanggan" class="flex-1 rounded-xl border border-slate-300 px-4 py-2.5 text-sm" />
        <select name="status" aria-label="Filter status" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm">
            <option value="">Semua status</option>
            @foreach (['pending' => 'Menunggu bayar', 'paid' => 'Sudah dibayar', 'processing' => 'Diproses', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $key => $label)
                <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm font-semibold">Filter</button>
        <a href="{{ route('admin.orders.index') }}" class="text-sm px-3 py-2.5 text-slate-600">Reset</a>
    </form>
    <div class="bg-white border border-slate-200 rounded-2xl overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-500"><tr><th class="p-4">Invoice</th><th class="p-4">Pelanggan</th><th class="p-4">Tanggal</th><th class="p-4 text-right">Total</th><th class="p-4">Status</th><th class="p-4">Aksi</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($orders as $order)
                <tr class="hover:bg-slate-50">
                    <td class="p-4 font-semibold text-slate-900">{{ $order->invoice_number }}</td>
                    <td class="p-4">{{ $order->customer_name }}</td>
                    <td class="p-4 whitespace-nowrap">{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                    <td class="p-4 text-right whitespace-nowrap">Rp {{ number_format((float) $order->total_amount, 0, ',', '.') }}</td>
                    <td class="p-4"><span class="rounded-lg bg-slate-100 px-2 py-1">{{ ['pending'=>'Menunggu bayar','paid'=>'Sudah dibayar','processing'=>'Diproses','completed'=>'Selesai','cancelled'=>'Dibatalkan'][$order->status] ?? $order->status }}</span></td>
                    <td class="p-4"><a class="text-indigo-700 font-semibold hover:underline" href="{{ route('admin.orders.show', $order->invoice_number) }}">Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-slate-500 p-10">Belum ada pesanan sesuai filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $orders->links() }}
</div>
@endsection

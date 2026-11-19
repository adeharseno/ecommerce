@extends('admin.layouts.app')
@section('title', 'Detail Pesanan ' . $order->invoice_number)
@section('page_title', 'Detail Pesanan')
@section('content')
@php
$labels = ['pending'=>'Menunggu bayar','paid'=>'Sudah dibayar','processing'=>'Diproses','completed'=>'Selesai','cancelled'=>'Dibatalkan'];
@endphp
<div class="max-w-5xl space-y-5">
    <a href="{{ route('admin.orders.index') }}" class="text-sm text-indigo-700 font-semibold">← Kembali ke Daftar Penjualan</a>
    <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-wrap justify-between gap-3">
        <div><p class="text-xs text-slate-500">INVOICE</p><h2 class="text-2xl font-bold">{{ $order->invoice_number }}</h2><p class="text-sm text-slate-500 mt-1">{{ $order->created_at?->format('d M Y, H:i') }}</p></div>
        <div class="text-right"><p class="text-xs text-slate-500">Status</p><p class="font-semibold">{{ $labels[$order->status] ?? $order->status }}</p><p class="font-bold text-xl mt-1">Rp {{ number_format((float) $order->total_amount, 0, ',', '.') }}</p></div>
    </div>
    <div class="grid md:grid-cols-2 gap-5">
        <section class="bg-white rounded-2xl border border-slate-200 p-6 space-y-3">
            <h3 class="font-bold">Pelanggan & Pengiriman</h3>
            <p><span class="text-slate-500 text-sm">Nama:</span> {{ $order->customer_name }}</p>
            <p><span class="text-slate-500 text-sm">Telepon:</span> {{ $order->customer_phone }}</p>
            <p class="whitespace-pre-line"><span class="text-slate-500 text-sm">Alamat:</span> {{ $order->shipping_address }}</p>
        </section>
        <section class="bg-white rounded-2xl border border-slate-200 p-6 space-y-3">
            <h3 class="font-bold">Pembayaran Transfer Bank</h3>
            @if ($proofExists)
                <a class="inline-flex text-indigo-700 underline font-semibold" href="{{ route('admin.orders.proof', $order->invoice_number) }}" target="_blank" rel="noopener">Lihat bukti transfer</a>
                <p class="text-xs text-amber-700">Bukti transfer bukan jaminan uang sudah masuk. Cocokkan dengan mutasi rekening sebelum konfirmasi.</p>
            @else
                <p class="text-sm text-slate-500">Belum ada bukti transfer yang tersedia pada kolom dan disk yang dikonfigurasi.</p>
            @endif
            @if (count($transitions))
                <form method="POST" action="{{ route('admin.orders.status', $order->invoice_number) }}" class="space-y-3 border-t pt-4">
                    @csrf @method('PATCH')
                    <label class="block text-sm font-medium" for="status">Ubah status</label>
                    <select name="status" id="status" required class="w-full rounded-xl border border-slate-300 p-3">
                        @foreach($transitions as $status)<option value="{{ $status }}">{{ $labels[$status] ?? $status }}</option>@endforeach
                    </select>
                    @if(in_array('paid', $transitions, true))
                        <label class="flex gap-2 items-start text-sm"><input type="checkbox" name="bank_verified" value="1" class="mt-1"><span>Saya sudah mencocokkan pembayaran dengan mutasi rekening bank (wajib untuk status Sudah Dibayar).</span></label>
                    @endif
                    @if($errors->any())<p class="text-red-700 text-sm">{{ $errors->first() }}</p>@endif
                    <button class="rounded-xl bg-indigo-600 hover:bg-indigo-700 px-4 py-3 text-white font-semibold" onclick="return confirm('Simpan perubahan status pesanan?')">Simpan Status</button>
                </form>
            @else
                <p class="text-sm text-slate-500">Status akhir. Tidak ada perubahan status tersedia.</p>
            @endif
        </section>
    </div>
    <section class="bg-white rounded-2xl border border-slate-200 p-6">
        <h3 class="font-bold mb-4">Item Pesanan</h3>
        <div class="divide-y divide-slate-100">
            @forelse($order->items as $item)
            <div class="flex justify-between items-center py-3 gap-3 text-sm">
                <div><p class="font-medium">{{ $item->product_name }}</p><p class="text-slate-500">{{ $item->quantity }} × Rp {{ number_format((float) $item->price, 0, ',', '.') }}</p></div>
                <span class="font-semibold whitespace-nowrap">Rp {{ number_format((float) $item->price * $item->quantity, 0, ',', '.') }}</span>
            </div>
            @empty <p class="text-slate-500 py-4">Tidak ada item pesanan.</p> @endforelse
        </div>
    </section>
</div>
@endsection

@extends('themes.briquet.layouts.app')

@section('title', 'Checkout — ' . config('app.name'))

@section('content')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- ── Page header ── --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-stone-900">Checkout</h1>
        <p class="text-sm text-stone-400 mt-1">Lengkapi data pengiriman Anda untuk melanjutkan.</p>
    </div>

    {{-- ── Progress indicator ── --}}
    <div class="flex items-center gap-2 mb-10 text-xs font-medium">
        <span class="flex items-center gap-1.5 text-stone-400">
            <span class="w-5 h-5 rounded-full bg-stone-200 text-stone-500 flex items-center justify-center text-[10px] font-bold">✓</span>
            Keranjang
        </span>
        <span class="flex-1 h-px bg-stone-200"></span>
        <span class="flex items-center gap-1.5 text-stone-800 font-semibold">
            <span class="w-5 h-5 rounded-full bg-stone-900 text-white flex items-center justify-center text-[10px] font-bold">2</span>
            Pengiriman
        </span>
        <span class="flex-1 h-px bg-stone-200"></span>
        <span class="flex items-center gap-1.5 text-stone-300">
            <span class="w-5 h-5 rounded-full bg-stone-100 text-stone-300 flex items-center justify-center text-[10px] font-bold">3</span>
            Pembayaran
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-10 items-start">

        {{-- ── Customer form ── --}}
        <div class="lg:col-span-3">
            <form method="POST" action="{{ route('checkout.store') }}" class="space-y-5">
                @csrf

                <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-6 space-y-5">
                    <h2 class="font-bold text-stone-900 text-base">Data Penerima</h2>

                    {{-- Name --}}
                    <div>
                        <label for="customer_name"
                               class="block text-sm font-medium text-stone-700 mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="customer_name"
                               name="customer_name"
                               value="{{ old('customer_name') }}"
                               placeholder="Masukkan nama lengkap"
                               autocomplete="name"
                               class="w-full px-4 py-2.5 border rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-stone-400 transition
                                      {{ $errors->has('customer_name') ? 'border-red-400 bg-red-50' : 'border-stone-300' }}">
                        @error('customer_name')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="customer_phone"
                               class="block text-sm font-medium text-stone-700 mb-1.5">
                            Nomor Telepon / WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-stone-400 font-medium select-none">
                                +62
                            </span>
                            <input type="tel"
                                   id="customer_phone"
                                   name="customer_phone"
                                   value="{{ old('customer_phone') }}"
                                   placeholder="81xxxxxxxxxx"
                                   autocomplete="tel"
                                   class="w-full pl-12 pr-4 py-2.5 border rounded-xl text-sm
                                          focus:outline-none focus:ring-2 focus:ring-stone-400 transition
                                          {{ $errors->has('customer_phone') ? 'border-red-400 bg-red-50' : 'border-stone-300' }}">
                        </div>
                        @error('customer_phone')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Address --}}
                    <div>
                        <label for="shipping_address"
                               class="block text-sm font-medium text-stone-700 mb-1.5">
                            Alamat Pengiriman Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea id="shipping_address"
                                  name="shipping_address"
                                  rows="4"
                                  placeholder="Jl. Nama Jalan No. XX, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos"
                                  autocomplete="street-address"
                                  class="w-full px-4 py-2.5 border rounded-xl text-sm resize-none
                                         focus:outline-none focus:ring-2 focus:ring-stone-400 transition
                                         {{ $errors->has('shipping_address') ? 'border-red-400 bg-red-50' : 'border-stone-300' }}">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- ── Payment info (static for now) ── --}}
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-amber-800">Metode Pembayaran: Transfer Bank</p>
                            <p class="text-xs text-amber-700 mt-1 leading-relaxed">
                                Setelah pesanan dibuat, detail rekening tujuan akan ditampilkan.
                                Selesaikan pembayaran dalam <strong>24 jam</strong> agar pesanan tidak dibatalkan.
                            </p>
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-4 bg-stone-900 text-white font-semibold rounded-full
                               hover:bg-stone-700 transition-colors text-sm tracking-wide">
                    Buat Pesanan →
                </button>
            </form>
        </div>

        {{-- ── Order summary sidebar ── --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-6 sticky top-24">
                <h2 class="font-bold text-stone-900 mb-5">Ringkasan Pesanan</h2>

                <div class="space-y-3 max-h-64 overflow-y-auto pr-1">
                    @foreach ($items as $item)
                        <div class="flex gap-3">
                            <img src="{{ $item['image'] }}"
                                 alt="{{ $item['name'] }}"
                                 class="w-12 h-12 object-cover rounded-lg bg-stone-100 shrink-0">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-stone-700 line-clamp-2">
                                    {{ $item['name'] }}
                                </p>
                                <p class="text-xs text-stone-400 mt-0.5">
                                    Rp {{ number_format($item['price'], 0, ',', '.') }} × {{ $item['quantity'] }}
                                </p>
                            </div>
                            <p class="text-xs font-bold text-stone-900 shrink-0 self-center">
                                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                            </p>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-stone-100 mt-5 pt-5 space-y-2">
                    <div class="flex justify-between text-sm text-stone-500">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-stone-500">
                        <span>Ongkos Kirim</span>
                        <span class="text-stone-400 italic">Ditentukan kemudian</span>
                    </div>
                    <div class="flex justify-between font-bold text-stone-900 text-base pt-2 border-t border-stone-100">
                        <span>Total</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

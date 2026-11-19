@extends('themes.briquet.layouts.app')

@section('title', 'Checkout — ' . 'Sumatra Briks')

@section('content')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- ── Page header ── --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-stone-900">Checkout</h1>
        <p class="text-sm text-stone-400 mt-1">Lengkapi data pengiriman Anda untuk melanjutkan pembuatan pesanan.</p>
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

    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-start gap-3 text-sm text-red-800">
            <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <p class="font-semibold">Gagal Memproses Pesanan</p>
                <p class="mt-0.5 text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-10 items-start">

        {{-- ── Customer form ── --}}
        <div class="lg:col-span-3">
            <form method="POST"
                  action="{{ route('checkout.store') }}"
                  class="space-y-5"
                  id="checkout-form"
                  onsubmit="return handleCheckoutSubmit(this);">
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
                               required
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
                                   required
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
                                  required
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

                {{-- ── Payment info ── --}}
                <div class="bg-blue-50/70 border border-blue-200 rounded-2xl p-5">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-blue-900">
                                Metode Pembayaran: Transfer Bank {{ $bank['name'] ?? 'BRI' }}
                            </p>
                            <p class="text-xs text-blue-800 mt-1 leading-relaxed">
                                Rekening resmi atas nama <strong>{{ $bank['account_holder'] ?? 'AKRATAMA SADAWIRA GRUP' }}</strong>.
                                Setelah klik "Buat Pesanan", nomor rekening dan instruksi transfer lengkap akan langsung ditampilkan pada halaman konfirmasi.
                            </p>
                        </div>
                    </div>
                </div>

                <button type="submit"
                        id="submit-order-button"
                        class="w-full py-4 bg-stone-900 text-white font-semibold rounded-full
                               hover:bg-stone-700 transition-colors text-sm tracking-wide flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed">
                    <span id="submit-text">Buat Pesanan →</span>
                    <span id="submit-loading" class="hidden items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        Memproses Pesanan...
                    </span>
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

                <div class="border-t border-stone-100 mt-5 pt-5 space-y-2.5">
                    <div class="flex justify-between text-sm text-stone-500">
                        <span>Subtotal Produk</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm text-stone-500">
                        <span>Ongkos Kirim</span>
                        <span class="text-xs text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200 font-medium">
                            Dikonfirmasi Admin
                        </span>
                    </div>
                    <div class="flex justify-between font-bold text-stone-900 text-base pt-2 border-t border-stone-100">
                        <span>Total Pembayaran</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-stone-50 rounded-xl border border-stone-100 text-xs text-stone-500 leading-relaxed">
                    <p class="flex items-start gap-1.5">
                        <svg class="w-4 h-4 text-stone-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>
                            Total di atas belum termasuk ongkos kirim. Admin toko akan menghubungi nomor WhatsApp Anda untuk konfirmasi tarif & kurir pengiriman.
                        </span>
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    let formSubmitted = false;
    function handleCheckoutSubmit(form) {
        if (formSubmitted) {
            return false;
        }
        formSubmitted = true;
        const btn = document.getElementById('submit-order-button');
        const text = document.getElementById('submit-text');
        const loading = document.getElementById('submit-loading');

        if (btn) {
            btn.disabled = true;
        }
        if (text) {
            text.classList.add('hidden');
        }
        if (loading) {
            loading.classList.remove('hidden');
            loading.classList.add('flex');
        }
        return true;
    }
</script>

@endsection

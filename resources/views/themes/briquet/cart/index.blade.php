@extends('themes.briquet.layouts.app')

@section('title', 'Keranjang Belanja — ' . 'Sumatra Briks')

@section('content')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- ── Page header ── --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-stone-900">Keranjang Belanja</h1>
        @if (! empty($items))
            <p class="text-sm text-stone-400 mt-1">
                {{ collect($items)->sum('quantity') }} item dalam keranjang
            </p>
        @endif
    </div>

    @if (empty($items))
        {{-- ── Empty state ── --}}
        <div class="text-center py-24 border border-dashed border-stone-200 rounded-2xl">
            <svg class="w-16 h-16 mx-auto text-stone-200 mb-4" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
            </svg>
            <p class="text-lg font-semibold text-stone-500">Keranjang Anda masih kosong</p>
            <p class="text-sm text-stone-400 mt-1">Tambahkan produk pilihan Anda dan mulai berbelanja.</p>
            <a href="{{ route('products.index') }}"
               class="mt-6 inline-flex items-center gap-2 px-6 py-3 bg-stone-900 text-white text-sm
                      font-semibold rounded-full hover:bg-stone-700 transition-colors">
                Mulai Belanja
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </div>

    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- ── Cart items ── --}}
            <div class="lg:col-span-2 space-y-3">
                @foreach ($items as $item)
                    <div class="flex gap-4 p-4 bg-white rounded-2xl border border-stone-100 shadow-sm">

                        {{-- Thumbnail --}}
                        <a href="{{ route('products.show', $item['slug']) }}"
                           class="shrink-0 w-24 h-24 rounded-xl overflow-hidden bg-stone-100">
                            <img src="{{ $item['image'] }}"
                                 alt="{{ $item['name'] }}"
                                 class="w-full h-full object-cover">
                        </a>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0 flex flex-col justify-between">
                            <div>
                                <a href="{{ route('products.show', $item['slug']) }}"
                                   class="font-semibold text-stone-800 hover:text-stone-600 transition-colors line-clamp-2 text-sm">
                                    {{ $item['name'] }}
                                </a>
                                <p class="text-xs text-stone-400 mt-0.5">
                                    Rp {{ number_format($item['price'], 0, ',', '.') }} / item
                                </p>
                            </div>

                            <div class="flex items-center justify-between mt-3 gap-4">
                                {{-- Quantity form --}}
                                <form method="POST"
                                      action="{{ route('cart.update', $item['id']) }}"
                                      class="flex items-center border border-stone-200 rounded-full overflow-hidden bg-white">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button"
                                            onclick="decrementQty(this)"
                                            class="w-9 h-9 flex items-center justify-center text-stone-500 hover:bg-stone-100 transition-colors text-base font-bold cursor-pointer select-none"
                                            aria-label="Kurangi jumlah">
                                        −
                                    </button>
                                    <input type="number"
                                           name="quantity"
                                           value="{{ $item['quantity'] }}"
                                           min="1"
                                           max="9999"
                                           class="w-12 sm:w-14 text-center text-sm font-semibold text-stone-800 bg-transparent py-1 focus:outline-none focus:bg-stone-50 transition-colors [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                           onchange="updateCartQty(this)"
                                           onkeydown="if(event.key==='Enter'){event.preventDefault();this.blur();}"
                                           aria-label="Jumlah produk {{ $item['name'] }}">
                                    <button type="button"
                                            onclick="incrementQty(this)"
                                            class="w-9 h-9 flex items-center justify-center text-stone-500 hover:bg-stone-100 transition-colors text-base font-bold cursor-pointer select-none"
                                            aria-label="Tambah jumlah">
                                        +
                                    </button>
                                </form>

                                <div class="flex items-center gap-4">
                                    {{-- Subtotal --}}
                                    <p class="font-bold text-stone-900 text-sm">
                                        Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                    </p>

                                    {{-- Remove --}}
                                    <form method="POST"
                                          action="{{ route('cart.remove', $item['id']) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-stone-300 hover:text-red-500 transition-colors cursor-pointer"
                                                aria-label="Hapus {{ $item['name'] }}">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="pt-2">
                    <a href="{{ route('products.index') }}"
                       class="inline-flex items-center gap-1.5 text-sm text-stone-400 hover:text-stone-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                        </svg>
                        Lanjut Belanja
                    </a>
                </div>
            </div>

            {{-- ── Order summary ── --}}
            <div class="lg:col-span-1">
                <div class="bg-stone-50 rounded-2xl border border-stone-200 p-6 sticky top-24">
                    <h2 class="font-bold text-stone-900 text-lg mb-5">Ringkasan</h2>

                    <div class="space-y-2 text-sm mb-5">
                        @foreach ($items as $item)
                            <div class="flex justify-between gap-2 text-stone-600">
                                <span class="line-clamp-1">{{ $item['name'] }} ×{{ $item['quantity'] }}</span>
                                <span class="shrink-0 font-medium">
                                    Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-stone-200 pt-4 mb-6">
                        <div class="flex justify-between font-bold text-stone-900 text-base">
                            <span>Total</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <p class="text-xs text-stone-400 mt-1">Belum termasuk ongkos kirim</p>
                    </div>

                    <a href="{{ route('checkout.index') }}"
                       class="block w-full text-center py-3.5 bg-stone-900 text-white font-semibold
                              rounded-full hover:bg-stone-700 transition-colors text-sm">
                        Lanjut ke Checkout →
                    </a>
                </div>
            </div>

        </div>
    @endif

</div>

@pushOnce('scripts')
<script>
    function decrementQty(btn) {
        const form = btn.closest('form');
        const input = form.querySelector('input[name="quantity"]');
        let currentVal = parseInt(input.value, 10) || 1;
        if (currentVal > 1) {
            input.value = currentVal - 1;
            form.submit();
        }
    }

    function incrementQty(btn) {
        const form = btn.closest('form');
        const input = form.querySelector('input[name="quantity"]');
        let currentVal = parseInt(input.value, 10) || 0;
        input.value = currentVal + 1;
        form.submit();
    }

    function updateCartQty(input) {
        let val = parseInt(input.value, 10);
        if (isNaN(val) || val < 1) {
            val = 1;
            input.value = 1;
        }
        input.closest('form').submit();
    }
</script>
@endPushOnce

@endsection

@extends('themes.briquet.layouts.app')

@section('title', config('app.name') . ' — Home')

@section('content')

    {{-- ── Hero ── --}}
    <section class="relative bg-stone-100 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
            <div class="max-w-2xl">
                <p class="text-xs font-semibold tracking-[0.25em] uppercase text-stone-500 mb-4">Koleksi Terbaru</p>
                <h1 class="text-4xl md:text-6xl font-bold leading-tight text-stone-900">
                    Temukan Produk <br>
                    <span class="text-stone-500">Pilihan Anda</span>
                </h1>
                <p class="mt-6 text-lg text-stone-600 leading-relaxed">
                    Kualitas terbaik, harga terjangkau. Belanja dengan mudah dan aman.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('products.index') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-stone-900 text-white text-sm font-semibold rounded-full hover:bg-stone-700 transition-colors">
                        Lihat Produk
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('page.show', 'about') }}"
                       class="inline-flex items-center gap-2 px-6 py-3 border border-stone-300 text-stone-700 text-sm font-semibold rounded-full hover:bg-stone-100 transition-colors">
                        Tentang Kami
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute inset-y-0 right-0 w-1/3 bg-gradient-to-l from-stone-200 to-transparent hidden lg:block"></div>
    </section>

    {{-- ── Featured Products ── --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-stone-400 mb-1">Unggulan</p>
                <h2 class="text-2xl md:text-3xl font-bold text-stone-900">Produk Terbaru</h2>
            </div>
            <a href="{{ route('products.index') }}"
               class="text-sm font-medium text-stone-600 hover:text-stone-900 transition-colors underline underline-offset-4">
                Lihat semua
            </a>
        </div>

        @if ($products->isEmpty())
            <div class="text-center py-20 text-stone-400">
                <p class="text-lg">Belum ada produk tersedia.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach ($products as $product)
                    <a href="{{ route('products.show', $product->slug) }}" class="group block">
                        <div class="aspect-square overflow-hidden rounded-xl bg-stone-100 mb-3">
                            <img src="{{ $product->getFirstImageUrl() }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="space-y-1">
                            @if ($product->category)
                                <p class="text-[11px] font-medium uppercase tracking-widest text-stone-400">
                                    {{ $product->category }}
                                </p>
                            @endif
                            <h3 class="text-sm font-semibold text-stone-800 group-hover:text-stone-600 transition-colors line-clamp-2">
                                {{ $product->name }}
                            </h3>
                            <p class="text-sm font-bold text-stone-900">
                                Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    {{-- ── Features strip ── --}}
    <section class="bg-stone-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
                <div class="flex flex-col items-center gap-2">
                    <svg class="w-8 h-8 text-stone-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                    </svg>
                    <h4 class="font-semibold">Pengiriman Cepat</h4>
                    <p class="text-stone-400 text-sm">2-5 hari kerja ke seluruh Indonesia</p>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <svg class="w-8 h-8 text-stone-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                    </svg>
                    <h4 class="font-semibold">Pembayaran Aman</h4>
                    <p class="text-stone-400 text-sm">Transaksi terjamin & terpercaya</p>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <svg class="w-8 h-8 text-stone-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                    </svg>
                    <h4 class="font-semibold">Garansi Pengembalian</h4>
                    <p class="text-stone-400 text-sm">Pengembalian mudah dalam 7 hari</p>
                </div>
            </div>
        </div>
    </section>

@endsection


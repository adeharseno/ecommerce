@extends('themes.briquet.layouts.app')

@section('title', $product->name . ' — ' . 'Sumatra Briks')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- ── Breadcrumb ── --}}
        <nav class="flex items-center gap-2 text-xs text-stone-400 mb-8">
            <a href="{{ route('home') }}" class="hover:text-stone-700">Home</a>
            <span>/</span>
            <a href="{{ route('products.index') }}" class="hover:text-stone-700">Produk</a>
            <span>/</span>
            <span class="text-stone-600 font-medium">{{ $product->name }}</span>
        </nav>

        {{-- ── Product detail ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start">

            {{-- Gallery --}}
            <div class="space-y-3">
                @php $images = $product->images ?? []; @endphp

                <div class="aspect-square overflow-hidden rounded-2xl bg-stone-100">
                    <img id="main-image"
                         src="{{ $product->getFirstImageUrl() }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover">
                </div>

                @if (count($images) > 1)
                    <div class="flex gap-2 overflow-x-auto pb-1">
                        @foreach ($images as $img)
                            @php
                                $thumbUrl = asset('storage/' . $img) . ($product->updated_at ? '?v=' . $product->updated_at->timestamp : '');
                            @endphp
                            <button type="button"
                                    onclick="document.getElementById('main-image').src='{{ $thumbUrl }}'"
                                    class="shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 border-transparent hover:border-stone-400 transition-colors focus:outline-none focus:border-stone-600">
                                <img src="{{ $thumbUrl }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Info & Add to cart --}}
            <div class="space-y-6">

                @if ($product->category)
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-stone-400">
                        {{ $product->category }}
                    </p>
                @endif

                <h1 class="text-3xl md:text-4xl font-bold text-stone-900 leading-tight">
                    {{ $product->name }}
                </h1>

                <p class="text-2xl font-bold text-stone-800">
                    Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                </p>

                {{-- Stock badge --}}
                <div class="flex items-center gap-2">
                    @if ($product->stock > 0)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full border border-green-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            Stok tersedia ({{ $product->stock }})
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-700 text-xs font-semibold rounded-full border border-red-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            Stok habis
                        </span>
                    @endif
                </div>

                @if ($product->description)
                    <div class="prose prose-sm prose-stone max-w-none text-stone-600">
                        <p>{{ $product->description }}</p>
                    </div>
                @endif

                <hr class="border-stone-200">

                {{-- Add to cart form --}}
                @if ($product->stock > 0)
                    <form method="POST" action="{{ route('cart.add', $product) }}" class="space-y-4">
                        @csrf
                        <div class="flex items-center gap-4">
                            <label for="quantity" class="text-sm font-medium text-stone-700">Jumlah:</label>
                            <div class="flex items-center border border-stone-300 rounded-full overflow-hidden">
                                <button type="button"
                                        onclick="const i=document.getElementById('quantity');if(i.value>1)i.value--"
                                        class="w-10 h-10 flex items-center justify-center text-stone-600 hover:bg-stone-100 transition-colors text-lg font-bold">
                                    −
                                </button>
                                <input type="number"
                                       id="quantity"
                                       name="quantity"
                                       value="1"
                                       min="1"
                                       max="{{ $product->stock }}"
                                       class="w-12 text-center text-sm font-semibold border-x border-stone-300 focus:outline-none h-10">
                                <button type="button"
                                        onclick="const i=document.getElementById('quantity');if(i.value<{{ $product->stock }})i.value++"
                                        class="w-10 h-10 flex items-center justify-center text-stone-600 hover:bg-stone-100 transition-colors text-lg font-bold">
                                    +
                                </button>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <button type="submit"
                                    class="flex-1 py-3 bg-stone-900 text-white text-sm font-semibold rounded-full hover:bg-stone-700 transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                                </svg>
                                Tambah ke Keranjang
                            </button>
                            <a href="{{ route('checkout.index') }}"
                               class="px-6 py-3 border border-stone-300 text-stone-700 text-sm font-semibold rounded-full hover:bg-stone-100 transition-colors">
                                Beli Sekarang
                            </a>
                        </div>
                    </form>
                @else
                    <p class="text-sm text-stone-400 italic">Produk sedang tidak tersedia.</p>
                @endif

            </div>
        </div>

        {{-- ── Related products ── --}}
        @if ($relatedProducts->isNotEmpty())
            <section class="mt-20">
                <h2 class="text-xl font-bold text-stone-900 mb-6">Produk Serupa</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-5">
                    @foreach ($relatedProducts as $related)
                        <a href="{{ route('products.show', $related->slug) }}" class="group block">
                            <div class="aspect-square overflow-hidden rounded-xl bg-stone-100 mb-3">
                                <img src="{{ $related->getFirstImageUrl() }}"
                                     alt="{{ $related->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <h3 class="text-sm font-semibold text-stone-800 group-hover:text-stone-600 transition-colors line-clamp-2">
                                {{ $related->name }}
                            </h3>
                            <p class="text-sm font-bold text-stone-900 mt-0.5">
                                Rp {{ number_format((float) $related->price, 0, ',', '.') }}
                            </p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

    </div>

@endsection


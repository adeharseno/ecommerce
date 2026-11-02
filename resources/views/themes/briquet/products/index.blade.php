@extends('themes.briquet.layouts.app')

@section('title', 'Produk — ' . config('app.name'))

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- ── Page header ── --}}
        <div class="mb-8">
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-stone-400 mb-1">Koleksi</p>
            <h1 class="text-3xl font-bold text-stone-900">Semua Produk</h1>
        </div>

        {{-- ── Filter bar ── --}}
        <form method="GET" action="{{ route('products.index') }}"
              class="flex flex-wrap gap-3 mb-10">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari produk..."
                   class="flex-1 min-w-[200px] px-4 py-2 border border-stone-300 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-stone-400">

            @if ($categories->isNotEmpty())
                <select name="category"
                        class="px-4 py-2 border border-stone-300 rounded-full text-sm bg-white focus:outline-none focus:ring-2 focus:ring-stone-400">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" @selected(request('category') === $cat)>
                            {{ ucfirst($cat) }}
                        </option>
                    @endforeach
                </select>
            @endif

            <button type="submit"
                    class="px-5 py-2 bg-stone-900 text-white text-sm font-semibold rounded-full hover:bg-stone-700 transition-colors">
                Cari
            </button>

            @if (request()->hasAny(['search', 'category']))
                <a href="{{ route('products.index') }}"
                   class="px-5 py-2 border border-stone-300 text-stone-600 text-sm font-medium rounded-full hover:bg-stone-100 transition-colors">
                    Reset
                </a>
            @endif
        </form>

        {{-- ── Product grid ── --}}
        @if ($products->isEmpty())
            <div class="text-center py-24 text-stone-400">
                <svg class="w-12 h-12 mx-auto mb-4 text-stone-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z"/>
                </svg>
                <p class="text-lg font-medium">Produk tidak ditemukan.</p>
                <p class="text-sm mt-1">Coba ubah kata kunci atau filter pencarian Anda.</p>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach ($products as $product)
                    <a href="{{ route('products.show', $product->slug) }}" class="group block">
                        <div class="aspect-square overflow-hidden rounded-xl bg-stone-100 mb-3 relative">
                            <img src="{{ $product->getFirstImageUrl() }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @if ($product->stock === 0)
                                <div class="absolute inset-0 bg-white/60 flex items-center justify-center">
                                    <span class="text-xs font-bold uppercase tracking-widest text-stone-500 bg-white px-3 py-1 rounded-full border border-stone-300">
                                        Habis
                                    </span>
                                </div>
                            @endif
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

            {{-- ── Pagination ── --}}
            @if ($products->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $products->links() }}
                </div>
            @endif
        @endif

    </div>

@endsection


<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen bg-stone-50 text-stone-800 font-sans antialiased">

    {{-- ── Navigation ── --}}
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="text-xl font-bold tracking-widest uppercase text-stone-800">
                    {{ config('app.name') }}
                </a>

                {{-- Desktop nav --}}
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-stone-600">
                    <a href="{{ route('home') }}"
                       class="hover:text-stone-900 transition-colors {{ request()->routeIs('home') ? 'text-stone-900 border-b-2 border-stone-800 pb-0.5' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('products.index') }}"
                       class="hover:text-stone-900 transition-colors {{ request()->routeIs('products.*') ? 'text-stone-900 border-b-2 border-stone-800 pb-0.5' : '' }}">
                        Produk
                    </a>
                    <a href="{{ route('page.show', 'about') }}"
                       class="hover:text-stone-900 transition-colors">
                        Tentang
                    </a>
                </nav>

                {{-- Cart icon --}}
                <a href="{{ route('cart.index') }}"
                   class="relative inline-flex items-center gap-1.5 text-stone-700 hover:text-stone-900 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                    @php $cartCount = app(\App\Services\CartService::class)->count(); @endphp
                    @if ($cartCount > 0)
                        <span class="absolute -top-2 -right-2 flex items-center justify-center w-5 h-5 bg-stone-800 text-white text-[10px] font-bold rounded-full">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

            </div>
        </div>
    </header>

    {{-- ── Flash Messages ── --}}
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- ── Main Content ── --}}
    <main>
        @yield('content')
    </main>

    {{-- ── Footer ── --}}
    <footer class="bg-stone-900 text-stone-300 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-white font-bold tracking-widest uppercase mb-3">{{ config('app.name') }}</h3>
                    <p class="text-sm leading-relaxed">Produk berkualitas dengan harga terjangkau untuk Anda.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3">Tautan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('page.show', 'about') }}" class="hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="{{ route('page.show', 'kebijakan-privasi') }}" class="hover:text-white transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="{{ route('page.show', 'syarat-ketentuan') }}" class="hover:text-white transition-colors">Syarat & Ketentuan</a></li>
                        <li><a href="{{ route('page.show', 'qna') }}" class="hover:text-white transition-colors">Q&A</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3">Belanja</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('products.index') }}" class="hover:text-white transition-colors">Semua Produk</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-white transition-colors">Keranjang</a></li>
                        <li><a href="{{ route('checkout.index') }}" class="hover:text-white transition-colors">Checkout</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-stone-700 mt-8 pt-8 text-center text-xs text-stone-500">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>


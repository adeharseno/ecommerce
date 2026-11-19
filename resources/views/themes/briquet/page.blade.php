@extends('themes.briquet.layouts.app')

@section('title', $page->title . ' — ' . 'Sumatra Briks')

@section('content')

    <div class="site-container py-12 md:py-16">

        <div class="max-w-4xl mx-auto">
            {{-- ── Breadcrumb ── --}}
            <nav class="flex items-center gap-2 text-xs text-stone-400 mb-8" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-stone-800 transition-colors">Beranda</a>
                <span>/</span>
                <span class="text-stone-700 font-semibold">{{ $page->title }}</span>
            </nav>

            {{-- ── Page header ── --}}
            <header class="mb-10 pb-8 border-b border-stone-200">
                <div class="eyebrow">SUMATRA BRIKS · OFFICIAL INFORMATION</div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-stone-900 tracking-tight mt-2 font-['Manrope']">
                    {{ $page->title }}
                </h1>
                <p class="text-xs text-stone-400 mt-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Terakhir diperbarui: {{ $page->updated_at?->translatedFormat('d F Y') ?? now()->translatedFormat('d F Y') }}</span>
                </p>
            </header>

            {{-- ── Page content ── --}}
            <article class="page-content bg-white p-6 sm:p-10 md:p-12 rounded-2xl border border-stone-200/80 shadow-sm text-stone-700 leading-relaxed">
                {!! $page->content !!}
            </article>

            {{-- ── Quick Navigation to other pages ── --}}
            <div class="mt-12 p-6 bg-stone-100/60 rounded-2xl border border-stone-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-stone-500">Halaman Terkait</p>
                    <div class="flex flex-wrap gap-x-4 gap-y-2 mt-2 text-xs font-semibold">
                        <a href="{{ route('page.show', 'about') }}" class="text-stone-700 hover:text-amber-800 transition-colors underline underline-offset-4">Tentang Kami</a>
                        <a href="{{ route('page.show', 'qna') }}" class="text-stone-700 hover:text-amber-800 transition-colors underline underline-offset-4">FAQ / Pertanyaan Umum</a>
                        <a href="{{ route('page.show', 'kebijakan-privasi') }}" class="text-stone-700 hover:text-amber-800 transition-colors underline underline-offset-4">Kebijakan Privasi</a>
                        <a href="{{ route('page.show', 'term-and-condition') }}" class="text-stone-700 hover:text-amber-800 transition-colors underline underline-offset-4">Syarat &amp; Ketentuan</a>
                        <a href="{{ route('page.show', 'return-policy') }}" class="text-stone-700 hover:text-amber-800 transition-colors underline underline-offset-4">Kebijakan Pengembalian</a>
                    </div>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('home') }}"
                       class="px-4 py-2 bg-white text-stone-700 border border-stone-200 hover:bg-stone-50 rounded-xl text-xs font-semibold transition">
                        ← Beranda
                    </a>
                    <a href="{{ route('products.index') }}"
                       class="px-4 py-2 bg-stone-900 text-white hover:bg-stone-800 rounded-xl text-xs font-semibold transition">
                        Lihat Produk ↗
                    </a>
                </div>
            </div>

        </div>

    </div>

@endsection

@pushOnce('styles')
<style>
    .page-content h2 {
        font-family: 'Manrope', sans-serif;
        font-size: 1.5rem;
        font-weight: 800;
        color: #191b19;
        margin-top: 2rem;
        margin-bottom: 0.85rem;
        letter-spacing: -0.02em;
        line-height: 1.25;
        border-bottom: 1px solid #f0ede6;
        padding-bottom: 0.5rem;
    }
    .page-content h2:first-child {
        margin-top: 0;
    }
    .page-content h3 {
        font-family: 'Manrope', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #272b26;
        margin-top: 1.6rem;
        margin-bottom: 0.5rem;
        line-height: 1.35;
    }
    .page-content p {
        font-size: 0.95rem;
        line-height: 1.85;
        color: #555750;
        margin-bottom: 1.1rem;
    }
    .page-content ul, .page-content ol {
        margin-top: 0.5rem;
        margin-bottom: 1.35rem;
        padding-left: 1.5rem;
        color: #555750;
        font-size: 0.95rem;
        line-height: 1.85;
    }
    .page-content ul {
        list-style-type: disc;
    }
    .page-content ol {
        list-style-type: decimal;
    }
    .page-content li {
        margin-bottom: 0.45rem;
    }
    .page-content strong {
        color: #191b19;
        font-weight: 700;
    }
    .page-content a {
        color: #b68157;
        text-decoration: underline;
        text-underline-offset: 4px;
        font-weight: 600;
    }
    .page-content a:hover {
        color: #8c5d37;
    }
    .page-content .highlight-box {
        background-color: #fbf9f5;
        border-left: 4px solid #b68157;
        padding: 1.15rem 1.35rem;
        border-radius: 0.75rem;
        margin: 1.5rem 0;
        color: #4a4d46;
        font-size: 0.92rem;
    }
    .page-content .faq-item {
        background: #fbfbf9;
        border: 1px solid #eeebe3;
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1rem;
    }
    .page-content .faq-item h3 {
        margin-top: 0;
        color: #191b19;
        font-size: 1.05rem;
    }
    .page-content .faq-item p:last-child {
        margin-bottom: 0;
    }
</style>
@endPushOnce

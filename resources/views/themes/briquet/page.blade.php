@extends('themes.briquet.layouts.app')

@section('title', $page->title . ' — ' . 'Sumatra Briks')

@section('content')

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- ── Breadcrumb ── --}}
        <nav class="flex items-center gap-2 text-xs text-stone-400 mb-8">
            <a href="{{ route('home') }}" class="hover:text-stone-700">Home</a>
            <span>/</span>
            <span class="text-stone-600 font-medium">{{ $page->title }}</span>
        </nav>

        {{-- ── Page header ── --}}
        <header class="mb-10">
            <h1 class="text-4xl font-bold text-stone-900">{{ $page->title }}</h1>
            <p class="text-xs text-stone-400 mt-2">
                Terakhir diperbarui: {{ $page->updated_at->translatedFormat('d F Y') }}
            </p>
        </header>

        <hr class="border-stone-200 mb-10">

        {{-- ── Page content ── --}}
        <article class="prose prose-stone max-w-none
            prose-headings:font-bold prose-headings:text-stone-900
            prose-h2:text-2xl prose-h3:text-xl
            prose-p:text-stone-600 prose-p:leading-relaxed
            prose-li:text-stone-600
            prose-a:text-stone-800 prose-a:underline prose-a:underline-offset-4 hover:prose-a:text-stone-500">
            {!! $page->content !!}
        </article>

        {{-- ── Back link ── --}}
        <div class="mt-12 pt-8 border-t border-stone-200">
            <a href="{{ url()->previous(route('home')) }}"
               class="inline-flex items-center gap-2 text-sm font-medium text-stone-600 hover:text-stone-900 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Kembali
            </a>
        </div>

    </div>

@endsection


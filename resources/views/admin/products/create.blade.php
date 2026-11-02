@extends('admin.layouts.app')

@section('title', 'Tambah Produk Baru')
@section('page_title', 'Tambah Produk Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Breadcrumb & Back --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.products.index') }}"
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Daftar Produk
        </a>
    </div>

    {{-- Form Card --}}
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Basic Information --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-5">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Informasi Utama Produk</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Name --}}
                <div class="md:col-span-2">
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Nama Produk <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="Contoh: Kopi Arabika Gayo 250gr"
                           required
                           class="w-full px-3.5 py-2.5 rounded-xl border text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition {{ $errors->has('name') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }}">
                    @error('name')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label for="category" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Kategori Produk
                    </label>
                    <input type="text"
                           id="category"
                           name="category"
                           value="{{ old('category') }}"
                           placeholder="Contoh: Minuman, Makanan, Aksesoris"
                           class="w-full px-3.5 py-2.5 rounded-xl border text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition {{ $errors->has('category') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }}">
                    @error('category')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Active Status Toggle --}}
                <div class="flex items-center pt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               class="sr-only peer"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        <span class="ml-3 text-xs font-semibold text-slate-700">Tampilkan / Aktifkan di Toko</span>
                    </label>
                </div>

                {{-- Price --}}
                <div>
                    <label for="price" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Harga (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 select-none">Rp</span>
                        <input type="number"
                               id="price"
                               name="price"
                               value="{{ old('price') }}"
                               placeholder="150000"
                               min="0"
                               step="0.01"
                               required
                               class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition {{ $errors->has('price') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }}">
                    </div>
                    @error('price')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Stock --}}
                <div>
                    <label for="stock" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Jumlah Stok <span class="text-rose-500">*</span>
                    </label>
                    <input type="number"
                           id="stock"
                           name="stock"
                           value="{{ old('stock', 0) }}"
                           placeholder="100"
                           min="0"
                           required
                           class="w-full px-3.5 py-2.5 rounded-xl border text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition {{ $errors->has('stock') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }}">
                    @error('stock')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label for="description" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Deskripsi Lengkap Produk
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="4"
                              placeholder="Tuliskan spesifikasi, keunggulan, atau petunjuk produk..."
                              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition {{ $errors->has('description') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-300' }}">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Product Images Upload --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900">Foto / Gambar Produk</h3>
                <span class="text-xs text-slate-400">Bisa upload hingga 5 gambar (Maks. 2MB/gambar)</span>
            </div>

            <div>
                <label for="images"
                       class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/60 hover:bg-slate-50 hover:border-indigo-400 transition cursor-pointer">
                    <svg class="w-10 h-10 text-slate-400 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    <p class="text-xs font-semibold text-slate-700">Pilih gambar atau seret file ke sini</p>
                    <p class="text-[11px] text-slate-400 mt-1">Format JPG, PNG, WEBP</p>
                    <input type="file"
                           id="images"
                           name="images[]"
                           multiple
                           accept="image/*"
                           class="sr-only"
                           onchange="previewMultipleImages(this)">
                </label>

                @error('images')
                    <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="mt-2 text-xs text-rose-500">{{ $message }}</p>
                @enderror

                {{-- Live Image Preview Grid --}}
                <div id="preview-container" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-3 mt-4 hidden"></div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.products.index') }}"
               class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition">
                Batal
            </a>
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-xs transition-colors">
                Simpan Produk
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewMultipleImages(input) {
        const container = document.getElementById('preview-container');
        container.innerHTML = '';

        if (input.files && input.files.length > 0) {
            container.classList.remove('hidden');

            Array.from(input.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const card = document.createElement('div');
                    card.className = 'relative aspect-square rounded-xl overflow-hidden border border-slate-200 bg-slate-100 group';
                    card.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded text-[10px] bg-slate-900/70 text-white font-mono">#${index + 1}</span>
                    `;
                    container.appendChild(card);
                };
                reader.readAsDataURL(file);
            });
        } else {
            container.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection


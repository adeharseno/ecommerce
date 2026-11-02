@extends('themes.briquet.layouts.app')

@section('title', 'Pesanan Berhasil — ' . config('app.name'))

@section('content')

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- ── Success header ── --}}
    <div class="text-center mb-10">
        @if ($transaction->status === 'paid')
            <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-5">
                <svg class="w-10 h-10 text-blue-500" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-stone-900">Pembayaran Diterima!</h1>
            <p class="text-stone-500 mt-2">Pesanan Anda sedang kami proses. Terima kasih!</p>
        @else
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-5">
                <svg class="w-10 h-10 text-green-500" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-stone-900">Pesanan Berhasil Dibuat!</h1>
            <p class="text-stone-500 mt-2">
                Selesaikan pembayaran dalam <strong class="text-stone-800">24 jam</strong>
                agar pesanan diproses.
            </p>
        @endif
    </div>

    {{-- ── Invoice card ── --}}
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden mb-6">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 bg-stone-50 border-b border-stone-100">
            <div>
                <p class="text-[11px] uppercase tracking-widest text-stone-400 font-medium">Nomor Invoice</p>
                <p class="text-lg font-bold text-stone-900 font-mono mt-0.5">
                    {{ $transaction->invoice_number }}
                </p>
            </div>
            @php
                $statusConfig = [
                    'pending'    => ['bg-amber-50 text-amber-700 border-amber-200', 'Menunggu Bayar'],
                    'paid'       => ['bg-blue-50 text-blue-700 border-blue-200', 'Sudah Dibayar'],
                    'processing' => ['bg-indigo-50 text-indigo-700 border-indigo-200', 'Diproses'],
                    'completed'  => ['bg-green-50 text-green-700 border-green-200', 'Selesai'],
                    'cancelled'  => ['bg-red-50 text-red-700 border-red-200', 'Dibatalkan'],
                ];
                [$statusClass, $statusLabel] = $statusConfig[$transaction->status] ?? ['bg-stone-50 text-stone-700 border-stone-200', ucfirst($transaction->status)];
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                {{ $statusLabel }}
            </span>
        </div>

        {{-- Customer info --}}
        <div class="px-6 py-4 space-y-2.5 border-b border-stone-100">
            <div class="flex justify-between gap-4 text-sm">
                <span class="text-stone-400 shrink-0">Nama</span>
                <span class="font-medium text-stone-800 text-right">{{ $transaction->customer_name }}</span>
            </div>
            <div class="flex justify-between gap-4 text-sm">
                <span class="text-stone-400 shrink-0">Telepon</span>
                <span class="font-medium text-stone-800">{{ $transaction->customer_phone }}</span>
            </div>
            <div class="flex justify-between gap-4 text-sm">
                <span class="text-stone-400 shrink-0">Alamat</span>
                <span class="font-medium text-stone-800 text-right">{{ $transaction->shipping_address }}</span>
            </div>
            <div class="flex justify-between gap-4 text-sm">
                <span class="text-stone-400 shrink-0">Tanggal</span>
                <span class="font-medium text-stone-800">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
            </div>
        </div>

        {{-- Order items --}}
        <div class="px-6 py-4 space-y-3 border-b border-stone-100">
            @foreach ($transaction->items as $item)
                <div class="flex justify-between gap-4 text-sm">
                    <span class="text-stone-600">
                        {{ $item->product_name }}
                        <span class="text-stone-400">×{{ $item->quantity }}</span>
                    </span>
                    <span class="font-semibold text-stone-800 shrink-0">
                        Rp {{ number_format((float) $item->price * $item->quantity, 0, ',', '.') }}
                    </span>
                </div>
            @endforeach
        </div>

        {{-- Total --}}
        <div class="px-6 py-4 flex justify-between items-center font-bold text-stone-900">
            <span>Total Pembayaran</span>
            <span class="text-xl">Rp {{ number_format((float) $transaction->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- ── Payment section ── --}}
    @if ($transaction->status === 'pending')

        {{-- Bank transfer info --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6 mb-6">
            <h2 class="font-bold text-stone-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-stone-500" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z"/>
                </svg>
                Instruksi Transfer Bank
            </h2>

            <div class="space-y-3">
                {{-- BCA --}}
                <div class="flex items-center justify-between p-4 rounded-xl bg-blue-50 border border-blue-100">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-blue-500 mb-1">BCA</p>
                        <p class="text-lg font-bold font-mono text-stone-900">1234 5678 90</p>
                        <p class="text-sm text-stone-600">a.n. Toko Online</p>
                    </div>
                    <button type="button"
                            onclick="navigator.clipboard.writeText('1234567890').then(()=>this.textContent='Tersalin!')"
                            class="text-xs text-blue-600 font-semibold hover:text-blue-800 transition-colors border border-blue-200 px-3 py-1.5 rounded-full">
                        Salin
                    </button>
                </div>

                {{-- BRI --}}
                <div class="flex items-center justify-between p-4 rounded-xl bg-sky-50 border border-sky-100">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-sky-500 mb-1">BRI</p>
                        <p class="text-lg font-bold font-mono text-stone-900">0987 6543 21</p>
                        <p class="text-sm text-stone-600">a.n. Toko Online</p>
                    </div>
                    <button type="button"
                            onclick="navigator.clipboard.writeText('0987654321').then(()=>this.textContent='Tersalin!')"
                            class="text-xs text-sky-600 font-semibold hover:text-sky-800 transition-colors border border-sky-200 px-3 py-1.5 rounded-full">
                        Salin
                    </button>
                </div>
            </div>

            <p class="text-xs text-stone-400 mt-4 leading-relaxed">
                Transfer tepat sesuai jumlah tagihan:
                <strong class="text-stone-700">Rp {{ number_format((float) $transaction->total_amount, 0, ',', '.') }}</strong>.
                Lalu upload bukti transfer di bawah ini.
            </p>
        </div>

        {{-- Upload proof form --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6">
            <h2 class="font-bold text-stone-900 mb-1">Upload Bukti Transfer</h2>
            <p class="text-sm text-stone-400 mb-5">
                Format: JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
            </p>

            <form method="POST"
                  action="{{ route('checkout.proof', $transaction->invoice_number) }}"
                  enctype="multipart/form-data"
                  class="space-y-4"
                  id="upload-proof-form">
                @csrf

                {{-- Dropzone area --}}
                <label for="payment_proof"
                       class="flex flex-col items-center justify-center gap-3 w-full h-40
                              border-2 border-dashed border-stone-200 rounded-xl
                              hover:border-stone-400 cursor-pointer transition-colors
                              bg-stone-50 hover:bg-stone-100"
                       id="drop-label">
                    <svg class="w-10 h-10 text-stone-300" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                    </svg>
                    <span class="text-sm text-stone-500" id="drop-text">
                        Klik atau seret foto bukti transfer ke sini
                    </span>
                    <input type="file"
                           id="payment_proof"
                           name="payment_proof"
                           accept="image/*"
                           class="sr-only"
                           onchange="previewProof(this)">
                </label>

                {{-- Image preview --}}
                <div id="image-preview" class="hidden">
                    <p class="text-xs text-stone-400 mb-2">Pratinjau:</p>
                    <img id="preview-img" src="" alt="Preview"
                         class="max-h-48 rounded-xl border border-stone-200 object-cover">
                </div>

                @error('payment_proof')
                    <p class="text-xs text-red-500 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror

                <button type="submit"
                        class="w-full py-3.5 bg-stone-900 text-white font-semibold rounded-full
                               hover:bg-stone-700 transition-colors text-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                    </svg>
                    Kirim Bukti Pembayaran
                </button>
            </form>
        </div>

    @elseif ($transaction->status === 'paid')
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-blue-800">Bukti pembayaran diterima</p>
                <p class="text-xs text-blue-600 mt-0.5">
                    Kami akan memverifikasi pembayaran Anda dan segera memproses pengiriman.
                </p>
            </div>
        </div>

    @elseif ($transaction->status === 'cancelled')
        <div class="bg-red-50 border border-red-200 rounded-2xl p-5 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-red-800">Pesanan Dibatalkan</p>
                <p class="text-xs text-red-600 mt-0.5">Pesanan ini telah dibatalkan. Buat pesanan baru untuk melanjutkan.</p>
            </div>
        </div>
    @endif

    {{-- ── Footer actions ── --}}
    <div class="flex flex-wrap items-center justify-center gap-4 mt-8 text-sm">
        <a href="{{ route('products.index') }}"
           class="text-stone-500 hover:text-stone-900 transition-colors underline underline-offset-4">
            Lanjut Belanja
        </a>
        <span class="text-stone-200">|</span>
        <a href="{{ route('page.show', 'qna') }}"
           class="text-stone-500 hover:text-stone-900 transition-colors underline underline-offset-4">
            Butuh Bantuan?
        </a>
    </div>

</div>

@pushOnce('scripts')
<script>
    function previewProof(input) {
        const preview  = document.getElementById('image-preview');
        const img      = document.getElementById('preview-img');
        const dropText = document.getElementById('drop-text');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                img.src = e.target.result;
                preview.classList.remove('hidden');
                dropText.textContent = input.files[0].name;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endPushOnce

@endsection

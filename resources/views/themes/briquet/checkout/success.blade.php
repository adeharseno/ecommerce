@extends('themes.briquet.layouts.app')

@section('title', 'Pesanan Berhasil — ' . 'Sumatra Briks')

@section('content')

<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- ── Flash messages ── --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-start gap-3 text-sm text-emerald-800">
            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-semibold">Berhasil</p>
                <p class="mt-0.5 text-emerald-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-start gap-3 text-sm text-red-800">
            <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <p class="font-semibold">Perhatian</p>
                <p class="mt-0.5 text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="text-center mb-10">
        @if ($transaction->status === 'paid')
            <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-5">
                <svg class="w-10 h-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-stone-900">Pembayaran Terverifikasi!</h1>
            <p class="text-stone-500 mt-2">Pesanan Anda telah kami konfirmasi dan sedang disiapkan untuk pengiriman.</p>
        @else
            <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-100 rounded-full mb-5">
                <svg class="w-10 h-10 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-stone-900">Pesanan Berhasil Dibuat!</h1>
            <p class="text-stone-500 mt-2">
                Silakan lakukan transfer ke rekening resmi BRI di bawah ini dalam <strong class="text-stone-800">24 jam</strong>.
            </p>
        @endif
    </div>

    {{-- ── Invoice Summary Card ── --}}
    <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden mb-6">

        {{-- Invoice Header --}}
        <div class="flex items-center justify-between px-6 py-4 bg-stone-50 border-b border-stone-100">
            <div>
                <p class="text-[11px] uppercase tracking-widest text-stone-400 font-medium">Nomor Pesanan / Invoice</p>
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

        {{-- Customer Info --}}
        <div class="px-6 py-4 space-y-2.5 border-b border-stone-100">
            <div class="flex justify-between gap-4 text-sm">
                <span class="text-stone-400 shrink-0">Nama Penerima</span>
                <span class="font-medium text-stone-800 text-right">{{ $transaction->customer_name }}</span>
            </div>
            <div class="flex justify-between gap-4 text-sm">
                <span class="text-stone-400 shrink-0">Nomor Telepon</span>
                <span class="font-medium text-stone-800">{{ $transaction->customer_phone }}</span>
            </div>
            <div class="flex justify-between gap-4 text-sm">
                <span class="text-stone-400 shrink-0">Alamat Pengiriman</span>
                <span class="font-medium text-stone-800 text-right">{{ $transaction->shipping_address }}</span>
            </div>
            <div class="flex justify-between gap-4 text-sm">
                <span class="text-stone-400 shrink-0">Waktu Pemesanan</span>
                <span class="font-medium text-stone-800">{{ $transaction->created_at->format('d M Y, H:i') }} WIB</span>
            </div>

            @if (! $isAuthorized)
                <div class="mt-3 p-3 bg-stone-50 rounded-xl border border-stone-200 text-xs text-stone-600">
                    <p class="font-semibold text-stone-700 mb-1">Privasi Pelanggan Terlindungi</p>
                    <p class="mb-2">Informasi kontak disamarkan untuk pengunjung umum. Masukkan nomor telepon yang digunakan saat checkout untuk membuka akses penuh:</p>
                    <form method="GET" action="{{ route('checkout.success', $transaction->invoice_number) }}" class="flex gap-2">
                        <input type="tel"
                               name="verify_phone"
                               placeholder="Contoh: 0812xxxxxxxx"
                               required
                               class="flex-1 px-3 py-1.5 text-xs border border-stone-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-stone-400">
                        <button type="submit"
                                class="px-3 py-1.5 bg-stone-900 text-white rounded-lg text-xs font-semibold hover:bg-stone-700 transition">
                            Verifikasi
                        </button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Order Items --}}
        <div class="px-6 py-4 space-y-3 border-b border-stone-100">
            <p class="text-xs uppercase tracking-wider text-stone-400 font-semibold mb-2">Rincian Produk</p>
            @foreach ($transaction->items as $item)
                <div class="flex justify-between gap-4 text-sm">
                    <span class="text-stone-700">
                        {{ $item->product_name }}
                        <span class="text-stone-400 text-xs ml-1">({{ $item->quantity }} pcs)</span>
                    </span>
                    <span class="font-semibold text-stone-800 shrink-0">
                        Rp {{ number_format((float) $item->price * $item->quantity, 0, ',', '.') }}
                    </span>
                </div>
            @endforeach
        </div>

        {{-- Totals & Shipping Note --}}
        <div class="px-6 py-4 bg-stone-50/50 space-y-2">
            <div class="flex justify-between text-sm text-stone-600">
                <span>Subtotal Produk</span>
                <span>Rp {{ number_format((float) $transaction->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center text-sm text-stone-600">
                <span>Biaya Pengiriman</span>
                <span class="text-xs text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200 font-medium">
                    Akan dikonfirmasi admin
                </span>
            </div>
            <div class="border-t border-stone-200 pt-2 flex justify-between items-center font-bold text-stone-900">
                <span>Total Transfer (Produk)</span>
                <span class="text-xl text-stone-900 font-mono">
                    Rp {{ number_format((float) $transaction->total_amount, 0, ',', '.') }}
                </span>
            </div>
            <p class="text-[11px] text-stone-400 leading-normal pt-1">
                * Ongkos kirim belum termasuk pada total di atas dan akan diinformasikan oleh admin via WhatsApp setelah rute dan kurir pengiriman disepakati.
            </p>
        </div>
    </div>

    {{-- ── Official Bank Transfer Instructions ── --}}
    @if ($transaction->status === 'pending')
        @php
            $bankName = $bank['name'] ?? 'BRI';
            $bankAccount = $bank['account_number'] ?? '20601010804563';
            $bankHolder = $bank['account_holder'] ?? 'AKRATAMA SADAWIRA GRUP';
            $transferAmount = (int) $transaction->total_amount;
        @endphp

        <div class="bg-white rounded-2xl border border-blue-200 shadow-sm p-6 mb-6">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-stone-900 text-base">Instruksi Pembayaran Transfer Bank</h2>
                    <p class="text-xs text-stone-500">Transfer tepat sesuai nominal ke rekening resmi berikut:</p>
                </div>
            </div>

            {{-- Bank Card --}}
            <div class="p-5 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50/30 border border-blue-200/80 space-y-4">
                {{-- Bank Name & Badge --}}
                <div class="flex justify-between items-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold tracking-wider uppercase bg-blue-600 text-white">
                        Bank {{ $bankName }}
                    </span>
                    <span class="text-xs text-blue-700 font-medium">Rekening Resmi Toko</span>
                </div>

                {{-- Account Number with Copy --}}
                <div class="bg-white p-3.5 rounded-xl border border-blue-100 flex items-center justify-between gap-3">
                    <div>
                        <p class="text-[11px] text-stone-400 uppercase tracking-wider font-semibold">Nomor Rekening</p>
                        <p class="text-xl font-bold font-mono text-stone-900 tracking-wider mt-0.5" id="bank-acc-text">{{ $bankAccount }}</p>
                        <p class="text-xs text-stone-600 font-medium mt-0.5">a.n. {{ $bankHolder }}</p>
                    </div>
                    <button type="button"
                            onclick="copyToClipboard('{{ $bankAccount }}', this, 'Salin Nomor Rekening')"
                            class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shrink-0 flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <span>Salin Nomor Rekening</span>
                    </button>
                </div>

                {{-- Transfer Amount with Copy --}}
                <div class="bg-white p-3.5 rounded-xl border border-blue-100 flex items-center justify-between gap-3">
                    <div>
                        <p class="text-[11px] text-stone-400 uppercase tracking-wider font-semibold">Nominal Transfer</p>
                        <p class="text-xl font-bold font-mono text-stone-900 mt-0.5">
                            Rp {{ number_format((float) $transaction->total_amount, 0, ',', '.') }}
                        </p>
                        <p class="text-[11px] text-stone-500 mt-0.5">Transfer persis hingga digit terakhir</p>
                    </div>
                    <button type="button"
                            onclick="copyToClipboard('{{ $transferAmount }}', this, 'Salin Nominal Transfer')"
                            class="px-3.5 py-2 bg-stone-800 hover:bg-stone-900 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm shrink-0 flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                        <span>Salin Nominal Transfer</span>
                    </button>
                </div>
            </div>

            <div class="mt-4 p-3 bg-amber-50 rounded-xl border border-amber-200 text-xs text-amber-800 flex items-start gap-2">
                <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p>
                    Jangan melakukan transfer ke nomor rekening lain selain rekening resmi BRI atas nama <strong>{{ $bankHolder }}</strong> di atas. Setelah transfer, segera kirim bukti transfer melalui formulir di bawah.
                </p>
            </div>
        </div>

        {{-- Upload Proof Form --}}
        <div class="bg-white rounded-2xl border border-stone-200 shadow-sm p-6">
            <h2 class="font-bold text-stone-900 mb-1">Kirim Bukti Transfer</h2>
            <p class="text-sm text-stone-400 mb-5">
                Format gambar: JPG, JPEG, PNG, atau WEBP. Ukuran maksimal 2 MB.
            </p>

            @if ($transaction->payment_proof)
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl mb-4 text-xs text-emerald-800 flex items-start gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <div>
                        <p class="font-semibold">Bukti Transfer Telah Diunggah</p>
                        <p class="mt-0.5">Bukti pembayaran Anda sudah kami terima dan sedang diverifikasi oleh admin toko. Anda dapat mengunggah ulang jika ingin memperbarui foto bukti transfer.</p>
                    </div>
                </div>
            @endif

            @if ($isAuthorized)
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
                               required
                               class="sr-only"
                               onchange="previewProof(this)">
                    </label>

                    {{-- Image preview --}}
                    <div id="image-preview" class="hidden">
                        <p class="text-xs text-stone-400 mb-2">Pratinjau Bukti Transfer:</p>
                        <img id="preview-img" src="" alt="Preview Bukti Transfer"
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
                                   hover:bg-stone-700 transition-colors text-sm flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                        </svg>
                        Kirim Bukti Pembayaran
                    </button>
                </form>
            @else
                <div class="p-4 bg-stone-50 rounded-xl border border-stone-200 text-xs text-stone-600">
                    Silakan lakukan verifikasi nomor telepon di atas terlebih dahulu untuk dapat mengunggah bukti pembayaran.
                </div>
            @endif
        </div>

    @elseif ($transaction->status === 'paid')
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-blue-800">Pembayaran Telah Diverifikasi</p>
                <p class="text-xs text-blue-600 mt-0.5">
                    Pesanan Anda sudah diverifikasi oleh admin. Kami sedang menyiapkan produk untuk segera dikirim ke alamat Anda.
                </p>
            </div>
        </div>

    @elseif ($transaction->status === 'cancelled')
        <div class="bg-red-50 border border-red-200 rounded-2xl p-5 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12V6.75Z"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-red-800">Pesanan Dibatalkan</p>
                <p class="text-xs text-red-600 mt-0.5">Pesanan ini telah dibatalkan. Silakan lakukan pemesanan baru jika ingin melanjutkan belanja.</p>
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
    function copyToClipboard(text, button, originalLabel) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(() => {
                showCopiedFeedback(button, originalLabel);
            }).catch(() => {
                fallbackCopy(text, button, originalLabel);
            });
        } else {
            fallbackCopy(text, button, originalLabel);
        }
    }

    function fallbackCopy(text, button, originalLabel) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.focus();
        textarea.select();
        try {
            document.execCommand('copy');
            showCopiedFeedback(button, originalLabel);
        } catch (err) {
            console.error('Copy failed', err);
        }
        document.body.removeChild(textarea);
    }

    function showCopiedFeedback(button, originalLabel) {
        const span = button.querySelector('span');
        if (!span) return;
        const originalText = span.textContent;
        span.textContent = '✓ Tersalin!';
        button.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
        setTimeout(() => {
            span.textContent = originalLabel || originalText;
            button.classList.remove('bg-emerald-600', 'hover:bg-emerald-700');
        }, 2000);
    }

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

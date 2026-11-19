<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $pages = [
            [
                'title' => 'Kebijakan Privasi',
                'slug' => 'kebijakan-privasi',
                'content' => '<h2>Kebijakan Privasi</h2><p>Kami berkomitmen untuk melindungi privasi dan keamanan data pribadi Anda. Halaman ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi Anda.</p><h3>Informasi yang Kami Kumpulkan</h3><p>Kami mengumpulkan informasi yang Anda berikan secara langsung, seperti nama, alamat email, nomor telepon, dan alamat pengiriman saat Anda melakukan pembelian.</p><h3>Penggunaan Informasi</h3><p>Informasi Anda digunakan untuk memproses pesanan, mengirimkan konfirmasi pembelian, dan meningkatkan layanan kami.</p><h3>Keamanan Data</h3><p>Kami menggunakan langkah-langkah keamanan yang sesuai untuk melindungi informasi Anda dari akses yang tidak sah.</p>',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Syarat & Ketentuan',
                'slug' => 'syarat-ketentuan',
                'content' => '<h2>Syarat & Ketentuan</h2><p>Dengan menggunakan layanan kami, Anda setuju untuk mematuhi syarat dan ketentuan yang berlaku berikut ini.</p><h3>Pemesanan</h3><p>Semua pesanan yang telah dikonfirmasi dan dibayar akan diproses sesuai dengan ketersediaan stok. Kami berhak membatalkan pesanan jika terjadi kesalahan harga atau stok habis.</p><h3>Pembayaran</h3><p>Pembayaran harus dilakukan dalam waktu 24 jam setelah pesanan dibuat. Pesanan yang belum dibayar akan otomatis dibatalkan.</p><h3>Pengembalian Barang</h3><p>Pengembalian barang dapat dilakukan dalam 7 hari setelah barang diterima dengan syarat barang dalam kondisi baik dan belum digunakan.</p>',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Tentang Kami',
                'slug' => 'about',
                'content' => '<h2>Tentang Kami</h2><p>Kami adalah platform e-commerce terpercaya yang menyediakan berbagai produk berkualitas dengan harga terjangkau.</p><h3>Visi Kami</h3><p>Menjadi platform belanja online terdepan yang memberikan pengalaman berbelanja yang mudah, aman, dan menyenangkan bagi seluruh pelanggan.</p><h3>Misi Kami</h3><ul><li>Menyediakan produk berkualitas tinggi dengan harga kompetitif.</li><li>Memberikan pelayanan pelanggan yang responsif dan ramah.</li><li>Memastikan keamanan transaksi dan privasi data pelanggan.</li></ul><h3>Mengapa Memilih Kami?</h3><p>Kami memiliki tim yang berdedikasi untuk memastikan setiap pelanggan mendapatkan pengalaman berbelanja terbaik.</p>',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Q&A / Pertanyaan Umum',
                'slug' => 'qna',
                'content' => '<h2>Pertanyaan yang Sering Diajukan (Q&A)</h2><h3>Bagaimana cara memesan produk?</h3><p>Pilih produk yang Anda inginkan, tambahkan ke keranjang, lalu ikuti proses checkout. Isi data pengiriman dan lakukan pembayaran sesuai instruksi.</p><h3>Metode pembayaran apa yang tersedia?</h3><p>Kami menerima pembayaran melalui transfer bank. Detail rekening akan diberikan setelah pesanan dikonfirmasi.</p><h3>Berapa lama waktu pengiriman?</h3><p>Estimasi waktu pengiriman adalah 2-5 hari kerja tergantung lokasi tujuan.</p><h3>Bagaimana cara mengecek status pesanan?</h3><p>Anda dapat mengecek status pesanan melalui halaman "Cek Pesanan" dengan memasukkan nomor invoice yang dikirimkan ke email Anda.</p><h3>Apa yang harus dilakukan jika barang rusak saat diterima?</h3><p>Segera hubungi kami dalam 1x24 jam dengan menyertakan foto produk yang rusak. Kami akan memproses penggantian secepatnya.</p>',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Gunakan upsert agar aman jika seeder dijalankan ulang (tidak duplikat)
        DB::table('pages')->upsert($pages, ['slug'], ['title', 'content', 'updated_at']);
    }
}

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

        $aboutContent = <<<'HTML'
<div class="highlight-box">
    <strong>Sumatra Briks</strong> (CV/PT Akratama Sadawira Grup) adalah produsen dan penyuplai briket arang batok kelapa (<em>100% natural coconut shell charcoal briquettes</em>) kualitas premium asal Indonesia. Berbasis di Medan, Sumatera Utara, kami melayani pesanan lokal, pasokan bisnis (B2B), hingga ekspor global.
</div>

<h2>Siapa Kami</h2>
<p>
    Indonesia merupakan produsen kelapa terbesar di dunia dengan batok kelapa berkualitas terbaik. Sumatra Briks hadir memanfaatkan potensi alami pulau Sumatera dan Indonesia untuk menghadirkan briket arang kelapa ramah lingkungan berkemampuan bakar tinggi tanpa merusak hutan.
</p>
<p>
    Diproses menggunakan teknologi pembakaran modern dan standar kontrol kualitas ketat, briket kami dirancang khusus untuk memenuhi ekspektasi tinggi para penikmat shisha/hookah serta kebutuhan pemanggangan kuliner (Barbecue / BBQ).
</p>

<h2>Visi & Misi</h2>
<h3>Visi</h3>
<p>
    Menjadi produsen dan eksportir briket arang kelapa Indonesia terdepan yang terpercaya di pasar global, dikenal atas keunggulan kualitas, konsistensi panas, dan tanggung jawab lingkungan.
</p>
<h3>Misi</h3>
<ul>
    <li><strong>Mutu Tanpa Kompromi:</strong> Memproduksi briket dengan kalori tinggi, kadar abu sangat rendah, dan waktu nyala maksimal.</li>
    <li><strong>Alami & Aman:</strong> Bebas dari bahan kimia beracun, sulfur, dan perekat kimia buatan sehingga aman untuk kesehatan konsumen.</li>
    <li><strong>Keberlanjutan Lingkungan:</strong> Menggunakan 100% limbah batok kelapa terbarukan sebagai wujud dukungan terhadap ekonomi sirkular dan petani kelapa lokal.</li>
    <li><strong>Pelayanan Profesional:</strong> Memberikan layanan transaksi yang aman, pengiriman tepat waktu, serta solusi kustomisasi packaging bagi mitra bisnis.</li>
</ul>

<h2>Keunggulan Briket Sumatra Briks</h2>
<ul>
    <li><strong>100% Bahan Alami:</strong> Batok kelapa murni tanpa campuran arang kayu, debu tambang, atau zat aditif kimia.</li>
    <li><strong>Panas Tahan Lama:</strong> Durasi pembakaran konsisten hingga lebih dari 2,5 jam dengan nilai kalori tinggi di atas 7.200 kcal/kg.</li>
    <li><strong>Abu Putih & Sangat Minim (Low Ash &lt; 2,5%):</strong> Tidak mengotori shisha bowl atau makanan pemanggang, serta menjaga keaslian rasa dan aroma.</li>
    <li><strong>Bebas Percikan & Bebas Bau:</strong> Tidak meletup saat dinyalakan (<em>sparkless</em>), tidak berasap pedih (<em>smokeless</em>), dan tidak menimbulkan bau menyengat (<em>odorless</em>).</li>
    <li><strong>Pilihan Bentuk Lengkap:</strong> Tersedia dalam ukuran Cube (C25, C26, C27, C28), Hexagonal, serta Pillow Shape.</li>
</ul>

<h2>Layanan Kami</h2>
<p>
    Kami melayani pemesanan retail satuan melalui website resmi, pembelian grosir untuk restoran BBQ &amp; shisha lounge, hingga pengiriman kontainer (FCL/LCL) dengan fasilitas OEM / Private Label kemasan untuk pembeli luar negeri.
</p>
HTML;

        $qnaContent = <<<'HTML'
<p class="text-sm text-stone-600 mb-6">
    Temukan jawaban atas pertanyaan umum seputar produk briket arang kelapa Sumatra Briks, prosedur pemesanan, pengiriman, dan konfirmasi pembayaran di bawah ini.
</p>

<div class="faq-item">
    <h3>1. Apa keunggulan briket Sumatra Briks dibanding arang biasa?</h3>
    <p>
        Briket kami terbuat dari 100% batok kelapa tua pilihan Indonesia tanpa campuran serbuk kayu atau bahan kimia berbahaya. Keunggulannya meliputi waktu menyala jauh lebih lama (hingga 2,5+ jam), panas stabil di atas 7.200 kcal/kg, tanpa asap pedih (<em>smokeless</em>), tanpa bau menyengat (<em>odorless</em>), tanpa letupan percikan api (<em>sparkless</em>), dan menghasilkan abu putih tipis kurang dari 2,5%.
    </p>
</div>

<div class="faq-item">
    <h3>2. Varian dan ukuran apa saja yang tersedia di Sumatra Briks?</h3>
    <p>Kami menyediakan varian briket sesuai peruntukan penggunaan:</p>
    <ul>
        <li><strong>Cube C25 (25×25×25 mm):</strong> Standar shisha/hookah terpopuler di dunia.</li>
        <li><strong>Cube C26 (26×26×26 mm):</strong> Kalori ekstra untuk sesi shisha lebih intens.</li>
        <li><strong>Cube C27 &amp; C28:</strong> Ukuran jumbo untuk mangkuk shisha besar dan durasi ekstra panjang.</li>
        <li><strong>Hexagonal:</strong> Cocok untuk pemakaian shisha jangka panjang dan panggangan daging BBQ.</li>
        <li><strong>Pillow / Bantal:</strong> Sangat ideal untuk memanggang BBQ outdoor dengan sirkulasi udara optimal.</li>
    </ul>
</div>

<div class="faq-item">
    <h3>3. Bagaimana cara memesan produk di website ini?</h3>
    <ol>
        <li>Buka menu <strong>Products</strong> dan pilih varian briket yang Anda perlukan.</li>
        <li>Tentukan jumlah dan klik <strong>Tambah ke Keranjang</strong>.</li>
        <li>Masuk ke menu <strong>Keranjang</strong> lalu klik <strong>Lanjut ke Checkout</strong>.</li>
        <li>Isi data nama lengkap penerima, nomor WhatsApp aktif, dan alamat pengiriman lengkap.</li>
        <li>Klik <strong>Buat Pesanan</strong>. Anda akan diarahkan ke halaman invoice dengan instruksi transfer Bank BRI resmi.</li>
        <li>Lakukan transfer sesuai total nominal dan unggah foto bukti transfer di halaman invoice tersebut.</li>
    </ol>
</div>

<div class="faq-item">
    <h3>4. Ke mana nomor rekening pembayaran resmi yang sah?</h3>
    <p>
        Pembayaran resmi hanya ditujukan ke rekening perusahaan resmi kami:
    </p>
    <div class="highlight-box">
        <strong>Bank:</strong> BRI (Bank Rakyat Indonesia)<br>
        <strong>Nomor Rekening:</strong> 20601010804563<br>
        <strong>Atas Nama:</strong> AKRATAMA SADAWIRA GRUP
    </div>
    <p>
        <em>Peringatan:</em> Jangan pernah melakukan transfer ke rekening selain atas nama <strong>AKRATAMA SADAWIRA GRUP</strong>.
    </p>
</div>

<div class="faq-item">
    <h3>5. Bagaimana penentuan ongkos kirim (ongkir)?</h3>
    <p>
        Total nilai yang tertera di halaman checkout adalah total harga produk. Biaya ongkos kirim belum termasuk dan akan dikonfirmasi secara langsung oleh admin toko ke nomor WhatsApp Anda untuk memilih opsi ekspedisi/kargo terbaik dan paling hemat ke kota tujuan Anda.
    </p>
</div>

<div class="faq-item">
    <h3>6. Berapa lama estimasi waktu pengiriman barang?</h3>
    <p>
        Setelah transfer berhasil diverifikasi oleh admin kami:
    </p>
    <ul>
        <li>Wilayah Sumatera &amp; Jawa: estimasi 2–4 hari kerja.</li>
        <li>Wilayah luar pulau: estimasi 4–7 hari kerja (bergantung rute dan pilihan ekspedisi/kargo).</li>
    </ul>
    <p>Nomor resi pengiriman akan dibagikan langsung oleh admin via WhatsApp.</p>
</div>

<div class="faq-item">
    <h3>7. Apakah Sumatra Briks melayani pemesanan partai besar (B2B) dan ekspor?</h3>
    <p>
        Ya! Kami melayani pasokan kontainer 20ft / 40ft (FCL &amp; LCL) untuk pasar ekspor serta pesanan grosir kontinu untuk kafe shisha, restoran BBQ, dan distributor di seluruh Indonesia. Kami juga menyediakan layanan kemasan private label (OEM). Silakan hubungi kami via email <a href="mailto:info@sumatrabriks.com">info@sumatrabriks.com</a>.
    </p>
</div>

<div class="faq-item">
    <h3>8. Bagaimana jika pesanan saya rusak saat diterima?</h3>
    <p>
        Harap rekam video <em>unboxing</em> tanpa jeda saat pertama kali membuka paket. Jika terjadi kerusakan fisik produk akibat kendala pengiriman, hubungi admin WhatsApp kami dalam waktu 1×24 jam sejak barang diterima untuk proses klaim kompensasi atau penggantian.
    </p>
</div>
HTML;

        $privacyContent = <<<'HTML'
<div class="highlight-box">
    Kebijakan Privasi ini menerangkan komitmen <strong>Sumatra Briks</strong> (dioperasikan oleh <strong>AKRATAMA SADAWIRA GRUP</strong>) dalam melindungi privasi, kerahasiaan, dan keamanan informasi pribadi Anda saat mengakses dan bertransaksi di situs web kami (<code>https://sumatrabriks.com</code>).
</div>

<h2>1. Informasi yang Kami Kumpulkan</h2>
<p>
    Untuk memproses pesanan dan memberikan pelayanan terbaik, kami mengumpulkan data yang Anda berikan secara sukarela pada formulir checkout dan komunikasi layanan pelanggan, yang meliputi:
</p>
<ul>
    <li><strong>Identitas Diri:</strong> Nama lengkap penerima barang.</li>
    <li><strong>Informasi Kontak:</strong> Nomor telepon seluler / WhatsApp aktif dan alamat email (jika disediakan).</li>
    <li><strong>Alamat Pengiriman:</strong> Alamat jalan, RT/RW, kelurahan, kecamatan, kota/kabupaten, provinsi, dan kode pos untuk tujuan pengantaran paket.</li>
    <li><strong>Bukti Pembayaran:</strong> Foto struk atau tangkapan layar bukti transfer bank yang diunggah untuk verifikasi pesanan.</li>
</ul>

<h2>2. Penggunaan Informasi</h2>
<p>Informasi pribadi Anda digunakan untuk tujuan-tujuan berikut:</p>
<ul>
    <li>Memproses dan menerbitkan invoice pesanan resmi Anda.</li>
    <li>Menghubungi Anda via WhatsApp/telepon guna konfirmasi opsi kurir, tarif ongkos kirim, dan pelacakan resi.</li>
    <li>Mencocokkan pembayaran transfer bank Anda dengan mutasi rekening resmi perusahaan.</li>
    <li>Menyampaikan paket barang ke alamat penerima yang benar melalui jasa ekspedisi terpercaya.</li>
    <li>Memberikan dukungan purnajual (<em>customer support</em>) apabila terjadi kendala pengiriman.</li>
</ul>

<h2>3. Perlindungan &amp; Keamanan Data Pribadi</h2>
<p>
    Kami mengambil langkah-langkah teknis dan organisasional yang wajar untuk menjaga data pribadi Anda dari akses tidak berwenang, kebocoran, pengubahan, atau perusakan:
</p>
<ul>
    <li><strong>Penyamaran Data Publik (Data Masking):</strong> Rincian nomor telepon dan alamat pada tautan invoice publik disamarkan demi mencegah kebocoran informasi kepada pengunjung internet lain yang tidak berhak.</li>
    <li><strong>Kerahasiaan Mutlak:</strong> Kami <strong>tidak pernah menjual, menyewakan, atau memperdagangkan</strong> data pribadi Anda kepada pihak ketiga mana pun untuk tujuan pemasaran produk luar.</li>
</ul>

<h2>4. Berbagi Informasi dengan Pihak Ketiga</h2>
<p>
    Data penerima (nama, telepon, dan alamat pengiriman) hanya dibagikan kepada pihak penyedia layanan kurir/ekspedisi logistik rekanan (seperti JNE, J&amp;T, SiCepat, Kargo Darat/Laut) semata-mata untuk tujuan fisik pengantaran produk pesanan Anda.
</p>

<h2>5. Hak Pengguna</h2>
<p>
    Anda berhak untuk mengonfirmasi data pribadi Anda yang tersimpan di sistem kami, meminta perbaikan jika terdapat kesalahan data, atau meminta penghapusan riwayat kontak setelah transaksi pembelian selesai dipenuhi.
</p>

<h2>6. Kontak Kami</h2>
<p>
    Bila Anda memiliki pertanyaan, masukan, atau kendala terkait Kebijakan Privasi ini, silakan hubungi tim kami melalui:
</p>
<ul>
    <li><strong>Email:</strong> <a href="mailto:info@sumatrabriks.com">info@sumatrabriks.com</a></li>
    <li><strong>Perusahaan:</strong> AKRATAMA SADAWIRA GRUP</li>
    <li><strong>Lokasi:</strong> Medan, Sumatera Utara, Indonesia</li>
</ul>
HTML;

        $termsContent = <<<'HTML'
<div class="highlight-box">
    Syarat &amp; Ketentuan berikut mengatur penggunaan situs web serta seluruh transaksi pembelian produk briket arang batok kelapa di <strong>Sumatra Briks</strong> (dikelola oleh <strong>AKRATAMA SADAWIRA GRUP</strong>). Dengan melakukan pemesanan di situs kami, Anda menyatakan telah membaca, memahami, dan menyetujui seluruh poin di bawah ini.
</div>

<h2>1. Ketentuan Umum</h2>
<ul>
    <li>Situs ini melayani transaksi pembelian briket arang batok kelapa untuk pelanggan ritel, pemilik usaha (B2B), maupun mitra ekspor.</li>
    <li>Pengguna wajib memberikan data identitas diri, nomor kontak WhatsApp, dan alamat pengiriman yang benar dan dapat dihubungi.</li>
    <li>Kami berhak memperbarui Syarat &amp; Ketentuan ini sewaktu-waktu sesuai dengan perkembangan hukum dan kebijakan operasional bisnis.</li>
</ul>

<h2>2. Informasi Produk &amp; Ketersediaan</h2>
<ul>
    <li>Kami menyajikan deskripsi, foto produk, spesifikasi kalori, dan dimensi kemasan seakurat mungkin. Namun, sedikit variasi warna kemasan pada layar dapat terjadi akibat pencahayaan foto.</li>
    <li>Ketersediaan stok bergerak secara dinamis. Apabila varian produk yang dipesan ternyata habis setelah checkout dibuat, admin kami akan segera mengonfirmasi kepada Anda untuk opsi penukaran varian atau pengembalian dana penuh (<em>full refund</em>).</li>
</ul>

<h2>3. Pemesanan &amp; Pembayaran</h2>
<ul>
    <li>Pesanan berstatus resmi terbit setelah formulir checkout disubmit dan memperoleh Nomor Invoice (contoh: <code>INV/YYYYMMDD/XXXX</code>).</li>
    <li>Pembayaran hanya sah apabila ditransfer tepat sesuai nominal ke rekening bank resmi perusahaan:
        <br><strong>Bank BRI No. Rek: 20601010804563 a.n. AKRATAMA SADAWIRA GRUP</strong>.
    </li>
    <li>Batas waktu pembayaran dan upload bukti transfer adalah <strong>24 jam</strong> sejak pesanan dibuat. Pesanan yang belum diselesaikan pembayarannya melampaui batas waktu tersebut dapat dibatalkan otomatis oleh sistem.</li>
    <li>Status pesanan awal adalah <em>pending</em> (menunggu pembayaran). Pesanan akan diproses ke tahap pengiriman setelah admin memvalidasi mutasi transfer pada rekening resmi.</li>
</ul>

<h2>4. Biaya Pengiriman (Ongkos Kirim)</h2>
<ul>
    <li>Nominal transaksi pada sistem checkout merupakan total harga produk dan <strong>belum termasuk ongkos kirim</strong>.</li>
    <li>Biaya pengiriman akan dihitung berdasarkan berat kotor paket dan alamat tujuan, kemudian dikonfirmasikan oleh admin via WhatsApp sebelum pesanan dikirimkan.</li>
    <li>Pengiriman dilakukan menggunakan mitra kurir ekspedisi atau kargo darat/laut yang telah disepakati bersama pelanggan.</li>
</ul>

<h2>5. Kebijakan Pembatalan, Retur &amp; Garansi</h2>
<ul>
    <li><strong>Pembatalan:</strong> Pembatalan pesanan hanya dapat dilakukan sebelum barang diserahkan ke pihak ekspedisi logistik.</li>
    <li><strong>Klaim Kerusakan / Salah Kirim:</strong> Pelanggan wajib menyertakan video <em>unboxing</em> utuh tanpa potongan dalam kurun waktu 1×24 jam sejak barang diterima dari kurir.</li>
    <li>Kami akan mengganti produk yang terbukti cacat produksi atau salah kirim tanpa membebankan biaya tambahan kepada pembeli.</li>
</ul>

<h2>6. Hukum yang Mengatur</h2>
<p>
    Syarat &amp; Ketentuan ini diatur dan ditafsirkan berdasarkan ketentuan hukum yang berlaku di Negara Kesatuan Republik Indonesia.
</p>
HTML;

        $returnContent = <<<'HTML'
<div class="highlight-box">
    Kepuasan dan kenyamanan berbelanja Anda adalah komitmen utama <strong>Sumatra Briks</strong> (dioperasikan oleh <strong>AKRATAMA SADAWIRA GRUP</strong>). Kami menjamin mutu setiap briket arang batok kelapa yang kami kirimkan. Halaman ini memuat panduan lengkap mengenai kebijakan pengembalian barang (<em>return</em>) dan pengembalian dana (<em>refund</em>).
</div>

<h2>1. Syarat Kelayakan Pengembalian Produk</h2>
<p>
    Pengajuan retur atau penggantian produk dapat diproses apabila memenuhi salah satu kriteria berikut:
</p>
<ul>
    <li><strong>Produk Rusak Saat Pengiriman:</strong> Kemasan box hancur parah atau briket di dalamnya pecah berkeping-keping akibat kelalaian penanganan kurir ekspedisi logistik.</li>
    <li><strong>Salah Kirim Varian / Dimensi:</strong> Produk yang diterima tidak cocok dengan invoice pemesanan (misalnya: Anda memesan Cube C25, namun menerima Cube C28 atau Pillow).</li>
    <li><strong>Kekurangan Kuantitas:</strong> Jumlah box atau berat bersih pesanan yang diterima kurang dari rincian pada invoice pembelian.</li>
    <li><strong>Cacat Kualitas Produksi:</strong> Briket terbukti memiliki kadar air berlebih atau tidak dapat menyala stabil sesuai standar spesifikasi mutu resmi kami.</li>
</ul>

<h2>2. Ketentuan Wajib Klaim (Video Unboxing)</h2>
<p>
    Untuk mempercepat proses investigasi dan klaim kompensasi, mohon perhatikan ketentuan berikut:
</p>
<ul>
    <li><strong>Video Unboxing Utuh:</strong> Pelanggan wajib merekam video pembukaan paket secara utuh sejak resi masih menempel di paket, tanpa jeda (<em>no pause</em>) dan tanpa penyuntingan (<em>no edit</em>).</li>
    <li><strong>Batas Waktu Pelaporan:</strong> Klaim kendala wajib dilaporkan dalam kurun waktu maksimal <strong>1×24 jam</strong> sejak paket dinyatakan sampai oleh sistem pelacakan ekspedisi.</li>
    <li><strong>Kondisi Briket:</strong> Produk briket yang diretur harus dalam kondisi utuh seperti saat diterima dan belum dipakai melebihi 1-2 sampel uji coba.</li>
    <li><strong>Lampiran Invoice:</strong> Sertakan Nomor Invoice resmi (contoh: <code>INV/YYYYMMDD/XXXX</code>) pada saat menghubungi layanan pelanggan kami.</li>
</ul>

<h2>3. Tahapan Pengajuan Pengembalian</h2>
<ol>
    <li><strong>Hubungi Admin Toko:</strong> Hubungi admin layanan pelanggan via WhatsApp atau kirim email ke <a href="mailto:info@sumatrabriks.com">info@sumatrabriks.com</a> dengan menyertakan Nomor Invoice dan rekaman video <em>unboxing</em>.</li>
    <li><strong>Penilaian Tim Quality Control:</strong> Tim kami akan memverifikasi bukti yang Anda kirimkan dalam waktu maksimal 1×24 jam kerja.</li>
    <li><strong>Persetujuan &amp; Solusi:</strong> Setelah klaim dinyatakan valid, admin akan memberikan opsi solusi terbaik (pengiriman produk pengganti atau pengembalian dana).</li>
</ol>

<h2>4. Opsi Penyelesaian (Replacement atau Refund)</h2>
<p>Pelanggan berhak memilih solusi yang diinginkan:</p>
<ul>
    <li><strong>Penggantian Barang Baru (Replacement):</strong> Kami segera mengirimkan produk pengganti baru ke alamat Anda. Seluruh biaya ongkos kirim pengiriman barang pengganti ditanggung 100% oleh Sumatra Briks.</li>
    <li><strong>Pengembalian Dana Penuh (Full Refund):</strong> Jika stok produk pengganti kosong atau Anda memilih pengembalian dana, dana pembelian akan ditransfer langsung ke rekening bank Anda dalam kurun waktu 1–2 hari kerja setelah verifikasi disetujui.</li>
</ul>

<h2>5. Biaya Ongkos Kirim Retur</h2>
<ul>
    <li>Jika pengembalian disebabkan oleh kelalaian toko (salah varian, cacat mutu) atau kerusakan dalam perjalanan ekspedisi, <strong>seluruh biaya ongkir pengembalian dan pengiriman ulang ditanggung sepenuhnya oleh Sumatra Briks</strong>.</li>
    <li>Pengembalian barang karena pembatalan sepihak setelah barang dikirim atau salah memilih varian oleh pembeli tidak dapat kami terima.</li>
</ul>

<h2>6. Kontak Layanan Bantuan Retur</h2>
<p>
    Untuk konsultasi dan bantuan mengenai proses retur barang, silakan hubungi kami melalui:
</p>
<ul>
    <li><strong>Email:</strong> <a href="mailto:info@sumatrabriks.com">info@sumatrabriks.com</a></li>
    <li><strong>WhatsApp:</strong> Nomor admin toko resmi yang tertera pada konfirmasi pesanan Anda</li>
    <li><strong>Alamat Operasional:</strong> AKRATAMA SADAWIRA GRUP — Medan, Sumatera Utara, Indonesia</li>
</ul>
HTML;

        $pages = [
            [
                'title' => 'Kebijakan Privasi',
                'slug' => 'kebijakan-privasi',
                'content' => $privacyContent,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Syarat & Ketentuan',
                'slug' => 'syarat-ketentuan',
                'content' => $termsContent,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Syarat & Ketentuan (Terms and Conditions)',
                'slug' => 'term-and-condition',
                'content' => $termsContent,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Kebijakan Pengembalian (Return Policy)',
                'slug' => 'kebijakan-pengembalian',
                'content' => $returnContent,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Kebijakan Pengembalian (Return Policy)',
                'slug' => 'return-policy',
                'content' => $returnContent,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Tentang Kami',
                'slug' => 'about',
                'content' => $aboutContent,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Pertanyaan Umum (Q&A / FAQ)',
                'slug' => 'qna',
                'content' => $qnaContent,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('pages')->upsert($pages, ['slug'], ['title', 'content', 'updated_at']);
    }
}

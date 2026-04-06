<?php

namespace App\Livewire;

use Livewire\Component;

class HelpCenter extends Component
{
    public $faqs = [];

    public function mount()
    {
        $this->faqs = [
            [
                'q' => 'Bagaimana cara melakukan pemesanan (Cara Belanja)?',
                'a' => "1. PILIH PRODUK: Cari dan tentukan produk pilihan Anda.\n2. KERANJANG: Klik tombol + Keranjang atau langsung Beli Sekarang.\n3. CHECKOUT: Pastikan alamat pengiriman & kontak sudah benar.\n4. PEMBAYARAN: Pilih metode pembayaran yang tersedia.\n5. SELESAI: Klik Buat Pesanan & bayar sesuai instruksi.",
            ],
            [
                'q' => 'Metode pembayaran apa saja yang tersedia?',
                'a' => 'Kami mendukung berbagai metode pembayaran melalui Midtrans: Transfer Bank (BCA, Mandiri, BNI, BRI), Kartu Kredit/Debit, QRIS, GoPay, ShopeePay, OVO, dan Dana.',
            ],
            [
                'q' => 'Berapa lama waktu pengiriman?',
                'a' => 'Waktu pengiriman tergantung dari kurir dan lokasi tujuan. Estimasi umumnya 2-5 hari kerja untuk dalam kota, dan 3-7 hari kerja untuk luar kota. Kami bekerja sama dengan J&T, JNE, dan SiCepat.',
            ],
            [
                'q' => 'Bagaimana cara melacak pesanan saya?',
                'a' => 'Anda bisa melacak pesanan di halaman "Pesanan Saya" setelah login. Nomor resi pengiriman akan tersedia setelah pesanan dikirim oleh mitra toko kami.',
            ],
            [
                'q' => 'Apakah saya bisa membatalkan pesanan?',
                'a' => 'Pembatalan pesanan dapat dilakukan selama status masih "Menunggu Pembayaran". Jika sudah dibayar, silakan hubungi Customer Service kami segera.',
            ],
            [
                'q' => 'Bagaimana jika barang yang diterima rusak?',
                'a' => 'Kami memberikan garansi retur jika barang yang diterima tidak sesuai atau rusak. Silakan hubungi kami maksimal 2x24 jam setelah barang diterima dengan melampirkan video unboxing.',
            ],
        ];
    }

    public function render()
    {
        return view('livewire.help-center');
    }
}

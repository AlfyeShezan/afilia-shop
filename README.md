<div align="center">
  # 🛒 Afilia Shop - Enterprise E-Commerce & Marketplace

  Sebuah platform E-Commerce skala Enterprise (Marketplace) modern yang dibangun dengan **Laravel 12**, **Livewire v3**, dan **Tailwind CSS**. Afilia Shop menyediakan fitur multi-role (Super Admin, Admin, Staff, Vendor, dan Customer) yang memungkinkan multi-seller berjualan di satu platform layaknya Tokopedia/Shopee.
</div>

<br />

## 🌟 Fitur Utama (Core Features)

* **Multi-Role & Permissions**: Menggunakan `spatie/laravel-permission` untuk membatasi hak akses (Super Admin, Admin, Staff, Vendor, Customer).
* **Multi-Seller (Vendor Dashboard)**: Vendor dapat mendaftar, mengelola produk sendiri, melihat pesanan, dan melakukan penarikan dana (Withdrawal).
* **Admin Dashboard**: Manajemen penuh atas kategori, produk, pesanan, pengguna, vendor, voucher, dan pengaturan sistem.
* **Manajemen Produk & Kategori**: Mendukung variasi produk, stok, harga, dan multi-level kategori.
* **Payment Gateway**: Terintegrasi dengan **Midtrans** untuk pembayaran online yang aman dan real-time.
* **Cart & Checkout System**: Sistem keranjang belanja dengan dukungan multi-alamat dan penggunaan voucher diskon.
* **Order History & Invoicing**: Melacak riwayat pesanan pelanggan dan mengenerate invoice format PDF (via `laravel-dompdf`).
* **Livewire SPA-like Experience**: Antarmuka responsif tanpa reload halaman dengan Livewire v3 & Volt.

---

## 🛠️ Teknologi yang Digunakan (Tech Stack)

* **Backend**: Laravel Framework (^12.0), PHP (^8.2)
* **Frontend**: Livewire (^3.6.4), Tailwind CSS, Alpine.js
* **Database**: MySQL / MariaDB
* **Authentication**: Laravel Breeze
* **Role & Permission**: Spatie Laravel Permission
* **Payment Gateway**: Midtrans PHP
* **PDF Generator**: Barryvdh Laravel DomPDF

---

## 📂 Struktur Direktori Utama

```text
toko-online/
├── app/
│   ├── Http/Controllers/      # Controller untuk API & Payment Callbacks
│   ├── Livewire/              # Komponen Livewire (Cart, Checkout, Dashboard dll)
│   ├── Models/                # Eloquent Models (User, Product, Order, dll)
│   └── Providers/             # Service Providers
├── database/
│   ├── migrations/            # Skema database
│   └── seeders/               # Data dummy & inisialisasi Role (DatabaseSeeder)
├── resources/
│   ├── views/
│   │   ├── livewire/          # View/Blade untuk Livewire Components
│   │   └── components/        # Blade UI components
│   └── css & js/              # Tailwind directives & app setup
├── routes/
│   └── web.php                # Definisi routing, middleware, dan grup navigasi
└── public/                    # Aset statis & entry point
```

---

## 🚀 Cara Instalasi & Menjalankan (Installation & Setup)

Ikuti langkah-langkah di bawah ini untuk menjalankan project ini di environment lokal Anda (misal menggunakan XAMPP/Laragon).

1. **Clone Repository (atau Download ZIP)**
   ```bash
   git clone https://github.com/username/afilia-shop.git
   cd afilia-shop
   ```

2. **Install Composer Dependencies**
   ```bash
   composer install
   ```

3. **Install NPM Dependencies**
   ```bash
   npm install
   npm run build
   ```

4. **Konfigurasi Environment (.env)**
   * Copy file `.env.example` ke `.env`
   * Buka konfigurasi `.env`, dan sesuaikan informasi Database Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=root
   DB_PASSWORD=
   
   MIDTRANS_SERVER_KEY=your_midtrans_server_key
   MIDTRANS_CLIENT_KEY=your_midtrans_client_key
   ```

5. **Generate App Key & Migrasi Database**
   Jalankan perintah ini untuk melakukan migrasi beserta data seeder (Roles, Akun Dummy, dsb).
   ```bash
   php artisan key:generate
   php artisan migrate:fresh --seed
   ```

6. **Jalankan Aplikasi Lokal**
   ```bash
   php artisan serve
   ```
   Aplikasi akan dapat diakses di: `http://localhost:8000`

---

## 🔐 Kredensial Default (Testing)

Karena database dilakukan *seeding*, Anda dapat login menggunakan akun berikut untuk keperluan testing:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Super Admin** | `admin@afilia.shop` | `password` |

Untuk akses login, silakan buka: `http://localhost:8000`

---

## 🖼️ Tampilan Aplikasi (Screenshots)

*[![alt text](image.png)]*

*[![alt text](image-1.png)]*

---

## 👤 Author & Kontributor

* **[Alfi Dias Saputra]** - *Lead Developer* - [https://github.com/AlfyeShezan]

<br/>

> **Note**: Disarankan menggunakan server web lokal seperti XAMPP atau perintah artisan serve. Pastikan versi PHP pada environment Anda adalah 8.2 atau lebih tinggi.

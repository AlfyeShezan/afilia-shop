
  # 🛒 Afilia Shop - Enterprise E-Commerce

  **Afilia Shop** adalah platform E-Commerce berskala modern yang dibangun menggunakan **Laravel 12**. Sistem ini dirancang untuk mempermudah proses jual beli secara online serta menyediakan sistem manajemen pesanan, produk, stok, dan pembayaran secara komprehensif bagi pemilik toko. Website ini memanfaatkan teknologi **Livewire v3** untuk memberikan pengalaman layaknya SPA (Single Page Application) yang cepat dan responsif layaknya aplikasi *native*.
</div>

<br />

## 📸 Tampilan Aplikasi
| Halaman Utama | Dashboard Admin |
| :---: | :---: |
| ![alt text](image.png) | ![alt text](image-1.png) |

---

## 🚀 Fitur Unggulan
* **Sistem Multi-Role:** Pembagian hak akses terpusat (Super Admin, Admin, Staff, dan Customer) menggunakan *Spatie Permission*.
* **Payment Gateway Integration:** Terhubung dengan **Midtrans** untuk memproses berbagai metode pembayaran secara otomatis dan *real-time*.
* **Manajemen Produk Kompleks:** Mendukung kategori bertingkat, variasi harga (SKU), manajemen stok, harga diskon, hingga status produk.
* **Keranjang Belanja & Wishlist:** Sistem Cart yang efisien dilengkapi fitur penyimpanan produk favorit (Wishlist).
* **Multi-Alamat Pengiriman:** Pengguna dapat menyimpan lebih dari satu daftar alamat pribadi untuk keperluan *checkout*.
* **Sistem Voucher & Diskon:** Pemilik toko dapat membuat voucher kupon diskon (tetap atau persentase) dilengkapi dengan limitasi penggunaan & masa aktif.
* **Invoice Digital:** Fitur *generate invoice* otomatis berformat PDF untuk setiap pesanan yang berhasil.
* **Riwayat Pesanan Pelanggan:** Fitur *tracking* lengkap untuk berbagai status pesanan pelanggan.

---

## 🛠️ Teknologi yang Digunakan
Project ini dibangun menggunakan ekosistem standar industri profesional:

* **Backend:** Laravel Framework (^12.0) dengan PHP (^8.2)
* **Database:** MySQL / MariaDB
* **Frontend:** Livewire (^3.6), Alpine.js
* **Styling:** Tailwind CSS
* **Tools:** XAMPP, npm/Node.js, Composer
* **Library Ekstra:** 
  * Midtrans (Payment Gateway)
  * Laravel-DomPDF (Generate PDF)
  * Spatie Laravel Permission (Roles & Permissions)
  * Laravel Breeze (Authentication)

---

## 📂 Struktur Folder
Berikut adalah struktur direktori utama pada project ini (berdasarkan arsitektur Laravel):

```text
/afilia-shop
├── app/               # Logic aplikasi (Models, Traits, Helpers)
│   ├── Http/          # Controller (Midtrans, Invoice)
│   └── Livewire/      # Komponen Livewire (Cart, Checkout, Manager, Dashboard)
├── database/          # File Migrasi & Sistem Seeder (Data Dummy)
├── public/            # File statis (CSS, JS build, upload gambar)
├── resources/         
│   ├── css/ & js/     # Konfigurasi Tailwind & JavaScript
│   └── views/         # File Blade (.blade.php) untuk UI aplikasi
├── routes/            # Definisi rute URL (web.php, auth.php)
├── .env.example       # Contoh environment variable
├── composer.json      # Dependencies PHP
└── package.json       # Dependencies Node/NPM
```

---

## 💻 Cara Instalasi & Menjalankan
Ikuti langkah-langkah berikut untuk menjalankan project pada server lokal (misalnya menggunakan XAMPP):

### 1. Persiapan Environment
Pastikan Anda telah menginstal **XAMPP**, **Composer**, dan **Node.js**. Pastikan versi **PHP yang digunakan minimal 8.2**.

### 2. Clone Repository
Buka terminal dan clone project ini ke dalam folder `htdocs` Anda:

```bash
cd C:\xampp4\htdocs
git clone https://github.com/AlfyeShezan/afilia-shop.git
cd afilia-shop
```

### 3. Install Dependencies
Jalankan perintah berikut untuk mengunduh ragam package pihak ketiga yang diperlukan:
```bash
composer install
npm install
npm run build
```

### 4. Konfigurasi Database & Environment
1. Buat file `.env` dengan menduplikat bawaan `.env.example`:
   ```bash
   cp .env.example .env
   ```
2. Buka `phpMyAdmin` (http://localhost/phpmyadmin) lalu buat database baru (misal: `toko_online`).
3. Sesuaikan konfigurasi pada file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=toko_online
   DB_USERNAME=root
   DB_PASSWORD=

   MIDTRANS_SERVER_KEY=kode_api_midtrans_server_anda_disini
   MIDTRANS_CLIENT_KEY=kode_api_midtrans_client_anda_disini
   ```

### 5. Generate Key & Migrasi Data
Untuk membuat tabel dan mengisi akun pengguna sementara (*dummy data*), jalankan perintah:
```bash
php artisan key:generate
php artisan migrate:fresh --seed
```

### 6. Jalankan Aplikasi
```bash
php artisan serve
```
Buka browser dan segera mulai jalankan toko online Anda pada alamat:
**`http://localhost:8000`**

---

## 👤 Akun Default (Demo)
Sistem *seeder* database telah secara otomatis menambahkan akun contoh berikut untuk kebutuhan percobaan `/login`:

**Administrator Utama:**
* **Username:** `admin@afilia.shop`
* **Password:** `password`

**Staff / Pegawai:**
* **Username:** `staff@afilia.shop`
* **Password:** `password`

**Pelanggan Percobaan:**
* **Username:** `customer@afilia.shop`
* **Password:** `password`

*(Anda dan pembeli juga dapat mendaftar membuat akun baru secara mandiri di halaman Register web utama).*

---

## 🤝 Kontribusi
Aplikasi ini selalu terbuka bagi kontribusi positif! Jika Anda ingin meningkatkannya:

1. Lakukan **Fork** pada repositori ini.
2. Buat **branch** khusus untuk modifikasi (`git checkout -b perbaikan-ui`).
3. **Commit** perbaruan fitur yang ditambahkan (`git commit -m 'Memperbaiki tampilan Cart'`).
4. **Push** temuan ke branch fork milikmu (`git push origin perbaikan-ui`).
5. Jangan ragu buat **Pull Request**!

---

## 📝 Author
**Alfi Dias Saputra**

* **GitHub:** [https://github.com/AlfyeShezan/](https://github.com/AlfyeShezan/)
* **Email:** alfidias1511@gmail.com

*Managed with ❤️ by [Alfye]*

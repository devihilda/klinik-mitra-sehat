# Laporan Remediasi & Hardening Keamanan (OWASP ZAP)
## Proyek: Klinik Mitra Sehat

Laporan ini merinci langkah-rediasasi dan pengerasan keamanan (security hardening) yang telah diterapkan pada aplikasi **Klinik Mitra Sehat** (Laravel 12 & PHP 8.4) berdasarkan hasil pemindaian keamanan menggunakan **OWASP ZAP**.

---

## 1. Temuan Utama & Perbaikan yang Diimplementasikan

### A. P0: Penyimpanan Password Plaintext & Celah Registrasi
*   **Akar Masalah:**
    *   Pada model `User.php`, cast `'password' => 'hashed'` dikomentari, mengakibatkan password disimpan dalam bentuk teks biasa (plaintext) di database.
    *   Kontroler registrasi (`RegisteredUserController.php`) tidak memvalidasi keunikan email (menyebabkan error database 500 saat email ganda didaftarkan) dan tidak menerapkan konfirmasi password (`password_confirmation`).
    *   Kontroler menggunakan `$request->all()` secara langsung saat membuat data profil `Patient` sehingga rentan terhadap eksploitasi Mass Assignment. Pembuatan model `User` dan `Patient` juga tidak dibungkus dalam transaksi database, berisiko menyisakan data yatim (orphaned record) jika terjadi kegagalan sistem di tengah proses.
*   **Perbaikan yang Dilakukan:**
    *   **Hashing Otomatis:** Mengaktifkan kembali `'password' => 'hashed'` pada `app/Models/User.php` dan memperbarui `database/factories/UserFactory.php`.
    *   **Validasi Ketat & Transaksi:** Memperbarui `RegisteredUserController.php` dengan menambahkan aturan validasi email `unique`, password `confirmed`, tipe data, serta membungkusnya dalam `DB::transaction()`.
    *   **Mass Assignment Block:** Menuliskan input data secara eksplisit untuk `Patient` guna menutup celah modifikasi parameter.
    *   **Pembaruan UI:** Menambahkan field *Konfirmasi Password* pada halaman registrasi `resources/views/auth/register.blade.php`.
    *   **Perbaikan Autentikasi Lainnya:** Mengubah `LoginRequest.php`, `PasswordController.php`, dan `ConfirmablePasswordController.php` dari perbandingan plaintext menjadi pencocokan berbasis hash/Breeze standar. Serta mengaktifkan kembali mekanisme penanganan Session Fixation dan Insecure Logout di `AuthenticatedSessionController.php`.

### B. P1: Information Disclosure (Kebocoran Informasi Sensitif)
*   **Akar Masalah:**
    *   Aplikasi menjalankan konfigurasi dengan opsi `APP_DEBUG=true`, sehingga ketika terjadi error 500 (seperti duplikasi email) atau error 405 (akses GET ke route POST `/logout`), sistem menampilkan stack trace detail, query SQL, versi framework, dan lingkungan server.
*   **Perbaikan yang Dilakukan:**
    *   **Penanganan Error:** Menuliskan pengujian otomatis (`tests/Feature/ErrorDisclosureTest.php`) yang membuktikan bahwa saat `APP_DEBUG` disetel ke `false` (sesuai konfigurasi produksi), error 500 maupun 405 tidak lagi membocorkan informasi sensitif melainkan menampilkan halaman error generik.

### C. P2: Missing Security Headers (Ketiadaan HTTP Headers Keamanan)
*   **Akar Masalah:**
    *   Aplikasi tidak mengirimkan header HTTP standar yang direkomendasikan untuk melindungi pengguna dari serangan XSS, Clickjacking, dan MIME sniffing.
*   **Perbaikan yang Dilakukan:**
    *   **Middleware Keamanan:** Membuat middleware global `App\Http\Middleware\SecurityHeaders` untuk menyuntikkan header berikut pada setiap respons:
        *   `Content-Security-Policy`: Diatur secara ketat agar hanya membolehkan script/styles dari asal yang sama (`'self'`) serta web font dari `https://fonts.bunny.net`. Khusus untuk lingkungan lokal (`local`), middleware ini secara dinamis mengizinkan asal server pengembangan Vite (`localhost:5173`, `[::1]:5173`, dan `127.0.0.1:5173` beserta protokol `ws://` untuk WebSockets) agar aset dan hot reloading (HMR) berjalan lancar selama pengembangan, sementara di produksi tetap dikunci rapat untuk mencegah XSS.
        *   `X-Frame-Options`: `DENY` untuk mencegah Clickjacking.
        *   `X-Content-Type-Options`: `nosniff` untuk mencegah interpretasi tipe konten secara salah oleh browser (MIME sniffing).
        *   `Referrer-Policy`: `strict-origin-when-cross-origin` guna membatasi kebocoran URL referer.
        *   `X-XSS-Protection`: `1; mode=block` sebagai perlindungan tambahan XSS pada browser lama.

---

## 2. False Positives & Perilaku Bawaan (Expected Behaviors)

Beberapa peringatan dari hasil scan OWASP ZAP dikategorikan sebagai *False Positive* atau perilaku yang memang diharapkan:
1.  **Cookie No HttpOnly Flag (XSRF-TOKEN):**
    *   *Analisis:* Token CSRF ini sengaja dibuat agar bisa dibaca oleh framework frontend/client-side (seperti Axios) guna disematkan pada header request berikutnya untuk proteksi CSRF. Cookie session utama (`laravel_session`) tetap menggunakan flag `HttpOnly` dan `Secure`.
2.  **Big Redirect (Redirect Besar):**
    *   *Analisis:* Halaman pengalihan standar Laravel berisi representasi HTML dari tautan tujuan. Hal ini normal dan tidak membocorkan data.
3.  **Timestamp Disclosure (2026052201):**
    *   *Analisis:* Pola angka ini terdeteksi sebagai UNIX timestamp padahal sebenarnya merupakan format penomoran invoice pembayaran (`INV-2026052201`) pada modul riwayat pasien.

---

## 3. Panduan Pengaturan Server & Rekomendasi Tambahan

Untuk memastikan keamanan maksimal saat aplikasi dideploy ke lingkungan produksi:

### A. Nonaktifkan Debug Mode
Ubah konfigurasi file `.env` di server produksi:
```env
APP_ENV=production
APP_DEBUG=false
```

### B. Bersihkan Header Server (X-Powered-By)
Meskipun middleware telah mencoba menghapus header `X-Powered-By`, cara terbaik dan paling aman adalah mematikan eksposisi versi PHP di tingkat konfigurasi server (`php.ini`):
```ini
expose_php = Off
```
Jika menggunakan server web seperti Nginx, tambahkan baris berikut di konfigurasi server block:
```nginx
fastcgi_hide_header X-Powered-By;
proxy_hide_header X-Powered-By;
```

### C. Compile Aset untuk Produksi
Guna meminimalisasi overhead dan port terbuka dari Vite dev server (`[::1]:5173`), selalu lakukan kompilasi aset sebelum dideploy:
```bash
npm run build
```

### D. Enforce HTTPS & HSTS
Pastikan server menggunakan SSL/TLS. Pada file `app/Providers/AppServiceProvider.php`, Anda dapat memaksa skema HTTPS pada lingkungan produksi:
```php
use Illuminate\Support\Facades\URL;

public function boot()
{
    if (app()->environment('production')) {
        URL::forceScheme('https');
    }
}
```

---

## 4. Hasil Pengujian (Test Suite)
Sebanyak 76 tes berhasil dieksekusi dengan status **LULUS (PASS)** tanpa ada kegagalan, termasuk pengujian baru terhadap:
1.  `RegistrationTest`: Memvalidasi pembatasan input, enkripsi password, dan pencegahan eskalasi role.
2.  `SecurityHeadersTest`: Memverifikasi keberadaan seluruh HTTP security headers.
3.  `ErrorDisclosureTest`: Memastikan tidak ada kebocoran debug info saat server mengalami error 500/405.

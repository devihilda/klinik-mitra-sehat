# Laporan Remediasi & Hardening Keamanan (OWASP ZAP) - Tahap Final
## Proyek: Klinik Mitra Sehat

Laporan ini merinci langkah-langkah remediasi dan pengerasan keamanan (*security hardening*) yang telah selesai diimplementasikan pada aplikasi **Klinik Mitra Sehat** (Laravel 12 & PHP 8.4) berdasarkan perbandingan hasil pemindaian awal dan kedua dari **OWASP ZAP**.

---

## 1. Status Temuan & Perbaikan Keamanan

### A. Temuan Baru & Diperbaiki (Tahap Hardening Lanjutan)
1. **Broken Access Control - Role Bypass (P0 / Medium):**
   * *Akar Masalah:* `RoleMiddleware.php` hanya memeriksa apakah user sudah login atau belum, namun membiarkan request lolos tanpa membandingkan `user()->role` dengan parameter `$role` yang didefinisikan pada route. Hal ini memicu bypass otorisasi secara sistemik.
   * *Perbaikan:* Mengaktifkan kembali baris pengecekan role dan memicu `abort(403)` jika role pengguna tidak cocok.
2. **Insecure Direct Object Reference (IDOR) - Manajemen Antrean (P0 / Medium):**
   * *Akar Masalah:* `Patient\QueueController@show` dan `Patient\QueueController@destroy` tidak memverifikasi kepemilikan data antrean (`patient_id`), sehingga pasien A dapat melihat detail keluhan medis dan membatalkan antrean milik pasien B.
   * *Perbaikan:* Menambahkan pengecekan eksplisit agar `patient_id` dari model `Queue` yang diakses harus sama dengan ID profil `Patient` milik pengguna yang sedang login. Jika tidak, response akan dibatalkan dengan status `403 Forbidden`.
3. **Content Security Policy (CSP) Header Not Set (Medium):**
   * *Akar Masalah:* ZAP mendeteksi ketiadaan header CSP pada route utama, form masuk, lupa password, dashboard pasien, dan berkas statis (seperti sitemap.xml).
   * *Perbaikan:* Menerapkan kebijakan CSP yang sangat ketat pada seluruh respon non-local via [SecurityHeaders.php](file:///D:/Projek%20Laravel/klinik-mitra-sehat/app/Http/Middleware/SecurityHeaders.php).
4. **Sub Resource Integrity (SRI) & Cross-Domain Assets (Medium/Low):**
   * *Akar Masalah:* Aplikasi memuat asset Google Fonts dari `fonts.bunny.net` dan memuat asset JS/CSS dari server pengembangan Vite (`http://[::1]:5173`) yang tidak memiliki atribut `integrity`.
   * *Perbaikan:* Mengunduh semua file font (`Figtree` dan `Instrument Sans`) secara lokal di direktori `public/fonts/`, mengubah referensi `@font-face` di [app.css](file:///D:/Projek%20Laravel/klinik-mitra-sehat/resources/css/app.css), dan menghapus semua link eksternal `fonts.bunny.net` dari file layout Blade. Seluruh asset saat ini disajikan secara *Same-Origin* sehingga meniadakan kebutuhan akan SRI pihak ketiga.
5. **X-Content-Type-Options Header Missing on /robots.txt (Low):**
   * *Akar Masalah:* Berkas `robots.txt` disajikan sebagai file statis langsung oleh web server, sehingga melewati middleware aplikasi Laravel dan tidak mendapatkan header `nosniff`.
   * *Perbaikan:* Menghapus berkas fisik `public/robots.txt` dan mengalihkannya ke Laravel Route di [web.php](file:///D:/Projek%20Laravel/klinik-mitra-sehat/routes/web.php) agar disajikan dinamis dengan header keamanan lengkap.

### B. Temuan Sebelumnya yang Berhasil Dipertahankan (Tidak Regresi)
* **Penyimpanan Password Hashed:** Password tetap disimpan menggunakan enkripsi hash Bcrypt via model cast `'password' => 'hashed'` di `User.php`.
* **Mencegah Error 500 saat Registrasi:** Pendaftaran dengan email ganda ditolak pada lapisan validasi, bukan menyebabkan *UniqueConstraintViolationException* di database.
* **Information Disclosure Teratasi:** Pengujian membuktikan bahwa ketika `APP_DEBUG=false`, tidak ada info sensitif (seperti stack trace, SQL query, dll.) yang dibocorkan baik pada halaman error 500 maupun 405.
* **Security Headers Lainnya:** Header `X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`, `Referrer-Policy: strict-origin-when-cross-origin`, dan `X-XSS-Protection: 1; mode=block` aktif di seluruh response aplikasi.

---

## 2. Kebijakan CSP Final & Pengamanan Aset

### A. CSP Production Policy
Kebijakan CSP yang diterapkan untuk mode produksi/pengujian adalah sebagai berikut:
```http
Content-Security-Policy: default-src 'self'; base-uri 'self'; object-src 'none'; frame-ancestors 'none'; form-action 'self'; img-src 'self' data:; font-src 'self' data:; script-src 'self'; style-src 'self' 'unsafe-inline'; connect-src 'self';
```

### B. Self-Hosting Font & Eliminasi Vite Dev
* **Font Lokal:** File woff2 untuk *Figtree* (400, 500, 600) dan *Instrument Sans* (400, 500, 600) telah dipindahkan ke `/public/fonts/` dan dimuat melalui berkas stylesheet terkompilasi `app.css`.
* **Vite Dev Server Bypass:** Di lingkungan lokal (`local`), pengiriman header CSP dilewati agar pengembang dapat menggunakan Vite dev server secara normal. Namun, untuk pemindaian keamanan atau produksi, server Vite harus dimatikan dan aset di-build.

---

## 3. Hasil Pembuatan Aset & Pengujian Unit

### A. Hasil `npm run build`
Aset berhasil dibangun untuk produksi:
```text
vite v6.4.2 building for production...
public/build/manifest.json             0.27 kB │ gzip:  0.15 kB
public/build/assets/app-Cg-8vLiN.css  93.18 kB │ gzip: 14.65 kB
public/build/assets/app-CoaHkm5D.js   88.61 kB │ gzip: 32.77 kB
✓ built in 3.86s
```

### B. Hasil `php artisan test`
Sebanyak **83 tes (272 assertions) Lolos 100%**:
```text
Tests:    83 passed (272 assertions)
Duration: 10.51s
```
Menguji aspek:
* `RegistrationTest` (Validasi input, hashing password, pencegahan manipulasi role).
* `SecurityHeadersTest` (Memastikan CSP, X-Frame-Options, X-Content-Type-Options aktif pada HTML, robots.txt, sitemap.xml, serta halaman error 404/405).
* `ErrorDisclosureTest` (Memastikan tidak ada kebocoran detail internal ketika `APP_DEBUG=false`).
* `AuthorizationTest` (Membuktikan isolasi rute antara Guest, Pasien, dan Petugas).
* `QueueTest` (Memastikan IDOR pada detail/pembatalan antrean kini diblokir dengan response 403).

---

## 4. Contoh Header Aktual Setiap Jenis Response

* **HTML Response (GET / atau GET /login):**
  ```http
  HTTP/1.1 200 OK
  Content-Type: text/html; charset=UTF-8
  Content-Security-Policy: default-src 'self'; base-uri 'self'; object-src 'none'; frame-ancestors 'none'; form-action 'self'; img-src 'self' data:; font-src 'self' data:; script-src 'self'; style-src 'self' 'unsafe-inline'; connect-src 'self';
  X-Frame-Options: DENY
  X-Content-Type-Options: nosniff
  Referrer-Policy: strict-origin-when-cross-origin
  X-XSS-Protection: 1; mode=block
  ```
* **Robots.txt (GET /robots.txt):**
  ```http
  HTTP/1.1 200 OK
  Content-Type: text/plain; charset=UTF-8
  Content-Security-Policy: default-src 'self'; base-uri 'self'; object-src 'none'; frame-ancestors 'none'; form-action 'self'; img-src 'self' data:; font-src 'self' data:; script-src 'self'; style-src 'self' 'unsafe-inline'; connect-src 'self';
  X-Frame-Options: DENY
  X-Content-Type-Options: nosniff
  ```
* **Sitemap.xml (GET /sitemap.xml):**
  ```http
  HTTP/1.1 200 OK
  Content-Type: application/xml; charset=UTF-8
  Content-Security-Policy: default-src 'self'; base-uri 'self'; object-src 'none'; frame-ancestors 'none'; form-action 'self'; img-src 'self' data:; font-src 'self' data:; script-src 'self'; style-src 'self' 'unsafe-inline'; connect-src 'self';
  X-Frame-Options: DENY
  X-Content-Type-Options: nosniff
  ```

---

## 5. Hasil Audit Khusus

### A. Audit Cookie
1. **XSRF-TOKEN:** Cookie ini tidak menggunakan flag `HttpOnly` secara sengaja. Hal ini merupakan *accepted framework behavior* dari Laravel & Axios agar skrip client-side dapat membaca nilai token untuk disematkan pada header `X-XSRF-TOKEN` saat mengirimkan request mutasi (POST/PUT/DELETE). Cookie ini aman karena dilindungi oleh perlindungan SameSite (`Lax`) dan Secure (jika berjalan di HTTPS).
2. **laravel_session:** Cookie sesi utama terbukti aman dengan konfigurasi default: `HttpOnly=true`, `SameSite=Lax`, dan `Secure=true` (ketika mendeteksi koneksi HTTPS).
3. **Session Lifecycle:** Sesi berhasil diregenerasi setelah login sukses (`session()->regenerate()`) dan di-invalidate sepenuhnya setelah logout (`session()->invalidate()`, `session()->regenerateToken()`), mencegah serangan Session Fixation.

### B. Audit Redirect (Big Redirect)
* Empat kasus redirect (GET `/pasien/dashboard` -> `/login`, POST `/forgot-password`, POST `/login`, POST `/register`) telah diperiksa dan dikonfirmasi **aman**. Redirect ini merupakan perilaku normal dari manajemen autentikasi Laravel. Response body hanya berisi HTML pengalihan minimal (< 400 bytes) dan tidak membocorkan data sensitif (password, password_confirmation, API token, dll.). Status: *False Positive / Accepted Low Risk*.

---

## 6. Daftar Route per Peran (Role Routing Table)

Aplikasi memiliki total 3 kelompok route utama:

### A. Rute Publik (Anonymous)
* `GET /` (Welcome)
* `GET /robots.txt`
* `GET /sitemap.xml`
* `GET /login` & `POST /login` (Autentikasi)
* `GET /register` & `POST /register` (Registrasi)
* `GET /forgot-password` & `POST /forgot-password` (Lupa Password)
* `GET /reset-password/{token}` & `POST /reset-password` (Atur Ulang Password)

### B. Rute Pasien (Role: `pasien`)
* `GET /pasien/dashboard` (Dashboard Pasien)
* `GET /pasien/queues` (Daftar Antrean Pasien)
* `POST /pasien/queues` (Mendaftar Antrean Mandiri)
* `GET /pasien/queues/create` (Form Pendaftaran Antrean)
* `GET /pasien/queues/{queue}` (Detail Antrean Pasien - Terproteksi IDOR)
* `DELETE /pasien/queues/{queue}` (Batal Antrean Pasien - Terproteksi IDOR)

### C. Rute Petugas (Role: `petugas`)
* `GET /petugas/dashboard` (Dashboard Petugas)
* `resource /petugas/patients` (CRUD Data Pasien oleh Petugas)
* `resource /petugas/medical-records` (CRUD Rekam Medis)
* `resource /petugas/polyclinics` (CRUD Poliklinik)
* `resource /petugas/doctors` (CRUD Dokter)
* `resource /petugas/doctor-schedules` (CRUD Jadwal Dokter)
* `resource /petugas/queues` (CRUD Antrean Pasien)

---

## 7. Langkah Melakukan Pemindaian Ulang (Scan Mode Setup)

Untuk memverifikasi hasil dengan akurat menggunakan OWASP ZAP, jalankan aplikasi pada server dengan kondisi berikut:
1. Atur `.env` lokal:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```
2. Jalankan perintah pembersihan cache Laravel:
   ```bash
   php artisan optimize:clear
   ```
3. Kompilasi aset frontend dan matikan server pengembangan Vite:
   ```bash
   npm run build
   ```
   *(Pastikan tidak ada server `npm run dev` yang berjalan).*
4. Jalankan server lokal:
   ```bash
   php artisan serve
   ```
5. Buka OWASP ZAP dan impor berkas panduan di [docs/owasp-zap-authenticated-scan.md](file:///D:/Projek%20Laravel/klinik-mitra-sehat/docs/owasp-zap-authenticated-scan.md) untuk melakukan pemindaian menyeluruh di bawah Context Petugas, Pasien, dan Anonymous.

---

## 8. Risiko Residual (Residual Risk)

* **X-Powered-By Server Header:**
  * *Status:* Di tingkat aplikasi, middleware telah membuang header ini. Namun, jika PHP dijalankan di belakang Nginx/Apache, server web atau modul PHP-FPM mungkin menyuntikkan kembali header ini.
  * *Rekomendasi:* Matikan melalui `php.ini` dengan mengatur `expose_php = Off` dan sembunyikan header di server block Nginx/Apache (tidak dapat diubah langsung dari repositori git).
* **HTTPS & HSTS (Strict-Transport-Security):**
  * *Status:* HSTS belum diaktifkan di localhost karena protokol HTTP biasa.
  * *Rekomendasi:* Aktifkan SSL/TLS pada server produksi dan paksa skema HTTPS di `AppServiceProvider` serta tambahkan header HSTS di konfigurasi server web (Nginx/Apache).
* **Cross-Site Request Forgery (CSRF) on API Routes:**
  * *Status:* Nihil (aplikasi tidak menggunakan rute API eksternal).

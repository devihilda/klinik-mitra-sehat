# Panduan Pemindaian Terautentikasi (OWASP ZAP Authenticated Scan)
## Proyek: Klinik Mitra Sehat

Dokumen ini memandu Anda dalam melakukan pemindaian keamanan terautentikasi (*Authenticated Active Scan*) menggunakan OWASP ZAP untuk mencakup seluruh area aplikasi secara menyeluruh (Anonymous, Pasien, dan Petugas).

---

## 1. Daftar Akun Pengujian (Scan Credentials)

Untuk keperluan pemindaian lokal/keamanan, jalankan seeder database:
```bash
php artisan db:seed
```
Seeder ini akan menyiapkan dua akun pengujian dengan kredensial berikut:

| Peran (Role) | Email | Password | Indikator Logged-In (Regex) |
| :--- | :--- | :--- | :--- |
| **Petugas (Officer)** | `petugas@klinik.test` | `admin12345` | `href="http://.*/logout"` atau `Petugas Klinik` |
| **Pasien (Patient)** | `pasien@klinik.test` | `pasien12345` | `href="http://.*/logout"` atau `Pasien Klinik` |

---

## 2. Struktur Endpoint & Context ZAP

Kelompokkan URL target ke dalam 3 Context utama di OWASP ZAP:

### Context 1: Anonymous / Guest (Tanpa Autentikasi)
*   **Target URL:**
    *   `/` (Welcome Page)
    *   `/login` (Form Login)
    *   `/register` (Form Registrasi)
    *   `/forgot-password` (Lupa Password)
    *   `/robots.txt`
    *   `/sitemap.xml`
*   **Metode Auth:** None (No Authentication).

### Context 2: Pasien (Patient Context)
*   **Target URL Include:**
    *   `\Qhttp://localhost:8000/pasien/\E.*` (atau sesuaikan dengan host lokal Anda)
    *   `\Qhttp://localhost:8000/profile\E.*`
*   **Target URL Exclude:**
    *   `\Qhttp://localhost:8000/logout\E`
    *   `\Qhttp://localhost:8000/petugas/\E.*`
*   **Konfigurasi Authentication:**
    *   **Method:** Form-based Auth.
    *   **Login Page URL:** `http://localhost:8000/login`
    *   **Login Request POST Data:** `email={%username%}&password={%password%}&_token={%antiCSRF%}`
    *   **Username Parameter:** `email`
    *   **Password Parameter:** `password`
*   **Users:**
    *   Username: `pasien@klinik.test`
    *   Password: `pasien12345`

### Context 3: Petugas (Officer Context)
*   **Target URL Include:**
    *   `\Qhttp://localhost:8000/petugas/\E.*`
    *   `\Qhttp://localhost:8000/profile\E.*`
*   **Target URL Exclude:**
    *   `\Qhttp://localhost:8000/logout\E`
    *   `\Qhttp://localhost:8000/pasien/\E.*`
*   **Konfigurasi Authentication:**
    *   **Method:** Form-based Auth.
    *   **Login Page URL:** `http://localhost:8000/login`
    *   **Login Request POST Data:** `email={%username%}&password={%password%}&_token={%antiCSRF%}`
    *   **Username Parameter:** `email`
    *   **Password Parameter:** `password`
*   **Users:**
    *   Username: `petugas@klinik.test`
    *   Password: `admin12345`

---

## 3. Langkah demi Langkah Konfigurasi OWASP ZAP

### Langkah 1: Rekam Sesi Login (Login Script / Request)
1. Buka OWASP ZAP.
2. Gunakan **ZAP HUD** atau browser internal ZAP untuk mengakses `http://localhost:8000/login`.
3. Lakukan proses login menggunakan salah satu akun di atas (misal `petugas@klinik.test`).
4. Pastikan ZAP menangkap request `POST /login`.

### Langkah 2: Atur Session Management
1. Masuk ke **Session Management** pada Context yang bersangkutan.
2. Pilih **Cookie-based Session Management**.
3. Pastikan cookie `laravel_session` dan `XSRF-TOKEN` terdeteksi oleh ZAP dalam daftar cookie sesi.

### Langkah 3: Konfigurasi Indikator Login
1. Buka pengaturan Context Anda -> **Authentication**.
2. Set **Logged In Indicator** menggunakan regex pattern yang menunjukkan halaman terautentikasi (contoh: `(?i)logout` atau nama pengguna seperti `Petugas Klinik`).
3. Set **Logged Out Indicator** (contoh: `(?i)login` atau `Log in`).

### Langkah 4: Tentukan Kredensial Pengguna
1. Buka Context -> **Users**.
2. Tambahkan User baru (Contoh: "User Petugas" atau "User Pasien").
3. Masukkan username (email) dan password yang sesuai.
4. Centang opsi **Enabled** untuk user tersebut.

### Langkah 5: Jalankan Forced User Mode
1. Aktifkan ikon **Forced User Mode** (ikon gembok/orang di toolbar atas ZAP) dan pilih user yang ingin disimulasikan.
2. Dengan mengaktifkan mode ini, ZAP akan otomatis mengirimkan kredensial login dan menjaga sesi tetap aktif selama merayapi (*spidering*) maupun memindai (*scanning*) endpoint terproteksi.

### Langkah 6: Jalankan Spider & Active Scan
1. Klik kanan pada target URL (misalnya folder `/petugas`) -> **Attack** -> **Spider...** (pilih Context dan User yang telah dibuat).
2. Setelah perayapan selesai, jalankan **Active Scan** menggunakan Context dan User tersebut.
3. Lakukan hal yang sama untuk Context Pasien untuk memastikan seluruh route di `/pasien/*` terpindai secara tuntas.

---

## 4. Cara Memverifikasi Keberhasilan Login ZAP
Untuk memastikan ZAP tidak keluar (logout) di tengah jalan selama proses pemindaian:
*   Lihat tab **HTTP Sessions** di ZAP. Pastikan status session untuk user Anda tetap bertanda `Active`.
*   Jika ZAP ter-logout secara tidak sengaja, ZAP akan otomatis mendeteksi ketiadaan *Logged In Indicator* pada respons halaman dan mengirim ulang request `POST /login` secara otomatis untuk membuat sesi baru.

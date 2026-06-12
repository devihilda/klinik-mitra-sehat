# Panduan Scan Ulang OWASP ZAP — Klinik Mitra Sehat

## Prasyarat

1. OWASP ZAP 2.17+ terinstal
2. Aplikasi sudah di-build production: `npm run build`
3. `APP_DEBUG=false` dan `APP_ENV=production`
4. Tidak ada server Vite dev (`npm run dev`) yang berjalan
5. File `public/hot` tidak boleh ada

---

## Langkah Persiapan Cepat

### Opsi A: Script Otomatis

**Windows:**
```powershell
powershell -File scripts/start-security-scan.ps1
```

**Linux/macOS:**
```bash
bash scripts/start-security-scan.sh
```

### Opsi B: Manual

```bash
# 1. Build assets
npm run build

# 2. Hapus file hot
rm -f public/hot       # Linux/macOS
del public\hot 2>nul   # Windows

# 3. Clear cache
php artisan optimize:clear

# 4. Jalankan server production-like
APP_DEBUG=false APP_ENV=production php artisan serve
# Windows:
# $env:APP_DEBUG="false"; $env:APP_ENV="production"; php artisan serve
```

---

## Prosedur Scan ZAP (WAJIB Session Baru)

> **PENTING:** Scan ulang WAJIB menggunakan session ZAP baru. Jangan menggunakan
> site tree atau alert dari scan sebelumnya.

### 1. Buat Session ZAP Baru

1. Buka OWASP ZAP
2. Klik **File → New Session**
3. Pilih **Yes** untuk menyimpan session lama (opsional)
4. Pastikan site tree dan alert list kosong

### 2. Konfigurasi Policy (Nonaktifkan Hanya Informational)

**Opsi A — Automation Framework (Rekomendasi):**

1. Buka tab **Automation**
2. Klik **Load Plan**
3. Pilih file `scripts/zap-scan-policy.yaml`
4. Klik **Run Plan**

**Opsi B — Manual:**

1. Buka **Analyze → Scan Policy Manager**
2. Klik **Modify** pada policy default
3. Di tab **Informational**, nonaktifkan HANYA rule berikut:
   - `10111` — Authentication Request Identified
   - `10112` — Session Management Response Identified
   - `10104` — User Agent Fuzzer
4. **JANGAN** nonaktifkan rule Medium/Low/High manapun:
   - `10038` — Content Security Policy Header Not Set (Medium) → HARUS tetap aktif
   - `90003` — Sub Resource Integrity Attribute Missing (Medium) → HARUS tetap aktif
   - `10010` — Cookie No HttpOnly Flag (Low) → HARUS tetap aktif
   - `10017` — Cross-Domain JavaScript Source File Inclusion (Low) → HARUS tetap aktif

### 3. Jalankan Spider

1. Klik kanan pada target di site tree → **Spider**
2. Masukkan URL: `http://127.0.0.1:8000`
3. Tunggu hingga selesai

### 4. Jalankan Ajax Spider

1. Klik kanan pada target → **AJAX Spider**
2. Browser: Firefox/Chrome
3. Tunggu hingga selesai

### 5. Jalankan Active Scan

1. Klik kanan pada target → **Active Scan**
2. Pastikan menggunakan policy yang sudah dikonfigurasi
3. Tunggu hingga selesai

### 6. Buat Report

1. Klik **Report → Generate Report**
2. Pilih format HTML atau JSON
3. Simpan report

---

## Expected Result

| Severity      | Count |
|---------------|-------|
| High          | 0     |
| Medium        | 0     |
| Low           | 0     |
| Informational | 0     |

### Mengapa 0?

| Finding Sebelumnya | Perbaikan |
|---|---|
| CSP Header Not Set (10038) | Middleware `SecurityHeaders` mengirim CSP pada **semua** response, termasuk 404/405 |
| SRI Attribute Missing (90003) | `vite-plugin-manifest-sri` menghasilkan hash integrity di `manifest.json`; `@vite` directive otomatis merender atribut `integrity` |
| Cookie No HttpOnly (10010) | Cookie `XSRF-TOKEN` dinonaktifkan via `withXsrfCookie(false)`; CSRF menggunakan meta tag + `X-CSRF-TOKEN` header |
| Cross-Domain JS (10017) | Semua asset disajikan dari origin sendiri (`/build/assets/`); tidak ada resource dari `fonts.bunny.net` atau port `5173` |
| Auth Request Identified (10111) | Dinonaktifkan di ZAP config (informational, bukan vulnerability) |
| Session Mgmt Response (10112) | Dinonaktifkan di ZAP config (informational, bukan vulnerability) |
| User Agent Fuzzer (10104) | Dinonaktifkan di ZAP config (informational, bukan vulnerability) |

---

## Verifikasi Otomatis

Setelah server berjalan, jalankan script verifikasi:

**Windows:**
```powershell
powershell -File scripts/verify-zap-zero.ps1
```

**Linux/macOS:**
```bash
bash scripts/verify-zap-zero.sh
```

Script ini memeriksa 8 endpoint dan akan gagal jika ditemukan:
- CSP header tidak ada
- Referensi port 5173
- Cookie XSRF-TOKEN
- Header X-Powered-By
- Stack trace atau SQLSTATE

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Mitra Sehat - Dashboard Petugas</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f8fafc;
            color: #1e293b;
        }
        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h2 {
            color: #2563eb;
            font-size: 1.25rem;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .logout-btn {
            color: #ef4444;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
        }
        .container {
            max-width: 800px;
            margin: 3rem auto;
            padding: 0 1.5rem;
        }
        .card {
            background: #ffffff;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .welcome-text {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .role-badge {
            display: inline-block;
            background-color: #fef3c7;
            color: #92400e;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .alur-list {
            margin-top: 2rem;
            border-top: 1px solid #f1f5f9;
            padding-top: 1.5rem;
        }
        .alur-list h3 {
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }
        .alur-list ul {
            list-style-type: none;
            padding-left: 0;
        }
        .alur-list li {
            position: relative;
            padding-left: 1.5rem;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }
        .alur-list li::before {
            content: "⚙";
            position: absolute;
            left: 0;
            color: #d97706;
            font-weight: bold;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <h2>Klinik Mitra Sehat</h2>
    <div class="user-info">
        <span>Halo, <strong>{{ session('name') }}</strong> ({{ session('username') }})</span>
        <a href="{{ route('logout') }}" class="logout-btn">Keluar</a>
    </div>
</nav>

<div class="container">
    <div class="card">
        <span class="role-badge">Petugas</span>
        <h1 class="welcome-text">Selamat Datang di Portal Petugas</h1>
        <p>Anda berhasil login ke sistem manajemen simulasi Klinik Mitra Sehat.</p>

        <div class="alur-list">
            <h3>Menu Pengelolaan Petugas yang Akan Dibuat:</h3>
            <ul>
                <li><strong>Mengelola data pasien</strong> - Ubah & hapus data pasien</li>
                <li><strong>Mencari data pasien</strong> - Cari data terdaftar</li>
                <li><strong>Mengelola poli</strong> - Tambah, ubah, & hapus poli</li>
                <li><strong>Mengelola jadwal dokter</strong> - Tambah, ubah, & hapus jadwal</li>
                <li><strong>Melihat daftar pasien</strong> - Daftar pasien pengambil antrian</li>
            </ul>
        </div>
    </div>
</div>

</body>
</html>

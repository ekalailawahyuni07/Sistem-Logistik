<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard User</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

<div class="sidebar">
    <div class="logo">
    <img src="{{ asset('images/logo-tkm.png') }}" alt="Logo PT">
</div>

    <div class="profile">
        <div class="avatar">👤</div>
        <h4>{{ Auth::user()->nama_user }}</h4>
        <p>{{ Auth::user()->email }}</p>
    </div>

    <div class="menu">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('data.material') }}">Data Material</a>
        <a href="{{ route('material.masuk') }}">Material Masuk</a>
        <a href="{{ route('material.keluar') }}">Material Keluar</a>
        <a href="{{ route('stok.material') }}">Stok Material</a>
        <a href="{{ route('cluster') }}">Cluster</a>
        <a href="{{ route('surat.jalan') }}">Surat Jalan</a>
    </div>

    <!-- Tambahkan ini -->
    <div class="logout">
        <form method="POST" action="{{ route('logout') }}" onsubmit="return konfirmasiLogout()">
            @csrf
            <button type="submit" class="logout-btn">🚪 Logout</button>
        </form>
    </div>

</div>

<div class="content">
    <div class="topbar">
        <h1>🏠 Dashboard</h1>

        <input type="text" placeholder="🔍 Cari material / Surat Jalan...">

        <h2>👤 Hello, {{ Auth::user()->nama_user }}! (Petugas)</h2>
    </div>

    <div class="alert">
        ⚠ Belum ada notifikasi
    </div>

    <div class="card">
        <h2>Grafik Material Masuk dan Keluar <span>(Bulan Ini)</span></h2>

        <div class="empty-box">
            Belum ada data grafik
        </div>
    </div>

    <div class="card">
        <h2>Monitoring Material Berdasarkan Area</h2>
        <h3>Area: -</h3>

        <div class="stats">
            <div class="stat blue">
                <b>{{ $totalKeluar }}</b>
                <p>Material Keluar</p>
            </div>

            <div class="stat orange">
                <b>{{ $totalMasuk }}</b>
                <p>Material Masuk</p>
            </div>

            <div class="stat blue">
                <b>{{ $totalStock }}</b>
                <p>Total Stock Material</p>
            </div>
        </div>
    </div>
</div>

<script>
function konfirmasiLogout() {
    return confirm("Apakah Anda yakin ingin logout?");
}
</script>

</body>
</html>
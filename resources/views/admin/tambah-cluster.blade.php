<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Cluster Admin</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

<div class="sidebar">

    <div class="logo">
        <img src="{{ asset('images/logo-tkm.png') }}" alt="Logo PT">
    </div>

    <a href="{{ route('admin.profile.edit') }}"class="profile-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
        <div class="profile">

            @if(Auth::user()->foto_profile)
                <img src="{{ asset('storage/' . Auth::user()->foto_profile) }}" class="profile-img">
            @else
                <div class="avatar">👤</div>
            @endif

            <h4>{{ Auth::user()->nama_user }}</h4>
            <p>{{ Auth::user()->email }}</p>

        </div>
    </a>

    <div class="menu">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.verifikasi.user') }}">Verifikasi User</a>
        <a href="{{ route('admin.kelola.area') }}">Kelola Area & Kluster</a>
        <a href="{{ route('admin.cluster') }}" class="active">Cluster</a>
        <a href="{{ route('admin.data.material') }}">Master Data Material</a>
        <a href="{{ route('admin.material.masuk') }}">Material Masuk</a>
        <a href="{{ route('admin.material.keluar') }}">Material Keluar</a>
        <a href="{{ route('admin.stok.material') }}">Stok Material</a>
        <a href="{{ route('admin.dokumen') }}">Dokumen</a>
        <a href="{{ route('admin.surat.jalan') }}">Surat Jalan</a>
        <a href="{{ route('admin.notifikasi') }}">Log Keluar Masuk</a>
    </div>

    <div class="logout">
        <form method="POST" action="{{ route('logout') }}" onsubmit="return konfirmasiLogout()">
            @csrf
            <button type="submit" class="logout-btn">
                Keluar
            </button>
        </form>
    </div>

</div>

<div class="content">

    <div class="topbar">
        <h1>🏢 Tambah Cluster</h1>
        <input type="text" placeholder="🔍 Cari cluster...">
        <h2>👤 Hello, {{ Auth::user()->nama_user }} (Admin)</h2>
    </div>

    <div class="form-card">

        <div class="form-header">
            <div>
                <h1>Tambah Cluster</h1>
                <p>Silakan isi data cluster baru.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.cluster.store') }}">
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label>Kode Cluster</label>
                    <input
                        type="text"
                        name="kode_cluster"
                        placeholder="Contoh : CL001"
                        required>
                </div>

                <div class="form-group">
                    <label>Nama Cluster</label>
                    <input
                        type="text"
                        name="nama_cluster"
                        placeholder="Masukkan nama cluster"
                        required>
                </div>

            </div>

            <div class="form-actions">

                <a href="{{ route('admin.cluster') }}" class="btn-kembali">
                    ← Kembali
                </a>

                <div>
                    <button type="reset" class="btn-reset">
                        Reset
                    </button>

                    <button type="submit" class="btn-update">
                        💾 Simpan
                    </button>
                </div>

            </div>

        </form>

    </div>

</div>

<script>
function konfirmasiLogout(){
    return confirm("Apakah Anda yakin ingin Keluar?");
}
</script>

</body>
</html>
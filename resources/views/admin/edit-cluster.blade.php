<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Cluster</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

<div class="sidebar">

    <div class="logo">
        <img src="{{ asset('images/logo-tkm.png') }}" alt="Logo PT">
    </div>

    <a href="{{ route('admin.profile.edit') }}"
       class="profile-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">

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
        <a href="{{ route('admin.cluster') }}" class="active">Daftar Kluster</a>
        <a href="{{ route('admin.data.material') }}">Master Data Material</a>
        <a href="{{ route('admin.material.masuk') }}">Material Masuk</a>
        <a href="{{ route('admin.material.keluar') }}">Material Keluar</a>
        <a href="{{ route('admin.stok.material') }}">Stok Material</a>
        <a href="{{ route('admin.dokumen') }}">Dokumen</a>
        <a href="{{ route('admin.surat.jalan') }}">Surat Jalan</a>
        <a href="{{ route('admin.notifikasi') }}">Log Keluar Masuk</a>
    </div>

    <div class="logout">
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button class="logout-btn">
                Keluar
            </button>

        </form>
    </div>

</div>


<div class="content">

    <div class="form-card">

        <div class="form-header">
            <div>
                <h1>Edit Kluster</h1>
                <p>Silahkan ubah data Kluster</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.cluster.update', $cluster->id_cluster) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group">
                    <label>ID Kluster</label>
                    <input
                        type="text"
                        name="kode_cluster"
                        value="{{ $cluster->kode_cluster }}"
                        required>
                </div>

                <div class="form-group">
                    <label>Nama Kluster</label>
                    <input
                        type="text"
                        name="nama_cluster"
                        value="{{ $cluster->nama_cluster }}"
                        required>
                </div>

            </div>

            <div class="form-footer">

                <a href="{{ route('admin.cluster') }}" class="btn-kembali">
                    ← Kembali
                </a>

                <button type="submit" class="btn-update">
                    ✓ Update
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>
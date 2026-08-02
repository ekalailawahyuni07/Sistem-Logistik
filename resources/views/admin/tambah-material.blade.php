<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Material Admin</title>
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
                <img src="{{ asset('storage/'.Auth::user()->foto_profile) }}" class="profile-img">
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
        <a href="{{ route('admin.cluster') }}">Daftar Kluster</a>
        <a href="{{ route('admin.data.material') }}" class="active">Master Data Material</a>
        <a href="{{ route('admin.material.masuk') }}">Material Masuk</a>
        <a href="{{ route('admin.material.keluar') }}">Material Keluar</a>
        <a href="{{ route('admin.stok.material') }}">Stok Material</a>
        <a href="{{ route('admin.dokumen') }}">Dokumen</a>
        <a href="{{ route('admin.surat.jalan') }}">Surat Jalan</a>
        <a href="{{ route('admin.notifikasi') }}">Log Keluar Masuk</a>
    </div>

    <div class="logout">
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault();document.getElementById('logoutForm').submit();"
           class="logout-btn">
            Keluar
        </a>

        <form id="logoutForm"
              action="{{ route('logout') }}"
              method="POST"
              style="display:none;">
            @csrf
        </form>
    </div>

</div>

<div class="content">

    <div class="form-card">

        <div class="form-header">
            <div>
                <h1>Tambah Material</h1>
                <p>Lengkapi data material baru sebelum menyimpannya ke dalam sistem</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.data.material.store') }}">
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label>Kode Material <span style="color:red;">*</span></label>
                    <input type="text" name="kode_material" placeholder="Masukkan kode material" required>
                </div>

                <div class="form-group">
                    <label>Nama Material <span style="color:red;">*</span></label>
                    <input type="text" name="nama_material" placeholder="Masukkan nama material" required>
                </div>

                <div class="form-group">
                    <label>Project <span style="color:red;">*</span></label>
                    <input type="text" name="project" placeholder="Masukkan nama project" required>
                </div>

                <div class="form-group">
                    <label>Jenis Material <span style="color:red;">*</span></label>
                    <input type="text" name="jenis_material" placeholder="Masukkan jenis material" required>
                </div>

                <div class="form-group">
                    <label>Satuan <span style="color:red;">*</span></label>
                    <input type="text" name="satuan" placeholder="Contoh : Batang" required>
                </div>

                <div class="form-group full">
                    <label>Keterangan</label>
                    <textarea name="keterangan" rows="5" placeholder="Masukkan keterangan..."></textarea>
                </div>

            </div>

            <div class="form-footer">
                <a href="{{ route('admin.data.material') }}" class="btn-kembali">
                    ← Kembali
                </a>

                <button type="submit" class="btn-simpan">
                    💾 Simpan
                </button>
            </div>

        </form>

    </div>

</div>

</body>
</html>
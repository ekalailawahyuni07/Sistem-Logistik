<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Material Admin</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

<div class="sidebar">
    <div class="logo">
        <img src="{{ asset('images/logo-tkm.png') }}" alt="Logo PT">
    </div>

    <a href="{{ route('profile.edit') }}" class="profile-link">
        <div class="profile">
            @if(Auth::user()->foto_profile)
                <img src="{{ asset('storage/' . Auth::user()->foto_profile) }}" class="profile-img">
            @else
                <div class="avatar">👤</div>
            @endif

            <h4>{{ Auth::user()->nama_user }}</h4>
            <p>{{ Auth::user()->email }}</p>
            <small style="color:#FFD54F;font-weight:bold;">Administrator</small>
        </div>
    </a>

    <div class="menu">
        <a href="{{ route('admin.dashboard') }}">🏠 Dashboard</a>
        <a href="{{ route('admin.verifikasi.user') }}">👥 Verifikasi User</a>
        <a href="{{ route('admin.kelola.area') }}">🌍 Kelola Area</a>
        <a href="{{ route('admin.data.material') }}" class="active">📦 Data Material</a>
        <a href="{{ route('admin.material.masuk') }}">📥 Material Masuk</a>
        <a href="{{ route('admin.material.keluar') }}">📤 Material Keluar</a>
        <a href="{{ route('admin.stok.material') }}">📊 Stok Material</a>
        <a href="{{ route('admin.cluster') }}">🏢 Cluster</a>
        <a href="{{ route('admin.dokumen') }}">📁 Dokumen</a>
        <a href="{{ route('admin.surat.jalan') }}">🚚 Surat Jalan</a>
        <a href="{{ route('admin.notifikasi') }}">🔔 Notifikasi</a>
    </div>

    <div class="logout">
        <form method="POST" action="{{ route('logout') }}" onsubmit="return konfirmasiLogout()">
            @csrf
            <button type="submit" class="logout-btn">🚪 Logout</button>
        </form>
    </div>
</div>

<div class="content">
    <div class="topbar">
        <h1>✏️ Edit Material</h1>
        <input type="text" placeholder="🔍 Cari material...">
        <h2>👤 Hello, {{ Auth::user()->nama_user }} (Administrator)</h2>
    </div>

    <div class="form-card">
        <div class="form-header">
            <div class="form-icon">✏️</div>
            <div>
                <h2>Edit Material Admin</h2>
                <p>Perbarui informasi material pada form di bawah ini.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.data.material.update', $material->id_material) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label>Kode Material</label>
                    <input type="text" name="kode_material" value="{{ old('kode_material', $material->kode_material) }}" required>
                </div>

                <div class="form-group">
                    <label>Nama Material</label>
                    <input type="text" name="nama_material" value="{{ old('nama_material', $material->nama_material) }}" required>
                </div>

                <div class="form-group">
                    <label>Jenis Material</label>
                    <input type="text" name="jenis_material" value="{{ old('jenis_material', $material->jenis_material) }}" required>
                </div>

                <div class="form-group">
                    <label>Satuan</label>
                    <input type="text" name="satuan" value="{{ old('satuan', $material->satuan) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" rows="5">{{ old('keterangan', $material->keterangan) }}</textarea>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.data.material') }}" class="btn-kembali">← Kembali</a>

                <div>
                    <button type="reset" class="btn-reset">Reset</button>
                    <button type="submit" class="btn-update">✓ Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function konfirmasiLogout() {
    return confirm("Apakah Anda yakin ingin logout?");
}
</script>

</body>
</html>
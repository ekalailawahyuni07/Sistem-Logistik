<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Material Masuk Admin</title>
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
        </div>
    </a>

    <div class="menu">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.verifikasi.user') }}">Verifikasi User</a>
        <a href="{{ route('admin.kelola.area') }}">Kelola Area</a>
        <a href="{{ route('admin.data.material') }}">Data Material</a>
        <a href="{{ route('admin.material.masuk') }}" class="active">Material Masuk</a>
        <a href="{{ route('admin.material.keluar') }}">Material Keluar</a>
        <a href="{{ route('admin.stok.material') }}">Stok Material</a>
        <a href="{{ route('admin.cluster') }}">Cluster</a>
        <a href="{{ route('admin.dokumen') }}">Dokumen</a>
        <a href="{{ route('admin.surat.jalan') }}">Surat Jalan</a>
        <a href="{{ route('admin.notifikasi') }}">Notifikasi</a>
    </div>

    <div class="logout">
        <form method="POST" action="{{ route('logout') }}" onsubmit="return konfirmasiLogout()">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</div>

<div class="content">
    <div class="topbar">
        <h1>Edit Material Masuk</h1>
        <input type="text" placeholder="🔍 Cari material masuk...">
        <h2>👤 Hello, {{ Auth::user()->nama_user }} (Admin)</h2>
    </div>

    <div class="form-card">
        <div class="form-header">
            <div class="form-icon">✏️</div>
            <div>
                <h2>Edit Material Masuk Admin</h2>
                <p>Perbarui data material masuk pada form di bawah ini.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.material.masuk.update', $transaksi->id_transaksi) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $transaksi->tgl_transaksi }}" required>
                </div>

                <div class="form-group">
                    <label>No Bukti / Surat Jalan</label>
                    <input type="text" name="no_bukti" value="{{ $transaksi->no_bukti }}" required>
                </div>

                <div class="form-group">
                    <label>Material</label>
                    <select name="id_material" class="select-material" required onchange="isiSatuan(this)">
                        <option value="">-- Pilih Material --</option>

                        @foreach($materials as $material)
                            <option
                                value="{{ $material->id_material }}"
                                data-satuan="{{ $material->satuan }}"
                                {{ $transaksi->id_material == $material->id_material ? 'selected' : '' }}>
                                [{{ $material->kode_material }}] {{ $material->nama_material }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" name="jumlah" min="1" value="{{ $transaksi->jumlah }}" required>
                </div>

                <div class="form-group">
                    <label>Satuan</label>
                    <input type="text" class="satuan-field" value="{{ $transaksi->material->satuan ?? '-' }}" readonly>
                </div>

                <div class="form-group">
                    <label>Keterangan</label>
                    <input type="text" name="keterangan" value="{{ $transaksi->keterangan }}" placeholder="Opsional">
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.material.masuk') }}" class="btn-kembali">← Kembali</a>

                <div>
                    <button type="reset" class="btn-reset">Reset</button>
                    <button type="submit" class="btn-update">✓ Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function isiSatuan(select) {
    let selected = select.options[select.selectedIndex];
    let satuan = selected.getAttribute('data-satuan') || '';

    let card = select.closest('.form-grid');
    card.querySelector('.satuan-field').value = satuan;
}

function konfirmasiLogout() {
    return confirm("Apakah Anda yakin ingin logout?");
}
</script>

</body>
</html>
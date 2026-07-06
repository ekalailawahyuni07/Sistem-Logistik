<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Material Masuk Admin</title>
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
        <h1>Material Masuk</h1>
        <input type="text" placeholder="🔍 Cari material masuk...">
        <h2>👤 Hello, {{ Auth::user()->nama_user }} (Admin)</h2>
    </div>

    <div class="card">
        <div class="card-header-material">
            <h2>Material Masuk Area Pontianak</h2>

            <a href="{{ route('admin.material.masuk.create') }}" class="btn-tambah">
                + Tambah Material Masuk
            </a>
        </div>

        <table class="material-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>No Bukti / Surat Jalan</th>
                    <th>Kode Material</th>
                    <th>Nama Material</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($masuk as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d-m-Y') }}</td>
                        <td>{{ $item->no_bukti }}</td>
                        <td>{{ $item->material->kode_material ?? '-' }}</td>
                        <td>{{ $item->material->nama_material ?? '-' }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>{{ $item->material->satuan ?? '-' }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td style="display:flex; gap:6px; justify-content:center;">
                            <a href="{{ route('admin.material.masuk.edit', $item->id_transaksi) }}" class="btn-edit">
                                Edit
                            </a>

                            <form method="POST"
                                action="{{ route('admin.material.masuk.destroy', $item->id_transaksi) }}"
                                class="form-hapus">
                                @csrf
                                @method('DELETE')

                                <button type="button" class="btn-hapus" onclick="bukaModalHapus(this)">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center;">
                            Belum ada data material masuk
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function konfirmasiLogout() {
    return confirm("Apakah Anda yakin ingin logout?");
}
</script>

<div id="modalHapus" class="modal-hapus">
    <div class="modal-box">
        <div class="modal-icon">⚠️</div>
        <h2>Konfirmasi Hapus</h2>
        <p>Data material masuk ini akan dihapus permanen. Yakin ingin melanjutkan?</p>

        <div class="modal-actions">
            <button type="button" class="btn-batal-modal" onclick="tutupModalHapus()">
                Batal
            </button>

            <button type="button" class="btn-konfirmasi-modal" onclick="submitHapus()">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>
<script>
let formHapusAktif = null;

function bukaModalHapus(button) {
    formHapusAktif = button.closest('form');
    document.getElementById('modalHapus').style.display = 'flex';
}

function tutupModalHapus() {
    formHapusAktif = null;
    document.getElementById('modalHapus').style.display = 'none';
}

function submitHapus() {
    if (formHapusAktif) {
        formHapusAktif.submit();
    }
}
</script>

</body>
</html>
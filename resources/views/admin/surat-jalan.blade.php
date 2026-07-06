<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Jalan Admin</title>
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
        <a href="{{ route('admin.data.material') }}">📦 Data Material</a>
        <a href="{{ route('admin.material.masuk') }}">📥 Material Masuk</a>
        <a href="{{ route('admin.material.keluar') }}">📤 Material Keluar</a>
        <a href="{{ route('admin.stok.material') }}">📊 Stok Material</a>
        <a href="{{ route('admin.cluster') }}">🏢 Cluster</a>
        <a href="{{ route('admin.dokumen') }}">📁 Dokumen</a>
        <a href="{{ route('admin.surat.jalan') }}" class="active">🚚 Surat Jalan</a>
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
        <h1>🚚 Surat Jalan</h1>
        <input type="text" id="searchSuratJalan" placeholder="🔍 Cari surat jalan..." onkeyup="cariSuratJalan()">
        <h2>👤 Hello, {{ Auth::user()->nama_user }} (Administrator)</h2>
    </div>

    <div class="card">
        <h2>Daftar Surat Jalan</h2>

        <table class="material-table" id="tabelSuratJalan">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>No Surat Jalan</th>
                    <th>Project</th>
                    <th>Cluster</th>
                    <th>Penerima</th>
                    <th>Material</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($suratJalan as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->tgl_transaksi }}</td>
                        <td>{{ $item->no_bukti }}</td>
                        <td>{{ $item->project ?? '-' }}</td>
                        <td>{{ $item->cluster->nama_cluster ?? '-' }}</td>
                        <td>{{ $item->nama_penerima ?? '-' }}</td>
                        <td>{{ $item->material->nama_material ?? '-' }}</td>
                        <td>{{ $item->jumlah }} {{ $item->material->satuan ?? '' }}</td>
                        <td>
                            <a href="{{ route('admin.surat.jalan.show', $item->id_transaksi) }}" class="btn-view">
                                Lihat
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center;">
                            Belum ada data surat jalan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function cariSuratJalan() {
    let input = document.getElementById("searchSuratJalan").value.toLowerCase();
    let rows = document.querySelectorAll("#tabelSuratJalan tbody tr");

    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(input) ? "" : "none";
    });
}

function konfirmasiLogout() {
    return confirm("Apakah Anda yakin ingin logout?");
}
</script>

</body>
</html>
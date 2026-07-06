<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Jalan</title>
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
        <a href="{{ route('dokumen') }}">Dokumen</a>
        <a href="{{ route('surat.jalan') }}" class="active">Surat Jalan</a>
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
        <h2>👤 Hello, {{ Auth::user()->nama_user }}! (Petugas)</h2>
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
                            <a href="{{ route('surat.jalan.show', $item->id_transaksi) }}" class="btn-view">
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
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dokumen</title>
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
        <a href="{{ route('dokumen') }}" class="active">Dokumen</a>
        <a href="{{ route('surat.jalan') }}">Surat Jalan</a>
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
        <h1>📄 Dokumen</h1>
        <input type="text" id="searchDokumen" placeholder="🔍 Cari dokumen..." onkeyup="cariDokumen()">
        <h2>👤 Hello, {{ Auth::user()->nama_user }}! (Petugas)</h2>
    </div>

    <div class="card">
        <h2>Daftar Dokumen & Dokumentasi</h2>

        <table class="material-table" id="tabelDokumen">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis</th>
                    <th>Nama File</th>
                    <th>Tanggal Upload</th>
                    <th>Tanggal Transaksi</th>
                    <th>No Bukti</th>
                    <th>Material</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($dokumen as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>Dokumen</td>
                        <td>{{ basename($item->file_dokumentasi) }}</td>
                        <td>{{ $item->tgl_upload ?? '-' }}</td>
                        <td>{{ $item->transaksiMaterial->tgl_transaksi ?? '-' }}</td>
                        <td>{{ $item->transaksiMaterial->no_bukti ?? '-' }}</td>
                        <td>{{ $item->transaksiMaterial->material->nama_material ?? '-' }}</td>
                        <td>{{ $item->keterangan ?? $item->transaksiMaterial->keterangan ?? '-' }}</td>
                        <td style="display:flex; gap:8px; justify-content:center;">

                            <a href="{{ asset('storage/' . $item->file_dokumentasi) }}"
                            target="_blank"
                            class="btn-view">
                                👁 Lihat
                            </a>

                            <a href="{{ asset('storage/' . $item->file_dokumentasi) }}"
                            download
                            class="btn-download">
                                ⬇ Download
                            </a>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center;">
                            Belum ada dokumen yang diupload
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function cariDokumen() {
    let input = document.getElementById("searchDokumen").value.toLowerCase();
    let rows = document.querySelectorAll("#tabelDokumen tbody tr");

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
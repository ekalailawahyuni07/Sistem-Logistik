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

    <a href="{{ route('profile.edit') }}" style="text-decoration:none; color:inherit;">
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
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('data.material') }}">Master Data Material</a>
        <a href="{{ route('material.masuk') }}">Material Masuk</a>
        <a href="{{ route('material.keluar') }}">Material Keluar</a>
        <a href="{{ route('stok.material') }}">Stok Material</a>
        <a href="{{ route('cluster') }}">Daftar Kluster</a>
        <a href="{{ route('dokumen') }}" class="active">Daftar Dokumen</a>
        <a href="{{ route('surat.jalan') }}">Surat Jalan</a>
    </div>

    <div class="logout">
        <form method="POST"
            action="{{ route('logout') }}"
            id="logoutForm">
            @csrf

            <button
                type="button"
                class="logout-btn"
                onclick="bukaModalLogout()"
            >
                Keluar
            </button>
        </form>
    </div>
</div>

<div class="content">
    <div class="topbar">
        <h1>Daftar Dokumen</h1>
        <h2>👤 Halo, {{ Auth::user()->nama_user }}! (Petugas)</h2>
    </div>

    <div class="card">
        <div class="material-card-header">
            <h2>Daftar Dokumen & Dokumentasi Area {{ Auth::user()->area->nama_area ?? '' }}</h2>

            <div class="material-toolbar" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                <input
                    type="text"
                    id="searchDokumen"
                    class="material-search"
                    placeholder="🔍 Cari dokumen..."
                    onkeyup="cariDokumen()"
                >
                <select
                    id="filterJenis"
                    class="filter-area"
                    onchange="cariDokumen()"
                    style="color:#000 !important;">
                    <option value="">Semua Jenis</option>
                    <option value="masuk">Material Masuk</option>
                    <option value="keluar">Material Keluar</option>
                </select>
            </div>
        </div>

        <table class="material-table" id="tabelDokumen">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Transaksi</th>
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
                        <td>
                            @php
                                $jenis = $item->transaksiMaterial->jenis_transaksi ?? null;
                            @endphp
                            @if($jenis == 'masuk')
                                <span class="badge-masuk">▲ Material Masuk</span>
                            @elseif($jenis == 'keluar')
                                <span class="badge-keluar">▼ Material Keluar</span>
                            @else
                                <span style="color:#888;">-</span>
                            @endif
                        </td>
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

<div id="modalLogout" class="modal-hapus">

    <div class="modal-box">

        <div class="modal-icon logout-icon">
            🚪
        </div>

        <h2>Keluar</h2>

        <p>
            Apakah Anda yakin ingin keluar dari sistem?
        </p>

        <div class="modal-warning-text">
            Anda harus masuk kembali untuk mengakses sistem.
        </div>

        <div class="modal-actions">

            <button
                type="button"
                class="btn-batal-modal"
                onclick="tutupModalLogout()"
            >
                Batal
            </button>

            <button
                type="button"
                class="btn-logout-modal"
                onclick="submitLogout()"
            >
                Ya, Keluar
            </button>

        </div>

    </div>

</div>

<script>
function cariDokumen() {
    let keyword = document.getElementById("searchDokumen").value.toLowerCase();
    let filterJenis = document.getElementById("filterJenis") ? document.getElementById("filterJenis").value : "";
    let rows = document.querySelectorAll("#tabelDokumen tbody tr");

    rows.forEach(function(row) {
        if (row.querySelector("td[colspan]")) return;

        let text = row.innerText.toLowerCase();
        let jenisTd = row.children[1];
        let jenisText = jenisTd ? jenisTd.innerText.toLowerCase() : "";

        let matchesKeyword = text.includes(keyword);
        let matchesJenis = !filterJenis ||
            (filterJenis === 'masuk' && jenisText.includes('masuk')) ||
            (filterJenis === 'keluar' && jenisText.includes('keluar'));

        row.style.display = (matchesKeyword && matchesJenis) ? "" : "none";
    });
}

function bukaModalLogout() {

    document.getElementById("modalLogout").style.display="flex";

}

function tutupModalLogout() {

    document.getElementById("modalLogout").style.display="none";

}

function submitLogout(){

    document.getElementById("logoutForm").submit();

}

window.onclick=function(event){

    let modal=document.getElementById("modalLogout");

    if(event.target==modal){

        tutupModalLogout();

    }

}
</script>

</body>
</html>
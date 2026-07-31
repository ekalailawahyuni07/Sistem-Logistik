<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dokumen Admin</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        html { height: 100%; }
        body { height: 100vh; overflow: hidden; }
        .sidebar { overflow-y: auto; height: 100vh; }
        .dokumen-page {
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            padding: 18px 28px;
        }
        .dokumen-page .topbar { flex-shrink: 0; margin-bottom: 12px; }
        .dokumen-page .card {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            padding: 14px 16px;
        }
        .dokumen-page .card-header { flex-shrink: 0; margin-bottom: 10px; }
        .dokumen-areas-container {
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 14px;
            padding-right: 4px;
        }
        .area-section {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }
        .stok-table-wrapper, .table-responsive {
            max-height: 250px !important;
            overflow-y: auto !important;
            overflow-x: auto !important;
        }
        .stok-table-wrapper::-webkit-scrollbar, .table-responsive::-webkit-scrollbar { width: 5px; height: 5px; }
        .stok-table-wrapper::-webkit-scrollbar-track, .table-responsive::-webkit-scrollbar-track { background: #f1f5f9; }
        .stok-table-wrapper::-webkit-scrollbar-thumb, .table-responsive::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 3px; }
        .material-table thead th {
            position: sticky !important;
            top: 0 !important;
            background: #1a3a6e !important;
            color: #fff !important;
            z-index: 10 !important;
            padding: 8px 10px;
            text-align: center;
            font-weight: 600;
            white-space: nowrap;
        }
        .filter-area {
            height: 38px !important;
            padding: 0 34px 0 12px !important;
            border: 1.5px solid #cdd5e0 !important;
            border-radius: 8px !important;
            font-size: 13px !important;
            background-color: #ffffff !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%231a237e' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 10px center !important;
            background-size: 14px 14px !important;
            color: #2d3748 !important;
            outline: none !important;
            cursor: pointer !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            box-sizing: border-box !important;
        }
    </style>
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
        <a href="{{ route('admin.cluster') }}">Daftar Kluster</a>
        <a href="{{ route('admin.data.material') }}">Master Data Material</a>
        <a href="{{ route('admin.material.masuk') }}">Material Masuk</a>
        <a href="{{ route('admin.material.keluar') }}">Material Keluar</a>
        <a href="{{ route('admin.stok.material') }}">Stok Material</a>
        <a href="{{ route('admin.dokumen') }}" class="active">Dokumen</a>
        <a href="{{ route('admin.surat.jalan') }}">Surat Jalan</a>
        <a href="{{ route('admin.notifikasi') }}">Log Keluar Masuk</a>
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

<div class="content dokumen-page">
    <div class="topbar">
        <h1>Dokumen</h1>
        <h2>👤 Halo, {{ Auth::user()->nama_user }} (Admin)</h2>
    </div>

    <div class="card">

        <div class="card-header">

            <h2>Daftar Dokumen Berdasarkan Area</h2>

            <div class="stok-toolbar" style="gap:10px;">

                <input
                    type="text"
                    id="searchDokumen"
                    placeholder="🔍 Cari Dokumen..."
                    onkeyup="cariDokumen()">

                <select
                    id="filterJenis"
                    class="filter-area"
                    onchange="cariDokumen()"
                    style="color:#000 !important;">
                    <option value="">Semua Jenis</option>
                    <option value="masuk">Material Masuk</option>
                    <option value="keluar">Material Keluar</option>
                </select>

                <form method="GET" action="{{ route('admin.dokumen') }}" style="margin:0;">

                    <select
                        name="id_area"
                        class="filter-area"
                        onchange="this.form.submit()"
                        style="color:#000 !important;">

                        <option value="">Semua Area</option>

                        @foreach($allAreas as $item)

                            <option
                                value="{{ $item->id_area }}"
                                {{ request('id_area') == $item->id_area ? 'selected' : '' }}>

                                {{ $item->nama_area }}

                            </option>

                        @endforeach

                    </select>

                </form>

            </div>

        </div>


        <div class="dokumen-areas-container">
        @foreach($areas as $area)

        <div class="area-section">

            <div class="area-header">

            <h3>📍 {{ $area->nama_area }}</h3>

            <span>
                Total Dokumen : {{ $area->dokumen->count() }}
            </span>

        </div>

            <div class="stok-table-wrapper">

                <div class="table-responsive">

                <table class="material-table tabelDokumen">

                    <thead>

                        <tr>

                            <th>No</th>
                            <th>Jenis Transaksi</th>
                            <th>Nama File</th>
                            <th>Tgl Upload</th>
                            <th>Tgl Transaksi</th>
                            <th>No Bukti</th>
                            <th>Material</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($area->dokumen as $item)

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

                            <td>
                                {{ basename($item->file_dokumentasi) }}
                            </td>

                            <td>
                                {{ $item->tgl_upload ?? '-' }}
                            </td>

                            <td>
                                {{ $item->transaksiMaterial->tgl_transaksi ?? '-' }}
                            </td>

                            <td>
                                {{ $item->transaksiMaterial->no_bukti ?? '-' }}
                            </td>

                            <td>
                                {{ $item->transaksiMaterial->material->nama_material ?? '-' }}
                            </td>

                            <td>
                                {{ $item->keterangan ?? $item->transaksiMaterial->keterangan ?? '-' }}
                            </td>

                            <td class="aksi-dokumen">

                                <a
                                    href="{{ asset('storage/'.$item->file_dokumentasi) }}"
                                    target="_blank"
                                    class="btn-view">

                                    👁 Lihat

                                </a>

                                <a
                                    href="{{ asset('storage/'.$item->file_dokumentasi) }}"
                                    download
                                    class="btn-download">

                                    ⬇ Download

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9" class="text-center">

                                Belum ada dokumen pada area ini.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

                </div>

            </div>

        </div>

        @endforeach
        </div><!-- /.dokumen-areas-container -->

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

    document.querySelectorAll(".tabelDokumen tbody tr").forEach(function(row) {
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
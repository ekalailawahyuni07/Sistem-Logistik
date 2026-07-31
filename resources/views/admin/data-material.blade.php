<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Material Admin</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        /* ===== DATA MATERIAL: FIT ONE SCREEN ===== */
        html {
            height: 100%;
        }

        body {
            height: 100vh;
            overflow: hidden; /* Cegah scroll pada body utama */
        }

        /* Sidebar tetap bisa scroll jika menu panjang */
        .sidebar {
            overflow-y: auto;
            height: 100vh;
        }

        .data-material-page {
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            padding: 18px 28px;
        }

        .data-material-page .topbar {
            flex-shrink: 0;
            margin-bottom: 12px;
        }

        .data-material-page .card {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            padding: 14px 16px;
        }

        .card-header-material {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 12px;
        }

        /* Container scrollable khusus untuk tabel */
        .material-table-container {
            flex: 1;
            overflow-y: auto;
            overflow-x: auto;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }

        .material-table-container::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .material-table-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        .material-table-container::-webkit-scrollbar-thumb {
            background: #a0aec0;
            border-radius: 4px;
        }

        .material-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .material-table thead th {
            position: sticky;
            top: 0;
            background: #1a3a6e;
            color: #ffffff;
            z-index: 2;
            padding: 10px;
            text-align: center;
            white-space: nowrap;
        }

        .material-table td {
            padding: 8px 10px;
            text-align: center;
            border-bottom: 1px solid #edf2f7;
        }

        .material-table tbody tr:hover {
            background: #f8fafc;
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
        <a href="{{ route('admin.data.material') }}"class="active">Master Data Material</a>
        <a href="{{ route('admin.material.masuk') }}">Material Masuk</a>
        <a href="{{ route('admin.material.keluar') }}">Material Keluar</a>
        <a href="{{ route('admin.stok.material') }}">Stok Material</a>
        <a href="{{ route('admin.dokumen') }}">Dokumen</a>
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

<div class="content data-material-page">
    <div class="topbar">
        <h1> Master Data Material</h1>
        <h2>👤 Halo, {{ Auth::user()->nama_user }} (Admin)</h2>
    </div>

    <div class="card">
        <div class="card-header-material">
            <h2>Daftar Material</h2>
            <div class="header-action" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <input
                    type="text"
                    id="searchMaterial"
                    placeholder="🔍 Cari material..."
                    onkeyup="cariMaterial()"
                    class="search-material"
                >
                <select
                    id="filterProject"
                    class="filter-area"
                    onchange="cariMaterial()"
                    style="color: #000000 !important;">
                    <option value="">Semua Project</option>
                    @foreach($projects as $p)
                        <option value="{{ strtolower($p->project) }}">{{ $p->project }}</option>
                    @endforeach
                </select>
                <a href="{{ route('admin.data.material.create') }}"
                class="btn-tambah">
                    + Tambah Material
                </a>
            </div>
        </div>

        <div class="material-table-container">
            <table class="material-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Material</th>
                        <th>Nama Material</th>
                        <th>Project</th>
                        <th>Jenis</th>
                        <th>Satuan</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($materials as $material)
                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $material->kode_material }}</td>
                    <td>{{ $material->nama_material }}</td>
                    <td>{{ $material->project }}</td>
                    <td>{{ $material->jenis_material }}</td>
                    <td>{{ $material->satuan }}</td>
                    <td>{{ $material->keterangan }}</td>

                    <td>
                        <a href="{{ route('admin.data.material.edit', $material->id_material) }}"
                        class="btn-edit">
                            Edit
                        </a>

                        <form
                            id="hapusForm{{ $material->id_material }}"
                            action="{{ route('admin.data.material.destroy', $material->id_material) }}"
                            method="POST"
                            style="display:inline;"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="button"
                                class="btn-delete"
                                onclick="bukaModalHapus({{ $material->id_material }})"
                            >
                                Hapus
                            </button>
                        </form>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;">
                        Belum ada data material
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
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

// =========================
// VARIABEL GLOBAL
// =========================

let formHapus = null;

// =========================
// LOGOUT
// =========================

function bukaModalLogout() {

    document.getElementById("modalLogout").style.display = "flex";

}

function tutupModalLogout() {

    document.getElementById("modalLogout").style.display = "none";

}

function submitLogout() {

    document.getElementById("logoutForm").submit();

}

// =========================
// HAPUS MATERIAL
// =========================

function bukaModalHapus(id) {

    formHapus = document.getElementById("hapusForm" + id);

    document.getElementById("modalHapus").style.display = "flex";

}

function tutupModalHapus() {

    document.getElementById("modalHapus").style.display = "none";

}

function submitHapus() {

    if (formHapus) {

        formHapus.submit();

    }

}

// =========================
// CARI MATERIAL
// =========================

function cariMaterial() {
    const keyword = document.getElementById("searchMaterial").value.toLowerCase();
    const selectedProject = document.getElementById("filterProject").value.toLowerCase();
    const rows = document.querySelectorAll(".material-table tbody tr");

    rows.forEach(function(row) {
        if (row.querySelector("td[colspan]")) return;

        const text = row.innerText.toLowerCase();
        const projectTd = row.children[3];
        const projectText = projectTd ? projectTd.innerText.toLowerCase().trim() : "";

        const matchesKeyword = text.includes(keyword);
        const matchesProject = !selectedProject || projectText.includes(selectedProject);

        if (matchesKeyword && matchesProject) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

// =========================
// TUTUP MODAL JIKA KLIK DI LUAR
// =========================

window.onclick = function(event){

    let modalLogout = document.getElementById("modalLogout");
    let modalHapus = document.getElementById("modalHapus");

    if(event.target == modalLogout){

        tutupModalLogout();

    }

    if(event.target == modalHapus){

        tutupModalHapus();

    }

}

</script>

<div id="modalHapus" class="modal-hapus">

    <div class="modal-box">

        <div class="modal-icon">
            🗑️
        </div>

        <h2>Hapus Material</h2>

        <p>
            Apakah Anda yakin ingin menghapus material ini?
        </p>

        <div class="modal-warning-text">
            Data yang dihapus tidak dapat dikembalikan lagi.
        </div>

        <div class="modal-actions">

            <button
                type="button"
                class="btn-batal-modal"
                onclick="tutupModalHapus()"
            >
                Batal
            </button>

            <button
                type="button"
                class="btn-hapus-modal"
                onclick="submitHapus()"
            >
                Ya, Hapus
            </button>

        </div>

    </div>

</div>

</body>
</html>
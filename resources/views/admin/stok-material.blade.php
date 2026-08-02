<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Stok Material Admin</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        /* ===== STOK MATERIAL: FIT ONE SCREEN ===== */
        html {
            height: 100%;
        }

        body {
            height: 100vh;
            overflow: hidden; /* cegah scroll body */
        }

        /* Sidebar tetap bisa scroll menu-nya */
        .sidebar {
            overflow-y: auto;
            height: 100vh;
        }

        .stok-material-page {
            height: 100vh;
            overflow-y: auto;
            padding: 22px 30px;
            box-sizing: border-box;
            display: block;
        }

        .stok-material-page .topbar {
            margin-bottom: 18px;
        }

        .stok-material-page .card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            display: block;
            overflow: visible;
        }

        .stok-material-page .card-header {
            margin-bottom: 15px;
        }

        .stok-areas-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .area-section {
            border: 1px solid #dbe3ed;
            border-radius: 10px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 3px 10px rgba(0,0,0,0.04);
            margin-bottom: 20px;
        }

        .stok-table-wrapper, .table-scroll {
            max-height: 220px !important;
            overflow-y: auto !important;
            overflow-x: auto !important;
            border-top: 1px solid #e2e8f0;
        }

        .area-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #061b40;
            color: #fff;
            padding: 8px 16px;
        }

        .area-header h3 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
        }

        .area-header span {
            font-size: 12px;
            background: rgba(255,255,255,0.15);
            padding: 2px 10px;
            border-radius: 20px;
        }

        .stok-table-wrapper, .table-responsive {
            max-height: 250px !important;
            overflow-y: auto !important;
            overflow-x: auto !important;
        }

        .stok-table-wrapper::-webkit-scrollbar, .table-responsive::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        .stok-table-wrapper::-webkit-scrollbar-track, .table-responsive::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .stok-table-wrapper::-webkit-scrollbar-thumb, .table-responsive::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px;
        }

        .material-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
        }

        .material-table thead tr {
            background: #1a3a6e;
            color: #fff;
        }

        .material-table th {
            position: sticky !important;
            top: 0 !important;
            background: #1a3a6e !important;
            color: #fff !important;
            z-index: 10 !important;
            padding: 7px 10px;
            text-align: center;
            font-weight: 600;
            white-space: nowrap;
        }

        .material-table td {
            padding: 6px 10px;
            text-align: center;
            border-bottom: 1px solid #edf2f7;
            color: #2d3748;
        }

        .material-table tbody tr:last-child td {
            border-bottom: none;
        }

        .material-table tbody tr:hover {
            background: #f7faff;
        }

        .text-center { text-align: center; }
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
        <a href="{{ route('admin.stok.material') }}" class="active">Stok Material</a>
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

<div class="content stok-material-page">
    <div class="topbar">
        <h1>Stok Material</h1>
        <h2>👤 Halo, {{ Auth::user()->nama_user }} (Admin)</h2>
    </div>

    <div class="card">

        <div class="card-header">

            <h2>Rekap Stok Material Berdasarkan Area</h2>

            <div class="stok-toolbar">

                <input
                    type="text"
                    id="searchStok"
                    placeholder="🔍 Cari Material..."
                    onkeyup="cariStok()">

                <select
                    id="filterProject"
                    class="filter-area"
                    onchange="cariStok()"
                    style="color:#000 !important;">
                    <option value="">Semua Project</option>
                    @foreach($projects as $p)
                        <option value="{{ strtolower($p->project) }}">{{ $p->project }}</option>
                    @endforeach
                </select>

                <form method="GET" action="{{ route('admin.stok.material') }}" style="margin:0;">
                    <select
                        name="id_area"
                        class="filter-area"
                        onchange="this.form.submit()"
                        style="color:#000 !important;">
                        <option value="">Semua Area</option>
                        @foreach($allAreas as $area)
                            <option
                                value="{{ $area->id_area }}"
                                {{ request('id_area') == $area->id_area ? 'selected' : '' }}>
                                {{ $area->nama_area }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <div class="export-dropdown">
                    <button
                        type="button"
                        class="btn-pdf"
                        onclick="toggleExportMenu()">
                        Export PDF ▾
                    </button>
                    <div id="exportMenu" class="export-menu">
                        <a href="{{ route('admin.stok.material.pdf') }}">
                            Export Semua Area
                        </a>
                        <a id="exportAreaBtn" href="#">
                            Export Area Dipilih
                        </a>
                    </div>
                </div>

            </div>

        </div>

        <div class="stok-areas-list">

        @foreach($areas as $area)

        <div class="area-section">

            <div class="area-header">

                <h3>{{ $area->nama_area }}</h3>

                <span>
                    Total Stock : {{ $area->total_stock }}
                </span>

            </div>

            <div class="stok-table-wrapper">

                <table class="material-table">

                    <thead>

                        <tr>
                            <th style="width:40px;">No</th>
                            <th style="min-width:120px;">Kode Material</th>
                            <th style="min-width:150px;">Nama Material</th>
                            <th style="min-width:90px;">Project</th>
                            <th style="min-width:70px;">Satuan</th>
                            <th style="min-width:90px;">Material IN</th>
                            <th style="min-width:90px;">Material OUT</th>
                            <th style="min-width:60px;">Stock</th>
                            <th style="min-width:80px;">Status</th>
                        </tr>

                    </thead>

                    <tbody>

                    @forelse($area->materials as $material)

                        @php
                            $masuk = $material->total_masuk ?? 0;
                            $keluar = $material->total_keluar ?? 0;
                            $stock = $masuk - $keluar;
                        @endphp

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $material->kode_material }}</td>

                            <td>{{ $material->nama_material }}</td>

                            <td>
                                <span style="background:#e8f0fe;color:#1a56db;padding:2px 8px;border-radius:12px;font-size:12px;font-weight:600;white-space:nowrap;">
                                    {{ $material->project_display ?? $material->project ?? '-' }}
                                </span>
                            </td>

                            <td>{{ $material->satuan }}</td>

                            <td>{{ $masuk }}</td>

                            <td>{{ $keluar }}</td>

                            <td>{{ $stock }}</td>

                            <td>

                                @if($stock <= 0)

                                    🔴 Habis

                                @elseif($stock <= 10)

                                    🟡 Menipis

                                @else

                                    🟢 Aman

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9" class="text-center">

                                Belum ada stok material

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @endforeach

        </div><!-- /.stok-areas-list -->

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
function cariStok() {
    let keyword = document.getElementById("searchStok").value.toLowerCase();
    let filterProject = document.getElementById("filterProject") ? document.getElementById("filterProject").value.toLowerCase() : "";
    let rows = document.querySelectorAll(".material-table tbody tr");

    rows.forEach(row => {
        if (row.querySelector("td[colspan]")) return;

        let text = row.innerText.toLowerCase();
        let projectTd = row.children[3];
        let projectText = projectTd ? projectTd.innerText.toLowerCase().trim() : "";

        let matchesKeyword = text.includes(keyword);
        let matchesProject = !filterProject || projectText.includes(filterProject);

        row.style.display = (matchesKeyword && matchesProject) ? "" : "none";
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

function toggleExportMenu(){

    let menu = document.getElementById("exportMenu");

    if(menu.style.display==="block"){

        menu.style.display="none";

    }else{

        menu.style.display="block";

    }

    const selectArea = document.querySelector('select[name="id_area"]');
    const exportAreaBtn = document.getElementById("exportAreaBtn");

    function updateExportLink(){

        const area = selectArea.value;

        exportAreaBtn.href =
            "{{ route('admin.stok.material.pdf') }}?id_area=" + area;

    }

    updateExportLink();

    selectArea.addEventListener("change", updateExportLink);
}

window.addEventListener("click",function(e){

    if(!e.target.closest(".export-dropdown")){

        document.getElementById("exportMenu").style.display="none";

    }

});
</script>

</body>
</html>
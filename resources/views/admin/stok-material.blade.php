<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Stok Material Admin</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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

                <form method="GET" action="{{ route('admin.stok.material') }}">
                    <select
                        name="id_area"
                        onchange="this.form.submit()">
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
                            📄 Export Semua Area
                        </a>
                        <a id="exportAreaBtn" href="#">
                            📍 Export Area Dipilih
                        </a>
                    </div>
                </div>

            </div>

        </div>

        @foreach($areas as $area)

        <div class="area-section">

            <div class="area-header">

                <h3>📍 {{ $area->nama_area }}</h3>

                <span>
                    Total Stock : {{ $area->total_stock }}
                </span>

            </div>

            <div class="stok-table-wrapper">

                <table class="material-table">

                    <thead>

                        <tr>
                            <th>No</th>
                            <th>Kode Material</th>
                            <th>Nama Material</th>
                            <th>Satuan</th>
                            <th>Material IN</th>
                            <th>Material OUT</th>
                            <th>Stock</th>
                            <th>Status</th>
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

                            <td colspan="8" class="text-center">

                                Belum ada stok material

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @endforeach

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
                Ya, Logout
            </button>

        </div>

    </div>

</div>


<script>
function cariStok() {
    let input = document.getElementById("searchStok").value.toLowerCase();
    let rows = document.querySelectorAll("#tabelStok tbody tr");

    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(input) ? "" : "none";
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
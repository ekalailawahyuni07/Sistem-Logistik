<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Material</title>
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
        <a href="{{ route('data.material') }}"class="{{ request()->routeIs('data.material') ? 'active' : '' }}">Master Data Material</a>
        <a href="{{ route('material.masuk') }}">Material Masuk</a>
        <a href="{{ route('material.keluar') }}">Material Keluar</a>
        <a href="{{ route('stok.material') }}">Stok Material</a>
        <a href="{{ route('cluster') }}">Daftar Kluster</a>
        <a href="{{ route('dokumen') }}">Daftar Dokumen</a>
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
        <h1>Master Data Material</h1>
        <h2>👤 Halo, {{ Auth::user()->nama_user }}! (Petugas)</h2>
    </div>

    <div class="card">

    <div class="material-card-header">

        <h2>Daftar Material</h2>

        <div class="material-toolbar">

            <input
                type="text"
                id="searchMaterial"
                class="material-search"
                placeholder="🔍 Cari material..."
                onkeyup="cariMaterial()"
            >

            {{-- ROUTE INI NANTI PAKAI ROUTE LAMA KAMU --}}
            <a href="{{ route('data.material.create') }}" class="btn-add">
                + Tambah Material
            </a>

        </div>

    </div>

    <table class="material-table" id="tabelMaterial">
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
                    <td>{{ $material->project ?? '-' }}</td>
                    <td>{{ $material->jenis_material }}</td>
                    <td>{{ $material->satuan }}</td>
                    <td>{{ $material->keterangan }}</td>
                    <td>
                        <a href="{{ route('data.material.edit', $material->id_material) }}" class="btn-edit">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;">
                        Belum ada data material
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

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

<script>
function cariMaterial() {
    const keyword = document
        .getElementById('searchMaterial')
        .value
        .toLowerCase();

    const rows = document.querySelectorAll('#tabelMaterial tbody tr');

    rows.forEach(function(row) {
        const text = row.innerText.toLowerCase();

        if (text.includes(keyword)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
</body>
</html>
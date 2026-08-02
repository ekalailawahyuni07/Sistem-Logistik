<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Material Masuk</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        html { height: 100%; }
        body { height: 100vh; overflow: hidden; }
        .sidebar { overflow-y: auto; height: 100vh; }
        .user-page-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            padding: 20px 30px;
            box-sizing: border-box;
        }
        .user-page-container .topbar { flex-shrink: 0; margin-bottom: 14px; }
        .user-page-container .card {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0;
            overflow: hidden;
            padding: 20px 24px;
            margin-bottom: 0;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        .material-card-header { flex-shrink: 0; margin-bottom: 14px; }
        .table-container-scroll, .stok-table-wrapper, .table-scroll {
            flex: 1 !important;
            max-height: none !important;
            overflow-y: auto !important;
            overflow-x: auto !important;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        .table-container-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .table-container-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
        .table-container-scroll::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 3px; }
        .material-table thead th {
            position: sticky !important;
            top: 0 !important;
            background: #1a3a6e !important;
            color: #ffffff !important;
            z-index: 10 !important;
            padding: 10px 12px;
            text-align: center;
            font-weight: 600;
            white-space: nowrap;
        }
    </style>
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
        <a href="{{ route('material.masuk') }}"class="{{ request()->routeIs('material.masuk') ? 'active' : '' }}">Material Masuk</a>
        <a href="{{ route('material.keluar') }}">Material Keluar</a>
        <a href="{{ route('stok.material') }}">Stok Material</a>
        <a href="{{ route('cluster') }}">Daftar Kluster</a>
        <a href="{{ route('dokumen') }}">Dokumen</a>
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

<div class="content user-page-container">
    <div class="topbar">
        <h1>Material Masuk</h1>
        <h2>👤 Halo, {{ Auth::user()->nama_user }}! (Petugas)</h2>
    </div>

    <div class="card">

        <div class="material-card-header">

            <h2>Material Masuk Area {{ Auth::user()->area->nama_area }}</h2>

            <div class="material-toolbar">

                <input
                    type="text"
                    id="searchMaterialMasuk"
                    class="material-search"
                    placeholder="🔍 Cari material masuk..."
                    onkeyup="cariMaterialMasuk()"
                >

                <a href="{{ route('material.masuk.create') }}" class="btn-add">
                    + Tambah Material Masuk
                </a>

            </div>

        </div>

        <div class="table-container-scroll">
            <table class="material-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>No Surat Jalan</th>
                        <th>Project</th>
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

                        <td>{{ $item->project }}</td>

                        <td>{{ $item->material->kode_material }}</td>

                        <td>{{ $item->material->nama_material }}</td>

                        <td>{{ $item->jumlah }}</td>

                        <td>{{ $item->material->satuan }}</td>

                        <td>{{ $item->keterangan }}</td>

                        <td>
                            <a href="{{ route('material.masuk.edit', $item->id_transaksi) }}" class="btn-edit">
                                Edit
                            </a>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="10" style="text-align:center;">
                            Belum ada data material masuk
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

    function cariMaterialMasuk() {
    const keyword = document.getElementById("searchMaterialMasuk").value.toLowerCase();
    const rows = document.querySelectorAll(".material-table tbody tr");

    rows.forEach(function(row) {
        if (row.querySelector("td[colspan]")) return;
        row.style.display = row.innerText.toLowerCase().includes(keyword) ? "" : "none";
    });
}
</script>
</body>
</html>
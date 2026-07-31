<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Kluster</title>
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
            padding: 18px 28px;
        }
        .user-page-container .topbar { flex-shrink: 0; margin-bottom: 12px; }
        .user-page-container .card {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            padding: 14px 16px;
        }
        .material-card-header { flex-shrink: 0; margin-bottom: 12px; }
        .table-container-scroll {
            flex: 1;
            overflow-y: auto;
            overflow-x: auto;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        .table-container-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .table-container-scroll::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .table-container-scroll::-webkit-scrollbar-thumb { background: #a0aec0; border-radius: 4px; }
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
        <a href="{{ route('material.masuk') }}">Material Masuk</a>
        <a href="{{ route('material.keluar') }}">Material Keluar</a>
        <a href="{{ route('stok.material') }}">Stok Material</a>
        <a href="{{ route('cluster') }}" class="active">Daftar Kluster</a>
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
        <h1>Daftar Kluster</h1>
        <h2>👤 Halo, {{ Auth::user()->nama_user }}! (Petugas)</h2>
    </div>

    <div class="card">
        <div class="material-card-header">
            <h2>Daftar Kluster Area {{ Auth::user()->area->nama_area ?? '' }}</h2>

            <div class="material-toolbar">
                <input
                    type="text"
                    id="searchCluster"
                    class="material-search"
                    placeholder="🔍 Cari kluster..."
                    onkeyup="cariCluster()"
                >

                <a href="{{ route('cluster.create') }}" class="btn-add">
                    + Tambah Kluster
                </a>
            </div>
        </div>

        <div class="table-container-scroll">
            <table class="material-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Kluster</th>
                        <th>Nama Kluster</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($clusters as $cluster)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $cluster->kode_cluster }}</td>
                            <td>{{ $cluster->nama_cluster }}</td>
                            <td>
                                <a href="{{ route('cluster.edit', $cluster->id_cluster) }}" class="btn-edit">
                                    Edit
                                </a>
                                <a href="{{ route('cluster.show', $cluster->id_cluster) }}" class="btn-view">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center;">
                                Belum ada data kluster
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
            Anda harus lmasuk kembali untuk mengakses sistem.
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
function cariCluster() {
    let input = document.getElementById("searchCluster").value.toLowerCase();
    let rows = document.querySelectorAll(".material-table tbody tr");

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
</script>

</body>
</html>
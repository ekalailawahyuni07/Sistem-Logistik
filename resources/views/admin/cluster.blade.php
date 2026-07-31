<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cluster Admin</title>
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
        <a href="{{ route('admin.cluster') }}" class="active">Daftar Kluster</a>
        <a href="{{ route('admin.data.material') }}">Master Data Material</a>
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

<div class="content">
    <div class="topbar">
        <h1>Daftar Kluster</h1>
        <h2>👤 Halo, {{ Auth::user()->nama_user }} (Admin)</h2>
    </div>

    <div class="card">
        <div class="card-header-material">
            <h2>Daftar Kluster Berdasarkan Area</h2>
            <div class="header-action">
                <input
                    type="text"
                    id="searchCluster"
                    placeholder="🔍 Cari cluster..."
                    onkeyup="cariCluster()"
                    class="search-cluster"
                >
                <select
                    id="filterArea"
                    class="filter-area"
                    onchange="cariCluster()"
                >
                    <option value="">Semua Area</option>

                    @foreach($areas as $area)
                        <option value="{{ strtolower($area->nama_area) }}">
                            {{ $area->nama_area }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        @if(session('success'))
            <div style="
                margin: 15px 20px;
                padding: 10px;
                background-color: #d4edda;
                color: #155724;
                border-radius: 5px;
            ">
                {{ session('success') }}
            </div>
        @endif

        <table class="material-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Cluster</th>
                    <th>Nama Cluster</th>
                    <th>Area</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($clusters as $cluster)
                    <tr data-area="{{ strtolower($cluster->area->nama_area ?? '') }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $cluster->kode_cluster }}</td>
                        <td>{{ $cluster->nama_cluster }}</td>

                        <td>
                            {{ $cluster->area->nama_area ?? '-' }}
                        </td>

                        <td>
                            <div class="aksi-buttons">

                                <a href="{{ route('admin.cluster.edit', $cluster->id_cluster) }}"
                                    class="btn-edit">
                                    Edit
                                </a>

                                <a href="{{ route('admin.cluster.show', $cluster->id_cluster) }}"
                                    class="btn-view">
                                    Lihat
                                </a>

                                <form
                                    id="hapusForm{{ $cluster->id_cluster }}"
                                    action="{{ route('admin.cluster.destroy', $cluster->id_cluster) }}"
                                    method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="button"
                                        class="btn-delete"
                                        onclick="bukaModalHapus('hapusForm{{ $cluster->id_cluster }}')">
                                        Hapus
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;">
                            Belum ada data cluster
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

<div id="modalHapus" class="modal-hapus">

    <div class="modal-box">

        <div class="modal-icon">
            🗑️
        </div>

        <h2>Hapus Kluster</h2>

        <p>
            Apakah Anda yakin ingin menghapus kluster ini?
        </p>

        <div class="modal-warning-text">
            Data yang dihapus tidak dapat dikembalikan.
        </div>

        <div class="modal-actions">

            <button
                type="button"
                class="btn-batal-modal"
                onclick="tutupModalHapus()">

                Batal

            </button>

            <button
                type="button"
                class="btn-hapus-modal"
                onclick="submitHapus()">

                Ya, Hapus

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

let formHapus = null;

function bukaModalHapus(idForm){

    formHapus = document.getElementById(idForm);

    document.getElementById("modalHapus").style.display = "flex";

}

function tutupModalHapus(){

    document.getElementById("modalHapus").style.display = "none";

    formHapus = null;

}

function submitHapus(){

    if(formHapus){

        formHapus.submit();

    }

}

window.onclick=function(event){

    let modal=document.getElementById("modalLogout");

    if(event.target==modal){

        tutupModalLogout();

    }

}
function cariCluster(){

    const keyword = document
        .getElementById("searchCluster")
        .value
        .toLowerCase();

    const area = document
        .getElementById("filterArea")
        .value
        .toLowerCase();

    document.querySelectorAll(".material-table tbody tr").forEach(row=>{

        const text = row.innerText.toLowerCase();

        const rowArea = row.dataset.area || "";

        const cocokCari = text.includes(keyword);

        const cocokArea =
            area === "" ||
            rowArea === area;

        row.style.display =
            (cocokCari && cocokArea)
            ? ""
            : "none";

    });

}

</script>

</body>
</html>
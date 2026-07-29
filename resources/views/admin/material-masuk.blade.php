<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Material Masuk Admin</title>
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
        <a href="{{ route('admin.material.masuk') }}" class="active">Material Masuk</a>
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
        <h1>Material Masuk</h1>
        <h2>👤 Halo, {{ Auth::user()->nama_user }} (Admin)</h2>
    </div>

    <div class="card">

        <div class="card-header-material">

            <div class="header-title">
                <h2>Material Masuk Berdasarkan Area</h2>
            </div>

            <div class="header-action">

                <input
                    type="text"
                    id="searchMaterial"
                    class="search-material"
                    placeholder="🔍 Cari Material...">

                <select
                    id="filterArea"
                    class="filter-area"
                    onchange="filterMaterial()">

                    <option value="">Semua Area</option>

                    @foreach($areas as $area)
                        <option value="{{ strtolower($area->nama_area) }}">
                            {{ $area->nama_area }}
                        </option>
                    @endforeach

                </select>

                <a href="{{ route('admin.material.masuk.create') }}"
                class="btn-tambah">
                    + Tambah Material Masuk
                </a>

            </div>

        </div>

        @foreach($areas as $area)

        <div class="area-card"
            data-area="{{ strtolower($area->nama_area) }}">

            <div class="area-header">
                <h3>📍 {{ $area->nama_area }}</h3>

                <span>
                    Total Material Masuk :
                    {{ $area->transaksiMaterial->count() }}
                </span>
            </div>

            <div class="table-scroll">

                <!-- BAGIAN TABEL KAMU TETAP -->
                <table class="material-table">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>No Surat Jalan</th>
                            <th>Kode Material</th>
                            <th>Nama Material</th>
                            <th>Project</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($area->transaksiMaterial as $item)

                        <tr>

                            <td>{{ $loop->iteration }}</td>
                            <td>{{ date('d-m-Y',strtotime($item->tgl_transaksi)) }}</td>
                            <td>{{ $item->no_bukti }}</td>
                            <td>{{ $item->material->kode_material }}</td>
                            <td>{{ $item->material->nama_material }}</td>
                            <td>{{ $item->project }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>{{ $item->material->satuan }}</td>
                            <td>{{ $item->keterangan }}</td>

                            <td>

                                <a href="{{ route('admin.material.masuk.edit',$item->id_transaksi) }}"
                                class="btn-edit">
                                    Edit
                                </a>

                                <form
                                    id="hapusForm{{ $item->id_transaksi }}"
                                    action="{{ route('admin.material.masuk.destroy',$item->id_transaksi) }}"
                                    method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="button"
                                        class="btn-delete"
                                        onclick="bukaModalHapus(this)">
                                        Hapus
                                    </button>

                                </form>

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

        @endforeach

    </div>
</div>

<script>
function konfirmasiLogout() {
    return confirm("Apakah Anda yakin ingin keluar?");
}
</script>

<div id="modalHapus" class="modal-hapus">
    <div class="modal-box">
        <div class="modal-icon">⚠️</div>
        <h2>Konfirmasi Hapus</h2>
        <p>Data material masuk ini akan dihapus permanen. Yakin ingin melanjutkan?</p>

        <div class="modal-actions">
            <button type="button" class="btn-batal-modal" onclick="tutupModalHapus()">
                Batal
            </button>

            <button type="button" class="btn-konfirmasi-modal" onclick="submitHapus()">
                Ya, Hapus
            </button>
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
                Ya, Logout
            </button>

        </div>

    </div>

</div>

<script>
let formHapusAktif = null;

function bukaModalHapus(button) {
    formHapusAktif = button.closest('form');
    document.getElementById('modalHapus').style.display = 'flex';
}

function tutupModalHapus() {
    formHapusAktif = null;
    document.getElementById('modalHapus').style.display = 'none';
}

function submitHapus() {
    if (formHapusAktif) {
        formHapusAktif.submit();
    }
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

function filterMaterial() {
    const filter = document.getElementById("filterArea").value.toLowerCase();
    const cards = document.querySelectorAll(".area-card");

    cards.forEach(card => {
        const area = card.dataset.area.toLowerCase();

        if (filter === "" || area === filter) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
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
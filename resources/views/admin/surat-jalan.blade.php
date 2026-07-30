<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Jalan Admin</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

<div class="sidebar">

    <div class="logo">
        <img src="{{ asset('images/logo-tkm.png') }}" alt="Logo PT">
    </div>

    <a href="{{ route('admin.profile.edit') }}"
       class="profile-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">

        <div class="profile">

            @if(Auth::user()->foto_profile)
                <img src="{{ asset('storage/'.Auth::user()->foto_profile) }}"
                     class="profile-img">
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
        <a href="{{ route('admin.dokumen') }}">Dokumen</a>
        <a href="{{ route('admin.surat.jalan') }}" class="active">Surat Jalan</a>
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
                onclick="bukaModalLogout()">

                Keluar

            </button>

        </form>

    </div>

</div>


<div class="content">
    <div class="sj-page">
    <div class="topbar">

        <h1>Surat Jalan</h1>

        <h2>
            👤 Halo,
            {{ Auth::user()->nama_user }}
            (Admin)
        </h2>

    </div>


    <div class="card">

        <div class="card-header">

            <h2>Daftar Surat Jalan Berdasarkan Area</h2>

            <div class="stok-toolbar">

                <input
                    type="text"
                    id="searchSurat"
                    placeholder="🔍 Cari Surat Jalan..."
                    onkeyup="cariSurat()">

                <form
                    method="GET"
                    action="{{ route('admin.surat.jalan') }}">

                    <select
                        name="id_area"
                        class="filter-area"
                        onchange="this.form.submit()">

                        <option value="">
                            Semua Area
                        </option>

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


        @foreach($areas as $area)

        <div class="area-section">

            <div class="area-header">

                <h3>
                    📍 {{ $area->nama_area }}
                </h3>

                <span>

                    Total Surat Jalan :
                    {{ $area->suratJalan->count() }}

                </span>

            </div>


            <div class="stok-table-wrapper">

                <div class="table-responsive">

                    <table class="material-table tabelSurat">

                        <thead>

                            <tr>

                                <th>No</th>
                                <th>Tanggal</th>
                                <th>No Surat Jalan</th>
                                <th>Project</th>
                                <th>Cluster</th>
                                <th>Penerima</th>
                                <th>Material</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($area->suratJalan as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d-m-Y') }}</td>
                                <td>{{ $item->no_bukti }}</td>
                                <td>{{ $item->project ?? '-' }}</td>
                                <td>{{ $item->cluster->nama_cluster ?? '-' }}</td>
                                <td>{{ $item->nama_penerima ?? '-' }}</td>
                                <td>{{ $item->material->nama_material ?? '-' }}</td>
                                <td>
                                    {{ $item->jumlah }}
                                    {{ $item->material->satuan ?? '' }}
                                </td>
                                <td>
                                    <a
                                        href="{{ route('admin.surat.jalan.show', $item->id_transaksi) }}"
                                        class="btn-view">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" style="text-align:center;padding:20px;">
                                    Belum ada Surat Jalan pada Area
                                    <strong>{{ $area->nama_area }}</strong>
                                </td>
                            </tr>
                            @endforelse
                            </tbody>
                            </table>
                            </div>
                            </div>
                            </div>
                            @endforeach
    </div>
    </div>
</div>

<!-- Modal Logout -->
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
                onclick="tutupModalLogout()">

                Batal

            </button>

            <button
                type="button"
                class="btn-logout-modal"
                onclick="submitLogout()">

                Ya, Logout

            </button>

        </div>

    </div>

</div>


<script>

function cariSurat() {
    let keyword = document.getElementById("searchSurat").value.toLowerCase();
    document.querySelectorAll(".tabelSurat tbody tr").forEach(function(row) {
        row.style.display = row.innerText.toLowerCase().includes(keyword) ? "" : "none";
    });
}


function bukaModalLogout(){

    document
        .getElementById("modalLogout")
        .style.display="flex";

}


function tutupModalLogout(){

    document
        .getElementById("modalLogout")
        .style.display="none";

}


function submitLogout(){

    document
        .getElementById("logoutForm")
        .submit();

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
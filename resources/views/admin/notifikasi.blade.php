<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Log Keluar Masuk</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

<div class="sidebar">
    <div class="logo">
        <img src="{{ asset('images/logo-tkm.png') }}">
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
        <a href="{{ route('admin.dokumen') }}">Dokumen</a>
        <a href="{{ route('admin.surat.jalan') }}">Surat Jalan</a>
        <a href="{{ route('admin.notifikasi') }}" class="active">Log Keluar Masuk</a>
    </div>

    <div class="logout">

        <form
            method="POST"
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

    <div class="topbar">

        <h1>Log Keluar Masuk</h1>

        <form
            method="GET"
            action="{{ route('admin.notifikasi') }}"
            style="margin:0;">

            <input
                type="text"
                name="keyword"
                placeholder="🔍 Cari tanggal, material, no bukti..."
                value="{{ request('keyword') }}">

        </form>

        <h2>
            👤 Halo, {{ Auth::user()->nama_user }} (Admin)
        </h2>

    </div>

    <div class="card">

        <table class="material-table">

            <thead>

                <tr>

                    <th>No</th>

                    <th>Waktu</th>

                    <th>Aktivitas</th>

                    <th>Material</th>

                    <th>Jumlah</th>

                    <th>No Bukti</th>

                </tr>

            </thead>

            <tbody>
                @forelse($notifikasi as $item)

<tr>

    <td>{{ $loop->iteration }}</td>

    <td>
        {{ $item->created_at->format('d-m-Y H:i:s') }}
    </td>

    <td>

        @if($item->jenis_transaksi=='masuk')

            <span style="color:green;font-weight:bold;">
                Material Masuk
            </span>

        @else

            <span style="color:red;font-weight:bold;">
                Material Keluar
            </span>

        @endif

    </td>

    <td>
        {{ $item->material->nama_material ?? '-' }}
    </td>

    <td>
        {{ $item->jumlah }}
    </td>

    <td>
        {{ $item->no_bukti }}
    </td>

</tr>

@empty

<tr>

    <td colspan="6" style="text-align:center;">
        Tidak ada data log.
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

function bukaModalLogout(){

    document.getElementById("modalLogout").style.display="flex";

}

function tutupModalLogout(){

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
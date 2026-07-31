<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

<div class="sidebar">
    <div class="logo">
        <img src="{{ asset('images/logo-tkm.png') }}" alt="Logo PT">
    </div>

    <div class="profile">
        @if(Auth::user()->foto_profile)
            <img src="{{ asset('storage/' . Auth::user()->foto_profile) }}" class="profile-img">
        @else
            <div class="avatar">👤</div>
        @endif

        <h4>{{ Auth::user()->nama_user }}</h4>
        <p>{{ Auth::user()->email }}</p>
    </div>

    <div class="menu">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('data.material') }}">Master Data Material</a>
        <a href="{{ route('material.masuk') }}">Material Masuk</a>
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

<div class="content">
    <div class="topbar">
        <h1>👤 Profile</h1>
        <h2>Halo, {{ Auth::user()->nama_user }}! (Petugas)</h2>
    </div>

    <div class="profile-page">

        <div class="profile-card-left">
            <div class="profile-cover"></div>

            <div class="profile-photo-wrap">
                @if($user->foto_profile)
                    <img src="{{ asset('storage/' . $user->foto_profile) }}" class="profile-big-img">
                @else
                    <div class="profile-big-avatar">👤</div>
                @endif
            </div>

            <h2>{{ $user->nama_user }}</h2>
            <span class="badge-role">Petugas</span>

            <div class="profile-info-list">
                <div>
                    <strong>Email</strong>
                    <p>{{ $user->email }}</p>
                </div>

                <div>
                    <strong>Nomor Telepon</strong>
                    <p>{{ $user->no_telp ?? '-' }}</p>
                </div>

                <div>
                    <strong>Alamat</strong>
                    <p>{{ $user->alamat ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="profile-card-right">
            @include('profile.partials.update-profile-information-form')

            <hr style="margin:30px 0;">

            @include('profile.partials.update-password-form')

            <hr style="margin:30px 0;">

            @include('profile.partials.delete-user-form')
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

    }

}
</script>

</body>
</html>
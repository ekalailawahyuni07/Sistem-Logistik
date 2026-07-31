<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kluster</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .form-header h1,
        .form-header h2,
        .form-group label,
        .topbar h1 {
            color: #000000 !important;
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

<div class="content">

    <div class="form-card">

        <div class="form-header">
            <div>
                <h1>Edit Kluster</h1>
                <p>Silahkan ubah data kluster.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('cluster.update', $cluster->id_cluster) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group">
                    <label>Kode Kluster <span style="color:red;">*</span></label>
                    <input
                        type="text"
                        name="kode_cluster"
                        value="{{ $cluster->kode_cluster }}"
                        style="color: #000000;"
                        required>
                </div>

                <div class="form-group">
                    <label>Nama Kluster <span style="color:red;">*</span></label>
                    <input
                        type="text"
                        name="nama_cluster"
                        value="{{ $cluster->nama_cluster }}"
                        style="color: #000000;"
                        required>
                </div>

            </div>

            <div class="form-footer">

                <a href="{{ route('cluster') }}" class="btn-kembali">
                    ← Kembali
                </a>

                <button type="submit" class="btn-update">
                    ✓ Update
                </button>

            </div>

        </form>

    </div>

</div>

<div id="modalLogout" class="modal-hapus">
    <div class="modal-box">
        <div class="modal-icon logout-icon">
            🚪
        </div>
        <h2>Keluar</h2>
        <p>Apakah Anda yakin ingin keluar dari sistem?</p>
        <div class="modal-warning-text">
            Anda harus masuk kembali untuk mengakses sistem.
        </div>
        <div class="modal-actions">
            <button type="button" class="btn-batal-modal" onclick="tutupModalLogout()">
                Batal
            </button>
            <button type="button" class="btn-logout-modal" onclick="submitLogout()">
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
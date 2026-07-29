<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Material</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .form-header h1,
        .form-header h2,
        .form-group label,
        .topbar h1,
        .form-group input,
        .form-group select,
        .form-group textarea {
            color: #000000 !important;
        }
    </style>
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
        <a href="{{ route('data.material') }}" class="active">Master Data Material</a>
        <a href="{{ route('material.masuk') }}">Material Masuk</a>
        <a href="{{ route('material.keluar') }}">Material Keluar</a>
        <a href="{{ route('stok.material') }}">Stok Material</a>
        <a href="{{ route('cluster') }}">Daftar Kluster</a>
        <a href="{{ route('dokumen') }}">Daftar Dokumen</a>
        <a href="{{ route('surat.jalan') }}">Surat Jalan</a>
    </div>

    <div class="logout">
        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
            @csrf
            <button type="button" class="logout-btn" onclick="bukaModalLogout()">
                Keluar
            </button>
        </form>
    </div>
</div>

<div class="content">

    <div class="form-card">

        <div class="form-header">
            <div>
                <h1>Tambah Material</h1>
                <p>Silahkan isi data material baru.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('data.material.store') }}">
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label>Kode Material <span style="color:red;">*</span></label>
                    <input
                        type="text"
                        name="kode_material"
                        placeholder="Masukkan kode material"
                        required>
                </div>

                <div class="form-group">
                    <label>Nama Material <span style="color:red;">*</span></label>
                    <input
                        type="text"
                        name="nama_material"
                        placeholder="Masukkan nama material"
                        required>
                </div>

                <div class="form-group">
                    <label>Project <span style="color:red;">*</span></label>
                    <input
                        type="text"
                        name="project"
                        placeholder="Masukkan nama project"
                        required>
                </div>

                <div class="form-group">
                    <label>Jenis Material <span style="color:red;">*</span></label>
                    <input
                        type="text"
                        name="jenis_material"
                        placeholder="Masukkan jenis material"
                        required>
                </div>

                <div class="form-group">
                    <label>Satuan <span style="color:red;">*</span></label>
                    <input
                        type="text"
                        name="satuan"
                        placeholder="Contoh : Batang"
                        required>
                </div>

                <div class="form-group full">
                    <label>Keterangan</label>
                    <textarea
                        name="keterangan"
                        rows="5"
                        placeholder="Masukkan keterangan..."></textarea>
                </div>

            </div>

            <div class="form-footer">

                <a href="{{ route('data.material') }}" class="btn-kembali">
                    ← Kembali
                </a>

                <button type="submit" class="btn-simpan">
                    💾 Simpan
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

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Material Admin</title>
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
        <a href="{{ route('admin.data.material') }}" class="active">Master Data Material</a>
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

    <div class="form-card">
        <div class="form-header">
            <div class="form-icon">✏️</div>
            <div>
                <h2>Edit Material</h2>
                <p>Perbarui informasi material sesuai kebutuhan pada formulir di bawah ini</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.data.material.update', $material->id_material) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group">
                    <label>Kode Material <span style="color:red;">*</span></label>
                    <input
                        type="text"
                        name="kode_material"
                        value="{{ old('kode_material', $material->kode_material) }}"
                        required>
                </div>

                <div class="form-group">
                    <label>Nama Material <span style="color:red;">*</span></label>
                    <input
                        type="text"
                        name="nama_material"
                        value="{{ old('nama_material', $material->nama_material) }}"
                        required>
                </div>

                <div class="form-group">
                    <label>Project <span style="color:red;">*</span></label>
                    <input
                        type="text"
                        name="project"
                        value="{{ old('project', $material->project) }}"
                        placeholder="Masukkan nama project"
                        required>
                </div>

                <div class="form-group">
                    <label>Jenis Material <span style="color:red;">*</span></label>
                    <input
                        type="text"
                        name="jenis_material"
                        value="{{ old('jenis_material', $material->jenis_material) }}"
                        required>
                </div>

                <div class="form-group">
                    <label>Satuan <span style="color:red;">*</span></label>
                    <input
                        type="text"
                        name="satuan"
                        value="{{ old('satuan', $material->satuan) }}"
                        required>
                </div>

                <div class="form-group full">
                    <label>Keterangan</label>
                    <textarea
                        name="keterangan"
                        rows="5">{{ old('keterangan', $material->keterangan) }}</textarea>
                </div>
            </div>
            <div class="form-footer">
                <a href="{{ route('admin.data.material') }}" class="btn-kembali">
                    ← Kembali
                </a>

                <div>
                    <button type="reset" class="btn-reset">
                        Reset
                    </button>

                    <button type="submit" class="btn-update">
                        ✓ Update
                    </button>
                </div>
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

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi User Admin</title>
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
                <img
                    src="{{ asset('storage/' . Auth::user()->foto_profile) }}"
                    class="profile-img"
                    alt="Foto Profil"
                >
            @else
                <div class="avatar">👤</div>
            @endif

            <h4>{{ Auth::user()->nama_user }}</h4>
            <p>{{ Auth::user()->email }}</p>
        </div>
    </a>

    <div class="menu">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.verifikasi.user') }}"class="active">Verifikasi User</a>
        <a href="{{ route('admin.kelola.area') }}">Kelola Area & Kluster</a>
        <a href="{{ route('admin.cluster') }}">Daftar Kluster</a>
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

<div class="content page-fit-screen">

    <div class="topbar">
        <h1>Verifikasi User</h1>

        <h2>
            👤 Hello, {{ Auth::user()->nama_user }} (Admin)
        </h2>
    </div>

    @if(session('success'))
        <div
            class="alert"
            style="background:#dcfce7;color:#166534;"
        >
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header-material">
            <h2>Daftar Pengajuan Akun</h2>
            <div class="header-tools">
                <form method="GET" class="header-filter">
                    <input
                        type="text"
                        name="search"
                        id="searchUser"
                        value="{{ request('search') }}"
                        placeholder="🔍 Cari nama atau email..."
                        class="search-user"
                    >
                    <select
                        name="area"
                        class="filter-area"
                        onchange="this.form.submit()"
                    >
                        <option value="">Semua Area</option>
                        @foreach($areas as $area)
                            <option
                                value="{{ $area->id_area }}"
                                {{ request('area') == $area->id_area ? 'selected' : '' }}
                            >
                                {{ $area->nama_area }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="material-table-container">
            <table class="material-table" id="tabelUser">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama User</th>
                        <th>Email</th>
                        <th>Nomor Telepon</th>
                        <th>Area</th>
                        <th>Role</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $user->nama_user }}</td>

                            <td>{{ $user->email }}</td>

                            <td>{{ $user->no_telp ?? '-' }}</td>

                            <td>
                                {{ $user->area->nama_area ?? '-' }}
                            </td>

                            <td>
                                {{ $user->role->nama_role ?? '-' }}
                            </td>

                            <td>
                                {{ optional($user->created_at)->format('d-m-Y H:i') ?? '-' }}
                            </td>

                            <td>
                                @if($user->status_validasi === 'pending')
                                    <span class="status-pending">
                                        Menunggu
                                    </span>

                                @elseif($user->status_validasi === 'disetujui')
                                    <span class="status-disetujui">
                                        Disetujui
                                    </span>

                                @else
                                    <span class="status-ditolak">
                                        Ditolak
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($user->status_validasi === 'pending')

                                    <div class="verifikasi-actions">

                                        <form
                                            method="POST"
                                            action="{{ route('admin.verifikasi.user.setujui', $user->id_user) }}"
                                            class="form-setujui"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="button"
                                                class="btn-setujui"
                                                onclick="bukaModalVerifikasi(
                                                    this,
                                                    'setujui',
                                                    '{{ addslashes($user->nama_user) }}'
                                                )"
                                            >
                                                ✓ Setujui
                                            </button>
                                        </form>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.verifikasi.user.tolak', $user->id_user) }}"
                                            class="form-tolak"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="button"
                                                class="btn-tolak"
                                                onclick="bukaModalVerifikasi(
                                                    this,
                                                    'tolak',
                                                    '{{ addslashes($user->nama_user) }}'
                                                )"
                                            >
                                                ✕ Tolak
                                            </button>
                                        </form>

                                    </div>

                                @elseif($user->status_validasi === 'disetujui')

                                    <form
                                        method="POST"
                                        action="{{ route('admin.verifikasi.user.destroy', $user->id_user) }}"
                                        class="form-hapus-user"
                                        style="display:inline-block;"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="button"
                                            class="btn-delete"
                                            onclick="bukaModalHapusUser(
                                                this,
                                                '{{ addslashes($user->nama_user) }}'
                                            )"
                                        >
                                            Hapus
                                        </button>
                                    </form>

                                @else
                                    <span>-</span>
                                @endif
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center;">
                                Belum ada pengajuan akun user.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL SETUJUI / TOLAK --}}
<div id="modalVerifikasi" class="modal-hapus">
    <div class="modal-box">

        <div id="modalVerifikasiIcon" class="modal-icon">
            ⚠️
        </div>

        <h2 id="modalVerifikasiJudul">
            Konfirmasi
        </h2>

        <p id="modalVerifikasiPesan">
            Apakah Anda yakin?
        </p>

        <div class="modal-actions">
            <button
                type="button"
                class="btn-batal-modal"
                onclick="tutupModalVerifikasi()"
            >
                Batal
            </button>

            <button
                type="button"
                id="btnKonfirmasiVerifikasi"
                class="btn-konfirmasi-modal"
                onclick="submitVerifikasi()"
            >
                Ya
            </button>
        </div>
    </div>
</div>

{{-- MODAL HAPUS USER --}}
<div id="modalHapusUser" class="modal-hapus">
    <div class="modal-box">

        <div class="modal-icon hapus-user-icon">
            🗑️
        </div>

        <h2>
            Hapus Akun Karyawan
        </h2>

        <p id="pesanHapusUser">
            Apakah Anda yakin ingin menghapus akun ini?
        </p>

        <div class="modal-warning-text">
            Akun yang dihapus tidak dapat digunakan untuk login kembali.
        </div>

        <div class="modal-actions">
            <button
                type="button"
                class="btn-batal-modal"
                onclick="tutupModalHapusUser()"
            >
                Batal
            </button>

            <button
                type="button"
                class="btn-konfirmasi-hapus-user"
                onclick="submitHapusUser()"
            >
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
                Ya, Keluar
            </button>

        </div>

    </div>

</div>

<script>
let formVerifikasiAktif = null;
let formHapusUserAktif = null;

function cariUser() {
    const input = document
        .getElementById('searchUser')
        .value
        .toLowerCase();

    const rows = document.querySelectorAll(
        '#tabelUser tbody tr'
    );

    rows.forEach(row => {
        const cocok = row.innerText
            .toLowerCase()
            .includes(input);

        row.style.display = cocok ? '' : 'none';
    });
}

function bukaModalVerifikasi(button, aksi, namaUser) {
    formVerifikasiAktif = button.closest('form');

    const modal = document.getElementById('modalVerifikasi');
    const judul = document.getElementById('modalVerifikasiJudul');
    const pesan = document.getElementById('modalVerifikasiPesan');
    const icon = document.getElementById('modalVerifikasiIcon');
    const tombol = document.getElementById('btnKonfirmasiVerifikasi');

    if (aksi === 'setujui') {
        icon.textContent = '✅';
        judul.textContent = 'Setujui Akun';

        pesan.textContent =
            'Yakin ingin menyetujui akun ' + namaUser + '?';

        tombol.textContent = 'Ya, Setujui';
        tombol.style.background = '#16a34a';
    } else {
        icon.textContent = '⚠️';
        judul.textContent = 'Tolak Akun';

        pesan.textContent =
            'Yakin ingin menolak dan menghapus akun ' +
            namaUser + '?';

        tombol.textContent = 'Ya, Tolak';
        tombol.style.background = '#dc2626';
    }

    modal.style.display = 'flex';
}

function tutupModalVerifikasi() {
    formVerifikasiAktif = null;

    document
        .getElementById('modalVerifikasi')
        .style
        .display = 'none';
}

function submitVerifikasi() {
    if (formVerifikasiAktif) {
        formVerifikasiAktif.submit();
    }
}

function bukaModalHapusUser(button, namaUser) {
    formHapusUserAktif = button.closest('form');

    document
        .getElementById('pesanHapusUser')
        .textContent =
            'Apakah Anda yakin ingin menghapus akun ' +
            namaUser +
            ' karena sudah resign atau tidak bekerja lagi?';

    document
        .getElementById('modalHapusUser')
        .style
        .display = 'flex';
}

function tutupModalHapusUser() {
    formHapusUserAktif = null;

    document
        .getElementById('modalHapusUser')
        .style
        .display = 'none';
}

function submitHapusUser() {
    if (formHapusUserAktif) {
        formHapusUserAktif.submit();
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

window.onclick=function(event){

    let modal=document.getElementById("modalLogout");

    if(event.target==modal){

        tutupModalLogout();

    }

}

window.addEventListener('click', function(event) {
    const modalVerifikasi =
        document.getElementById('modalVerifikasi');

    const modalHapusUser =
        document.getElementById('modalHapusUser');

    if (event.target === modalVerifikasi) {
        tutupModalVerifikasi();
    }

    if (event.target === modalHapusUser) {
        tutupModalHapusUser();
    }
});
</script>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Material Masuk Admin</title>
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
    <div class="form-card">
        <div class="form-header">
            <div class="form-icon">✏️</div>
            <div>
                <h2>Edit Material Masuk Admin</h2>
                <p>Perbarui data material masuk pada form di bawah ini.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.material.masuk.update', $transaksi->id_transaksi) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label>
                        Tanggal <span style="color:red;">*</span>
                    </label>
                    <input type="date" name="tanggal" value="{{ \Carbon\Carbon::parse($transaksi->tgl_transaksi)->format('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label>
                        No Surat Jalan <span style="color:red;">*</span>
                    </label>
                    <input type="text" name="no_bukti" value="{{ $transaksi->no_bukti }}" required>
                </div>

                <div class="form-group">
                    <label>Area <span class="required">*</span></label>

                    <select name="id_area" required>
                        @foreach($areas as $area)
                            <option
                                value="{{ $area->id_area }}"
                                {{ $transaksi->id_area == $area->id_area ? 'selected' : '' }}>
                                {{ $area->nama_area }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>
                        Project <span style="color:red;">*</span>
                    </label>
                    <select name="project" required>
                        <option value="">
                            -- Pilih Project --
                        </option>
                        @foreach($projects as $project)
                            <option
                                value="{{ $project->project }}"
                                {{ $transaksi->project == $project->project ? 'selected' : '' }}>

                                {{ $project->project }}
                            </option>
                        @endforeach

                    </select>

                </div>

                <div class="form-group">
                    <label>
                        Material <span style="color:red;">*</span>
                    </label>
                    <select name="id_material" class="select-material" required onchange="isiSatuan(this)">
                        <option value="">-- Pilih Material --</option>

                        @foreach($materials as $material)
                            <option
                                value="{{ $material->id_material }}"
                                data-satuan="{{ $material->satuan }}"
                                {{ $transaksi->id_material == $material->id_material ? 'selected' : '' }}>
                                [{{ $material->kode_material }}] {{ $material->nama_material }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>
                        Jumlah <span style="color:red;">*</span>
                    </label>
                    <input type="number" name="jumlah" min="1" value="{{ $transaksi->jumlah }}" required>
                </div>

                <div class="form-group">
                    <label>Satuan</label>
                    <input type="text" class="satuan-field" value="{{ $transaksi->material->satuan ?? '-' }}" readonly>
                </div>

                <div class="form-group">
                    <label>Keterangan</label>
                    <input type="text" name="keterangan" value="{{ $transaksi->keterangan }}" placeholder="Opsional">
                </div>

                <div class="form-group full">
                <label>Dokumen / Foto Saat Ini</label>
                <table class="material-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama File</th>
                            <th>Keterangan</th>
                            <th>Lihat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi->dokumentasiTransaksi as $dok)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ basename($dok->file_dokumentasi) }}</td>
                                <td>{{ $dok->keterangan ?: '-' }}</td>
                                <td>
                                    <a href="{{ asset('storage/'.$dok->file_dokumentasi) }}"
                                    target="_blank"
                                    class="btn-lihat">
                                        Lihat
                                    </a>
                                </td>

                                <td>
                                    <form
                                        id="hapusDokumen{{ $dok->id_dokumentasi }}"
                                        action="{{ route('admin.material.masuk.dokumen.destroy', $dok->id_dokumentasi) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="button"
                                            class="btn-delete"
                                            onclick="bukaModalHapusDokumen(this)">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center">
                                    Tidak ada dokumen.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="form-group">
                <label>
                    Tambah Foto Dokumentasi Baru
                </label>
                <input
                    type="file"
                    name="foto_dokumentasi[]"
                    multiple
                    accept=".jpg,.jpeg,.png">
                <small>(JPEG / PNG)</small>
            </div>
            <div class="form-group">
                <label>
                    Tambah Dokumen Surat Jalan
                </label>
                <input
                    type="file"
                    name="dokumen[]"
                    multiple
                    accept=".pdf,.doc,.docx">
                <small>(PDF / DOC / DOCX)</small>
            </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.material.masuk') }}" class="btn-kembali">← Kembali</a>
                <div>
                    <button type="reset" class="btn-reset">Reset</button>
                    <button type="submit" class="btn-update">✓ Update</button>
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
function isiSatuan(select) {
    let selected = select.options[select.selectedIndex];
    let satuan = selected.getAttribute('data-satuan') || '';

    let card = select.closest('.form-grid');
    card.querySelector('.satuan-field').value = satuan;
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

window.onclick = function(event){

    let modal = document.getElementById("modalLogout");

    if(event.target == modal){
        tutupModalLogout();
    }

}

let formDokumen = null;

function bukaModalHapusDokumen(button){

    formDokumen = button.closest("form");

    document.getElementById("modalHapusDokumen").style.display = "flex";
}

function tutupModalHapusDokumen(){

    formDokumen = null;

    document.getElementById("modalHapusDokumen").style.display = "none";
}

function submitHapusDokumen(){

    if(formDokumen){
        formDokumen.submit();
    }

}

window.addEventListener("click", function(e){

    const modal = document.getElementById("modalHapusDokumen");

    if(e.target === modal){
        tutupModalHapusDokumen();
    }

});

</script>
<div id="modalHapusDokumen" class="modal-hapus">

    <div class="modal-box">

        <div class="modal-icon">⚠️</div>

        <h2>Konfirmasi Hapus</h2>

        <p>
            Apakah Anda yakin ingin menghapus dokumen ini?
        </p>

        <div class="modal-actions">

            <button
                type="button"
                class="btn-batal-modal"
                onclick="tutupModalHapusDokumen()">
                Batal
            </button>

            <button
                type="button"
                class="btn-konfirmasi-modal"
                onclick="submitHapusDokumen()">
                Ya, Hapus
            </button>

        </div>

    </div>

</div>
</body>
</html>
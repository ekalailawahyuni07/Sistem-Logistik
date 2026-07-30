<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Material Masuk</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .form-header h1,
        .form-header h2,
        .form-header p,
        .form-group label,
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
        <a href="{{ route('material.masuk') }}" class="active">Material Masuk</a>
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
            <div class="form-icon">✏️</div>
            <div>
                <h2>Edit Material Masuk</h2>
                <p>Perbarui data material masuk pada form di bawah ini.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('material.masuk.update', $transaksi->id_transaksi) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label>
                        Tanggal <span style="color:red;">*</span>
                    </label>
                    <input
                        type="date"
                        name="tanggal"
                        value="{{ \Carbon\Carbon::parse($transaksi->tgl_transaksi)->format('Y-m-d') }}"
                        required>
                </div>

                <div class="form-group">
                    <label>
                        No Surat Jalan <span style="color:red;">*</span>
                    </label>
                    <input
                        type="text"
                        name="no_bukti"
                        value="{{ $transaksi->no_bukti }}"
                        required>
                </div>

                <div class="form-group">
                    <label>
                        Project <span style="color:red;">*</span>
                    </label>
                    <select name="project" required>
                        <option value="">-- Pilih Project --</option>
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
                    <select
                        name="id_material"
                        class="select-material"
                        required
                        onchange="isiSatuan(this)">
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
                    <input
                        type="number"
                        name="jumlah"
                        min="1"
                        value="{{ $transaksi->jumlah }}"
                        required>
                </div>

                <div class="form-group">
                    <label>Satuan</label>
                    <input
                        type="text"
                        class="satuan-field"
                        value="{{ $transaksi->material->satuan ?? '-' }}"
                        readonly>
                </div>

                <div class="form-group full">
                    <label>Keterangan</label>
                    <input
                        type="text"
                        name="keterangan"
                        value="{{ $transaksi->keterangan }}"
                        placeholder="Opsional">
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
                                            action="{{ route('material.masuk.dokumen.destroy', $dok->id_dokumentasi) }}"
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
                    <label>Tambah Foto Dokumentasi Baru</label>
                    <input
                        type="file"
                        name="foto_dokumentasi[]"
                        multiple
                        accept=".jpg,.jpeg,.png">
                    <small style="color:#6b7280;">(JPEG / PNG)</small>
                </div>

                <div class="form-group">
                    <label>Tambah Dokumen Surat Jalan</label>
                    <input
                        type="file"
                        name="dokumen[]"
                        multiple
                        accept=".pdf,.doc,.docx">
                    <small style="color:#6b7280;">(PDF / DOC / DOCX)</small>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('material.masuk') }}" class="btn-kembali">← Kembali</a>
                <div>
                    <button type="reset" class="btn-reset">Reset</button>
                    <button type="submit" class="btn-update">✓ Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="modalHapus" class="modal-hapus">
    <div class="modal-box">
        <div class="modal-icon">⚠️</div>
        <h2>Konfirmasi Hapus</h2>
        <p>Apakah Anda yakin ingin menghapus dokumen ini?</p>
        <div class="modal-actions">
            <button type="button" class="btn-batal-modal" onclick="tutupModalHapus()">Batal</button>
            <button type="button" class="btn-konfirmasi-modal" onclick="submitHapusDokumen()">Ya, Hapus</button>
        </div>
    </div>
</div>

<div id="modalLogout" class="modal-hapus">
    <div class="modal-box">
        <div class="modal-icon logout-icon">🚪</div>
        <h2>Keluar</h2>
        <p>Apakah Anda yakin ingin keluar dari sistem?</p>
        <div class="modal-warning-text">
            Anda harus masuk kembali untuk mengakses sistem.
        </div>
        <div class="modal-actions">
            <button type="button" class="btn-batal-modal" onclick="tutupModalLogout()">Batal</button>
            <button type="button" class="btn-logout-modal" onclick="submitLogout()">Ya, Keluar</button>
        </div>
    </div>
</div>

<script>
let formHapusTarget = null;

function bukaModalHapusDokumen(btn) {
    formHapusTarget = btn.closest('form');
    document.getElementById('modalHapus').style.display = 'flex';
}

function tutupModalHapus() {
    document.getElementById('modalHapus').style.display = 'none';
    formHapusTarget = null;
}

function submitHapusDokumen() {
    if (formHapusTarget) {
        formHapusTarget.submit();
    }
}

function isiSatuan(select) {
    let selectedOption = select.options[select.selectedIndex];
    let satuan = selectedOption.getAttribute('data-satuan');
    let formGroup = select.closest('.form-grid');
    let satuanInput = formGroup.querySelector('.satuan-field');
    if (satuanInput) {
        satuanInput.value = satuan ? satuan : '-';
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
    let modalLogout = document.getElementById("modalLogout");
    let modalHapus = document.getElementById("modalHapus");
    if(event.target == modalLogout){
        tutupModalLogout();
    }
    if(event.target == modalHapus){
        tutupModalHapus();
    }
}
</script>

</body>
</html>
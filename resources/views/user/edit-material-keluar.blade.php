<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Material Keluar</title>
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
        <a href="{{ route('material.masuk') }}">Material Masuk</a>
        <a href="{{ route('material.keluar') }}" class="active">Material Keluar</a>
        <a href="{{ route('stok.material') }}">Stok Material</a>
        <a href="{{ route('cluster') }}">Daftar Kluster</a>
        <a href="{{ route('dokumen') }}">Dokumen</a>
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
                <h2>Edit Material Keluar</h2>
                <p>Perbarui data material keluar pada form di bawah ini.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('material.keluar.update', $transaksi->id_transaksi) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label>Tanggal <span style="color:red;">*</span></label>
                    <input
                        type="date"
                        name="tanggal"
                        value="{{ \Carbon\Carbon::parse($transaksi->tgl_transaksi)->format('Y-m-d') }}"
                        required>
                </div>

                <div class="form-group">
                    <label>No Surat Jalan <span style="color:red;">*</span></label>
                    <input
                        type="text"
                        name="no_bukti"
                        value="{{ $transaksi->no_bukti }}"
                        required>
                </div>

                <div class="form-group">
                    <label>Kluster <span style="color:red;">*</span></label>
                    <select name="id_cluster" required>
                        @foreach($clusters as $cluster)
                            <option
                                value="{{ $cluster->id_cluster }}"
                                {{ $transaksi->id_cluster == $cluster->id_cluster ? 'selected' : '' }}>
                                {{ $cluster->nama_cluster }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Project <span style="color:red;">*</span></label>
                    <select name="project" required>
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
                    <label>Nama Penerima <span style="color:red;">*</span></label>
                    <input
                        type="text"
                        name="nama_penerima"
                        value="{{ $transaksi->nama_penerima }}"
                        required>
                </div>

                <div class="form-group">
                    <label>Yang Menerima <span style="color:red;">*</span></label>
                    <input
                        type="text"
                        name="nama_sopir"
                        value="{{ $transaksi->nama_sopir }}"
                        required>
                </div>

                <div class="form-group">
                    <label>No HP Penerima <span style="color:red;">*</span></label>
                    <input
                        type="text"
                        name="no_hp"
                        value="{{ $transaksi->no_hp }}"
                        minlength="11"
                        maxlength="13"
                        required>
                </div>

                <div class="form-group">
                    <label>Kendaraan <span style="color:red;">*</span></label>
                    <input
                        type="text"
                        name="kendaraan"
                        value="{{ $transaksi->kendaraan }}"
                        required>
                </div>

                <div class="form-group">
                    <label>Plat Nomor <span style="color:red;">*</span></label>
                    <input
                        type="text"
                        name="plat_nomor"
                        value="{{ $transaksi->plat_nomor }}"
                        required>
                </div>
            </div>

            <div class="detail-material-card" style="margin-top: 25px;">
                <h3 style="color: #000000 !important; font-size: 18px; font-weight: 700; margin-bottom: 15px;">Detail Material Keluar</h3>

                <table class="material-table">
                    <thead>
                        <tr>
                            <th>Material <span style="color:red">*</span></th>
                            <th width="170">Jumlah</th>
                            <th width="170">Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select
                                    name="id_material"
                                    id="id_material"
                                    onchange="isiSatuan()"
                                    required>
                                    @foreach($materials as $material)
                                        <option
                                            value="{{ $material->id_material }}"
                                            data-satuan="{{ $material->satuan }}"
                                            {{ $transaksi->id_material == $material->id_material ? 'selected' : '' }}>
                                            [{{ $material->kode_material }}] {{ $material->nama_material }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input
                                    type="number"
                                    name="jumlah"
                                    min="1"
                                    value="{{ $transaksi->jumlah }}"
                                    required>
                            </td>
                            <td>
                                <input
                                    type="text"
                                    id="satuan"
                                    value="{{ $transaksi->material->satuan ?? '-' }}"
                                    readonly>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="form-group full" style="margin-top: 20px;">
                <label>Keterangan</label>
                <textarea name="keterangan" rows="3" placeholder="Opsional">{{ $transaksi->keterangan }}</textarea>
            </div>

            <div class="form-group full" style="margin-top: 20px;">
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
                                        action="{{ route('material.keluar.dokumen.destroy', $dok->id_dokumentasi) }}"
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

            <div class="form-group full" style="margin-top:20px;">
                <label>Upload Foto Dokumentasi Pengeluaran Material Baru</label>
                <input
                    type="file"
                    name="dokumen[]"
                    multiple
                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <small style="color:#6b7280; font-size:13px;">(Opsional - PDF / DOC / DOCX / JPG / PNG)</small>
            </div>

            <div class="form-actions" style="margin-top: 30px;">
                <a href="{{ route('material.keluar') }}" class="btn-kembali">← Kembali</a>
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

function isiSatuan() {
    let select = document.getElementById('id_material');
    let selected = select.options[select.selectedIndex];
    if (selected) {
        document.getElementById('satuan').value = selected.getAttribute('data-satuan') || '';
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
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Material Masuk</title>
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
        <a href="{{ route('material.masuk') }}" class="active">Material Masuk</a>
        <a href="{{ route('material.keluar') }}">Material Keluar</a>
        <a href="{{ route('stok.material') }}">Stok Material</a>
        <a href="{{ route('cluster') }}">Daftar Kluster</a>
        <a href="{{ route('dokumen') }}">Daftar Dokumen</a>
        <a href="{{ route('surat.jalan') }}">Surat Jalan</a>
    </div>

    <div class="logout">
        <form method="POST"
            action="{{ route('material.masuk.update', $transaksi->id_transaksi) }}"
            enctype="multipart/form-data">
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

    <div class="form-card">

        <div class="form-header">
            <div class="form-icon">✏️</div>

            <div>
                <h2>Edit Material Masuk</h2>
                <p>Perbarui data material masuk pada form di bawah ini.</p>
            </div>
        </div>


        <form method="POST"
              action="{{ route('material.masuk.update', $transaksi->id_transaksi) }}">

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

                    <select
                        name="id_material"
                        class="select-material"
                        required
                        onchange="isiSatuan(this)">

                        <option value="">
                            -- Pilih Material --
                        </option>

                        @foreach($materials as $material)
                            <option
                                value="{{ $material->id_material }}"
                                data-satuan="{{ $material->satuan }}"
                                {{ $transaksi->id_material == $material->id_material ? 'selected' : '' }}>

                                [{{ $material->kode_material }}]
                                {{ $material->nama_material }}

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
                        value="{{ $transaksi->material->satuan ?? '' }}"
                        readonly>
                </div>


                <div class="form-group">
                    <label>Keterangan</label>

                    <input
                        type="text"
                        name="keterangan"
                        value="{{ $transaksi->keterangan }}"
                        placeholder="Opsional">
                </div>

                <div class="form-group">
                    <label>Foto Dokumentasi</label>

                    <label class="upload-box">
                        <span class="upload-icon">📷</span>

                        <span class="upload-info">
                            <strong>Pilih Foto Dokumentasi</strong>
                            <small>JPG, JPEG, PNG</small>
                        </span>

                        <span class="upload-button">Pilih File</span>

                        <input
                            type="file"
                            name="foto_dokumentasi[]"
                            accept="image/*"
                            multiple
                            onchange="tampilkanNamaFile(this)"
                        >
                    </label>

                    <div class="file-name">Belum ada file baru dipilih</div>

                    @if($transaksi->dokumentasi && $transaksi->dokumentasi->count())
                        @foreach($transaksi->dokumentasi as $dok)
                            @if(str_starts_with($dok->file_dokumentasi, 'foto-dokumentasi/'))
                                <div class="existing-file">
                                    <span>📷 Foto tersimpan</span>

                                    <a
                                        href="{{ asset('storage/' . $dok->file_dokumentasi) }}"
                                        target="_blank">
                                        Lihat Foto
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>


                <div class="form-group">
                    <label>Dokumen</label>

                    <label class="upload-box">
                        <span class="upload-icon">📄</span>

                        <span class="upload-info">
                            <strong>Pilih Dokumen</strong>
                            <small>PDF atau dokumen pendukung</small>
                        </span>

                        <span class="upload-button">Pilih File</span>

                        <input
                            type="file"
                            name="dokumen[]"
                            multiple
                            onchange="tampilkanNamaFile(this)"
                        >
                    </label>

                    <div class="file-name">Belum ada file baru dipilih</div>

                    @if($transaksi->dokumentasi && $transaksi->dokumentasi->count())
                        @foreach($transaksi->dokumentasi as $dok)
                            @if(str_starts_with($dok->file_dokumentasi, 'dokumen-transaksi/'))
                                <div class="existing-file">
                                    <span>📄 Dokumen tersimpan</span>

                                    <a
                                        href="{{ asset('storage/' . $dok->file_dokumentasi) }}"
                                        target="_blank">
                                        Lihat Dokumen
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>


            <div class="form-actions">

                <a href="{{ route('material.masuk') }}"
                   class="btn-kembali">
                    ← Kembali
                </a>

                <div>
                    <button
                        type="reset"
                        class="btn-reset">
                        Reset
                    </button>

                    <button
                        type="submit"
                        class="btn-update">
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
function isiSatuan(select) {
    const selected = select.options[select.selectedIndex];
    const satuan = selected.getAttribute('data-satuan') || '';

    document.querySelector('.satuan-field').value = satuan;
}

function bukaModalLogout() {
    document.getElementById('modalLogout').style.display = 'flex';
}

function tutupModalLogout() {
    document.getElementById('modalLogout').style.display = 'none';
}

function submitLogout() {
    document.getElementById('logoutForm').submit();
}

window.onclick = function(event) {
    const modal = document.getElementById('modalLogout');

    if (event.target === modal) {
        tutupModalLogout();
    }
};
</script>

<script>
function tampilkanNamaFile(input) {

    const container = input.closest('.form-group');
    const fileName = container.querySelector('.file-name');

    if (!input.files.length) {
        fileName.textContent = 'Belum ada file baru dipilih';
        return;
    }

    if (input.files.length === 1) {
        fileName.textContent = '✓ ' + input.files[0].name;
    } else {
        fileName.textContent = '✓ ' + input.files.length + ' file dipilih';
    }
}
</script>

</body>
</html>
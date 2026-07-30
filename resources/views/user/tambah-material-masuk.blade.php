<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Material Masuk</title>
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
        <div class="avatar">👤</div>
        <h4>{{ Auth::user()->nama_user }}</h4>
        <p>{{ Auth::user()->email }}</p>
    </div>

    <div class="menu">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('material.masuk') }}">Master Data Material</a>
        <a href="{{ route('material.masuk') }}"class="{{ request()->routeIs('material.masuk') || request()->routeIs('material.masuk.create') || request()->routeIs('material.masuk.edit') ? 'active' : '' }}">Material Masuk</a>
        <a href="{{ route('material.keluar') }}">Material Keluar</a>
        <a href="{{ route('stok.material') }}">Stok Material</a>
        <a href="{{ route('cluster') }}">Daftar Kluster</a>
        <a href="{{ route('dokumen') }}">Daftar Dokumen</a>
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
            <div class="form-icon">📥</div>
            <div>
                <h2>Tambah Material Masuk</h2>
                <p>Isi tanggal dan no bukti, lalu tambahkan beberapa material yang masuk.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('material.masuk.store') }}" enctype="multipart/form-data">
            @csrf

        <div class="form-grid">

            <div class="form-group">
                <label>
                    Tanggal <span style="color:red;">*</span>
                </label>

                <input
                    type="date"
                    name="tanggal"
                    required>
            </div>

            <div class="form-group">
                <label>
                    No Surat Jalan <span style="color:red;">*</span>
                </label>

                <input
                    type="text"
                    name="no_bukti"
                    placeholder="Contoh: 1654/EMR/NRO-GDR/11/2025"
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

                        <option value="{{ $project->project }}">
                            {{ $project->project }}
                        </option>

                    @endforeach

                </select>

            </div>

        </div>
            <div class="material-detail">
                <div class="detail-header">
                    <h3>Detail Material Masuk</h3>
                    <button type="button" class="btn-add-row" onclick="tambahBaris()">+ Tambah Baris</button>
                </div>

                <table class="material-table">
                    <thead>
                        <tr>
                            <th style="width:35%;">
                                Material <span style="color:red;">*</span>
                            </th>

                            <th style="width:15%;">
                                Jumlah <span style="color:red;">*</span>
                            </th>

                            <th style="width:15%;">
                                Satuan
                            </th>

                            <th>Keterangan</th>

                            <th style="width:10%;">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody id="materialBody">
                        <tr>
                            <td>
                                <select name="id_material[]" class="select-material" onchange="isiSatuan(this)" required>
                                    <option value="">-- Pilih Material --</option>
                                    @foreach($materials as $material)
                                        <option 
                                            value="{{ $material->id_material }}"
                                            data-satuan="{{ $material->satuan }}">
                                            [{{ $material->kode_material }}] {{ $material->nama_material }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="number" name="jumlah[]" min="1" placeholder="Masukkan jumlah" required>
                            </td>

                            <td>
                                <input type="text" class="satuan-field" readonly>
                            </td>

                            <td>
                                <input type="text" name="keterangan[]" placeholder="Opsional">
                            </td>

                            <td style="text-align:center;">
                                <button type="button" class="btn-hapus-row" onclick="hapusBaris(this)">Hapus</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="form-grid" style="margin: 20px 28px;">
                <div class="form-group">

                    <label>Upload Foto Dokumentasi<span style="color:red;">*</span></label>

                    <input
                        type="file"
                        name="foto_dokumentasi[]"
                        multiple
                        accept=".jpg,.jpeg,.png"
                        required
                    >

                    <small style="color:#6b7280;font-size:13px;">
                        (JPEG / PNG)
                    </small>

                </div>

                <div class="form-group">

                    <label>Upload Dokumen <span style="color:red;">*</span></label>

                    <input
                        type="file"
                        name="dokumen[]"
                        multiple
                        accept=".pdf,.doc,.docx"
                        required
                    >

                    <small style="color:#6b7280;font-size:13px;">
                        (Surat Jalan)
                    </small>

                </div>
            </div>
            
            <div class="form-actions">
                <a href="{{ route('material.masuk') }}" class="btn-kembali">← Kembali</a>

                <div>
                    <button type="reset" class="btn-reset">Reset</button>
                    <button type="submit" class="btn-update">✓ Simpan Semua</button>
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
                Ya, Keluar
            </button>

        </div>

    </div>

</div>

<script>
    function isiSatuan(select) {
        let selected = select.options[select.selectedIndex];
        let satuan = selected.getAttribute('data-satuan') || '';

        let row = select.closest('tr');
        row.querySelector('.satuan-field').value = satuan;
    }

    function tambahBaris() {
        let tbody = document.getElementById('materialBody');
        let barisPertama = tbody.querySelector('tr');
        let barisBaru = barisPertama.cloneNode(true);

        barisBaru.querySelectorAll('input').forEach(input => {
            input.value = '';
        });

        barisBaru.querySelector('select').selectedIndex = 0;

        tbody.appendChild(barisBaru);
    }

    function hapusBaris(button) {
        let tbody = document.getElementById('materialBody');

        if (tbody.rows.length > 1) {
            button.closest('tr').remove();
        } else {
            alert('Minimal harus ada 1 baris material.');
        }
    }
</script>
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
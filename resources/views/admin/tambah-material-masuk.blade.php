<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Material Masuk Admin</title>
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
            <div class="form-icon">📥</div>
            <div>
                <h2>Tambah Material Masuk</h2>
                <p>Isi informasi penerimaan material secara lengkap sebelum menyimpan data ke dalam sistem</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.material.masuk.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label>
                        Tanggal <span style="color:red;">*</span>
                    </label>
                    <input type="date" name="tanggal" required>
                </div>

                <div class="form-group">
                    <label>Area <span class="required">*</span></label>
                    <select name="id_area" required>
                        <option value="">-- Pilih Area --</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id_area }}">
                                {{ $area->nama_area }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>
                        No Surat Jalan <span style="color:red;">*</span>
                    </label>
                    <input type="text" name="no_bukti" placeholder="Contoh: 1654/EMR/NRO-GDR/11/2025" required>
                </div>

                <div class="form-group">
                    <label>
                        Project <span style="color:red;">*</span>
                    </label>

                    <select name="project" id="projectSelect" required>
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
                            <th style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="materialBody">
                        <tr>
                            <td>
                                <select
                                    name="id_material[]"
                                    class="select-material material-select"
                                    onchange="isiSatuan(this)"
                                    required>
                                    <option value="">-- Pilih Material --</option>
                                    @foreach($materials as $material)
                                        <option
                                            value="{{ $material->id_material }}"
                                            data-project="{{ $material->project }}"
                                            data-satuan="{{ $material->satuan }}">

                                            [{{ $material->kode_material }}]
                                            {{ $material->nama_material }}
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
                                <button type="button" class="btn-hapus-row" onclick="hapusBaris(this)">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="form-grid" style="margin: 20px 28px;">
                <div class="form-group">
                    <label>
                        Upload Foto Dokumentasi Penerimaan Material<span style="color:red;">*</span>
                    </label>

                    <input
                        type="file"
                        name="foto_dokumentasi[]"
                        multiple
                        accept=".jpg,.jpeg,.png"
                        required>

                    <small style="color:#6b7280;font-size:13px;">
                        (JPEG / PNG)
                    </small>
                </div>

                <div class="form-group">
                    <label>
                        Upload Dokumen Surat Jalan<span style="color:red;">*</span>
                    </label>

                    <input
                        type="file"
                        name="dokumen[]"
                        multiple
                        accept=".pdf,.doc,.docx"
                        required>

                    <small style="color:#6b7280;font-size:13px;">
                        (Surat Jalan)
                    </small>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.material.masuk') }}" class="btn-kembali">
                    ← Kembali
                </a>

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

<div id="notifBaris" class="notif-baris">
    Minimal harus ada 1 baris material.
</div>

<script>
let semuaMaterial = [];

window.onload = function () {

    const select = document.querySelector('.material-select');

    semuaMaterial = Array.from(select.options);

    document
        .getElementById('projectSelect')
        .addEventListener('change', filterMaterial);

} 

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

    let select = barisBaru.querySelector(".material-select");

    select.selectedIndex = 0;

    tbody.appendChild(barisBaru);

    filterMaterial();

}

function hapusBaris(button) {

    const tbody = document.getElementById('materialBody');

    if (tbody.rows.length <= 1) {

        tampilPesan(
            'Minimal harus ada 1 baris material.',
            '#dc3545'
        );

        return;
    }

    button.closest('tr').remove();
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

function tampilPesan(text, warna="#dc3545"){

    const notif=document.getElementById("notifBaris");

    notif.innerHTML=text;

    notif.style.background=warna;

    notif.style.display="block";

    setTimeout(function(){

        notif.style.display="none";

    },2500);

}

function filterMaterial(){

    let project =
        document.getElementById("projectSelect").value;

    document.querySelectorAll(".material-select").forEach(function(select){

        let valueSekarang = select.value;

        select.innerHTML = "";

        let awal = document.createElement("option");
        awal.value = "";
        awal.text = "-- Pilih Material --";
        select.appendChild(awal);

        semuaMaterial.forEach(function(opt){

            if(opt.value=="") return;

            if(project=="" || opt.dataset.project==project){

                select.appendChild(opt.cloneNode(true));

            }

        });

        select.value = valueSekarang;

    });

}
</script>
</body>
</html>
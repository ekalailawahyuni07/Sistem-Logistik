<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Material Keluar Admin</title>
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
        <a href="{{ route('admin.material.masuk') }}">Material Masuk</a>
        <a href="{{ route('admin.material.keluar') }}" class="active">Material Keluar</a>
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
    @if(session('error'))
        <div class="alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="form-card">
        <div class="form-header">
            <div class="form-icon">📤</div>
            <div>
                <h2>Tambah Material Keluar Admin</h2>
                <p>Isi tanggal dan no bukti, lalu tambahkan beberapa material yang keluar.</p>
            </div>
        </div>

        <form method="POST"
            action="{{ route('admin.material.keluar.store') }}"
            enctype="multipart/form-data">

            @csrf

            <div class="form-grid">

                {{-- Tanggal --}}
                <div class="form-group">
                    <label>Tanggal <span style="color:red">*</span></label>
                    <input
                    type="date"
                    name="tanggal"
                    value="{{ old('tanggal') }}"
                    required>
                </div>

                {{-- No Surat Jalan --}}
                <div class="form-group">
                    <label>No Surat Jalan <span style="color:red">*</span></label>
                    <input
                    type="text"
                    name="no_bukti"
                    value="{{ old('no_bukti') }}"
                    placeholder="Masukkan no bukti / surat jalan"
                    required>
                </div>

                {{--Area--}}
                <div class="form-group">
                    <label>Area <span class="required">*</span></label>

                    <select
                        name="id_area"
                        id="id_area"
                        required>

                        <option value="">-- Pilih Area --</option>

                        @foreach($areas as $area)
                            <option
                                value="{{ $area->id_area }}"
                                {{ old('id_area') == $area->id_area ? 'selected' : '' }}>
                                {{ $area->nama_area }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- Cluster --}}
                <div class="form-group">
                    <label>Cluster <span style="color:red">*</span></label>

                    <select
                        name="id_cluster"
                        id="id_cluster"
                        required>

                        <option value="">-- Pilih Cluster --</option>

                    </select>

                </div>

                {{-- Project --}}
                <div class="form-group">

                    <label>Project <span style="color:red">*</span></label>

                    <select name="project" id="project" required>
                        <option value="">-- Pilih Project --</option>
                    </select>

                </div>

                {{-- Nama Penerima --}}
                <div class="form-group">

                    <label>Nama Penerima <span style="color:red">*</span></label>

                    <input
                        type="text"
                        name="nama_penerima"
                        value="{{ old('nama_penerima') }}"
                        placeholder="Masukkan nama penerima"
                        required>

                </div>

                {{-- Yang Menerima --}}
                <div class="form-group">

                    <label>Yang Menerima <span style="color:red">*</span></label>

                    <input
                        type="text"
                        name="nama_sopir"
                        value="{{ old('nama_sopir') }}"
                        placeholder="Masukkan nama yang menerima"
                        required>

                </div>

                {{-- No HP --}}
                <div class="form-group">
                    <label>
                        No HP Penerima <span style="color:red">*</span>
                    </label>

                    <input
                        type="text"
                        id="no_hp"
                        name="no_hp"
                        value="{{ old('no_hp') }}"
                        placeholder="Masukkan nomor HP"
                        minlength="11"
                        maxlength="13"
                        inputmode="numeric"
                        oninput="validasiNoHp(this)"
                        required>
                </div>

                {{-- Kendaraan --}}
                <div class="form-group">

                    <label>Kendaraan <span style="color:red">*</span></label>

                    <input
                        type="text"
                        name="kendaraan"
                        value="{{ old('kendaraan') }}"
                        placeholder="Contoh : Pickup L300"
                        required>
                </div>

                {{-- Plat Nomor --}}
                <div class="form-group">
                    <label>Plat Nomor <span style="color:red">*</span></label>
                    <input
                        type="text"
                        name="plat_nomor"
                        value="{{ old('plat_nomor') }}"
                        placeholder="KB 1234 XX"
                        required>
                </div>

            </div>

            {{-- ============================= --}}
            {{-- DETAIL MATERIAL --}}
            {{-- ============================= --}}

            <div class="material-detail">
                <div class="detail-header">
                    <h3>Detail Material Keluar</h3>
                    <button
                        type="button"
                        class="btn-add-row"
                        onclick="tambahBaris()">
                        + Tambah Baris
                    </button>
                </div>

                <table class="material-table">
                    <thead>
                        <tr>
                            <th style="width:45%">
                                Material <span style="color:red">*</span>
                            </th>

                            <th style="width:15%">
                                Jumlah
                            </th>

                            <th style="width:15%">
                                Satuan
                            </th>

                            <th style="width:10%">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody id="materialBody">
                        <tr>
                            <td>
                                <select
                                    name="id_material[]"
                                    class="select-material"
                                    onchange="isiSatuan(this)"
                                    required>
                                    <option value="">
                                        -- Pilih Material --
                                    </option>
                                    @foreach($materials as $material)
                                        <option
                                            value="{{ $material->id_material }}"
                                            data-satuan="{{ $material->satuan }}">

                                            [{{ $material->kode_material }}]
                                            {{ $material->nama_material }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input
                                    type="number"
                                    name="jumlah[]"
                                    min="1"
                                    placeholder="Jumlah"
                                    required>
                            </td>
                            <td>
                                <input
                                    type="text"
                                    class="satuan-field"
                                    readonly>
                            </td>
                            <td style="text-align:center;">
                                <button
                                    type="button"
                                    class="btn-hapus-row"
                                    onclick="hapusBaris(this)">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- ============================= --}}
            {{-- KETERANGAN --}}
            {{-- ============================= --}}

            <div class="form-group" style="margin-top:30px;">
                <label>Keterangan</label>
                <textarea
                    name="keterangan"
                    rows="4"
                    placeholder="Opsional">{{ old('keterangan') }}
                </textarea>
            </div>

            {{-- ============================= --}}
            {{-- FOTO --}}
            {{-- ============================= --}}

            <div class="upload-card">
                <h3>
                    Upload Foto Dokumentasi Pengeluaran Material
                    <span style="color:red">*</span>
                </h3>
                <input
                    type="file"
                    name="foto_dokumentasi[]"
                    multiple
                    accept=".jpg,.jpeg,.png"
                    required>
                <small>(JPG / PNG)</small>
            </div>

            {{-- ============================= --}}
            {{-- TOMBOL --}}
            {{-- ============================= --}}

            <div class="form-actions">
                <a
                    href="{{ route('admin.material.keluar') }}"
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

                        💾 Simpan Material Keluar

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

document.getElementById('id_area').addEventListener('change', function () {

    let idArea = this.value;

    fetch("{{ url('/admin/get-cluster') }}/" + idArea)
        .then(response => response.json())
        .then(data => {

            let cluster = document.getElementById('id_cluster');

            cluster.innerHTML = '<option value="">-- Pilih Cluster --</option>';

            data.forEach(function(item){

                cluster.innerHTML +=
                    `<option value="${item.id_cluster}">
                        ${item.nama_cluster}
                    </option>`;

            });

        });

    fetch("/admin/get-project/" + idArea)
    .then(response => response.json())
    .then(data => {

        let project = document.getElementById("project");

        project.innerHTML =
            '<option value="">-- Pilih Project --</option>';

        data.forEach(function(item){

            project.innerHTML += `
                <option value="${item.project}">
                    ${item.project}
                </option>
            `;

        });

    });

});

document.getElementById('project').addEventListener('change', function () {

    let idArea    = document.getElementById('id_area').value;
    let project   = this.value;

    fetch("/admin/get-material/" + idArea + "/" + project)
        .then(response => response.json())
        .then(data => {

            document.querySelectorAll(".select-material").forEach(function(select){

                select.innerHTML =
                    '<option value="">-- Pilih Material --</option>';

                data.forEach(function(item){

                    select.innerHTML += `
                        <option
                            value="${item.id_material}"
                            data-satuan="${item.satuan}">
                            [${item.kode_material}] ${item.nama_material}
                        </option>
                    `;

                });

            });

        });

});

function validasiNoHp(input) {

    // Hanya boleh angka
    input.value = input.value.replace(/[^0-9]/g, '');

    if (input.value.length > 0 && input.value.length < 11) {

        input.setCustomValidity('Nomor HP minimal 11 digit.');

    } else if (input.value.length > 13) {

        input.setCustomValidity('Nomor HP maksimal 13 digit.');

    } else {

        input.setCustomValidity('');

    }

}

</script>

@if(session('reset_jumlah'))
<script>

window.addEventListener('load', function(){

    document.querySelectorAll("input[name='jumlah[]']").forEach(function(input){
        input.value = '';
    });

});

</script>
@endif

</script>

</body>
</html>
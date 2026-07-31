<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Material Keluar Admin</title>
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
    <div class="form-card dokumen-card">

        <div class="form-header form-header-keluar">
            <div class="form-icon">📤</div>
            <div>
                <h2>Edit Material Keluar</h2>
                <p>Perbarui data material keluar</p>
            </div>
        </div>

        <form method="POST"
              action="{{ route('admin.material.keluar.update',$transaksi->id_transaksi) }}"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="form-grid">

                {{-- Tanggal --}}
                <div class="form-group">
                    <label>Tanggal <span style="color:red">*</span></label>
                    <input
                        type="date"
                        name="tanggal"
                        value="{{ old('tanggal', \Carbon\Carbon::parse($transaksi->tgl_transaksi)->format('Y-m-d')) }}"
                        required>
                </div>

                {{-- No Surat Jalan --}}
                <div class="form-group">
                    <label>No Surat Jalan <span style="color:red">*</span></label>
                    <input
                        type="text"
                        name="no_bukti"
                        value="{{ old('no_bukti', $transaksi->no_bukti) }}"
                        required>
                </div>

                {{-- Area --}}
                <div class="form-group">
                    <label>Area <span style="color:red">*</span></label>

                    <select name="id_area" id="id_area" required>

                        @foreach($areas as $area)

                            <option
                                value="{{ $area->id_area }}"
                                {{ old('id_area', $transaksi->id_area) == $area->id_area ? 'selected' : '' }}>

                                {{ $area->nama_area }}

                            </option>

                        @endforeach

                    </select>
                </div>

                {{-- Cluster --}}
                <div class="form-group">
                    <label>Cluster <span style="color:red">*</span></label>

                    <select name="id_cluster" id="id_cluster" required>

                        @foreach($clusters as $cluster)

                            <option
                                value="{{ $cluster->id_cluster }}"
                                {{ old('id_cluster', $transaksi->id_cluster) == $cluster->id_cluster ? 'selected' : '' }}>

                                {{ $cluster->nama_cluster }}

                            </option>

                        @endforeach

                    </select>

                </div>

                {{-- Project --}}
                <div class="form-group">
                    <label>Project <span style="color:red">*</span></label>

                    <select name="project" id="project" required>

                        @foreach($projects as $project)

                            <option
                                value="{{ $project->project }}"
                                {{ old('project', $transaksi->project) == $project->project ? 'selected' : '' }}>

                                {{ $project->project }}

                            </option>

                        @endforeach

                    </select>
                </div>

                {{-- Nama Penerima --}}
                <div class="form-group">
                    <label>Nama Penerima <span style="color:red">*</span></label>

                    <input
                        type="text"
                        name="nama_penerima"
                        value="{{ old('nama_penerima', $transaksi->nama_penerima) }}"
                        required>
                </div>

                {{-- Yang Menerima --}}
                <div class="form-group">
                    <label>Yang Menerima <span style="color:red">*</span></label>

                    <input
                        type="text"
                        name="nama_sopir"
                        value="{{ old('nama_sopir', $transaksi->nama_sopir) }}"
                        required>
                </div>

                {{-- No HP --}}
                <div class="form-group">
                    <label>No HP Penerima <span style="color:red">*</span></label>

                    <input
                        type="text"
                        name="no_hp"
                        id="no_hp"
                        value="{{ old('no_hp', $transaksi->no_hp) }}"
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
                        value="{{ old('kendaraan', $transaksi->kendaraan) }}"
                        required>
                </div>

                {{-- Plat Nomor --}}
                <div class="form-group">
                    <label>Plat Nomor <span style="color:red">*</span></label>

                    <input
                        type="text"
                        name="plat_nomor"
                        value="{{ old('plat_nomor', $transaksi->plat_nomor) }}"
                        required>
                </div>

            </div>

            <div class="detail-material-card detail-material-keluar">

                <h2 class="detail-title">Detail Material Keluar</h2>

                <table class="material-table detail-material-table">
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
                                            {{ old('id_material',$transaksi->id_material)==$material->id_material ? 'selected' : '' }}>
                                            [{{ $material->kode_material }}]
                                            {{ $material->nama_material }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input
                                    type="number"
                                    name="jumlah"
                                    min="1"
                                    value="{{ old('jumlah',$transaksi->jumlah) }}"
                                    required>
                            </td>
                            <td>
                                <input
                                    type="text"
                                    id="satuan"
                                    value="{{ $transaksi->material->satuan }}"
                                    readonly>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="form-group detail-keterangan">

                <label>Keterangan</label>

                <textarea name="keterangan"
                          rows="5">{{ $transaksi->keterangan }}</textarea>

            </div>

            <div class="card" style="margin-top:25px;">

                <h2>Dokumen / Foto Saat Ini</h2>

                <table class="material-table">

                    <thead>

                    <tr>
                        <th style="width:70px;">No</th>
                        <th>Nama File</th>
                        <th style="width:180px;">Keterangan</th>
                        <th style="width:220px;">Aksi</th>
                    </tr>

                    </thead>

                    <tbody>

                    @forelse($transaksi->dokumentasiTransaksi as $dok)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ basename($dok->file_dokumentasi) }}</td>

                        <td>{{ $dok->keterangan ?? '-' }}</td>

                        <td class="aksi">
                            <div class="aksi-dokumen">
                                <a href="{{ asset('storage/'.$dok->file_dokumentasi) }}"
                                target="_blank"
                                class="btn-view">
                                    Lihat
                                </a>
                                <form
                                    action="{{ route('admin.material.keluar.dokumen.destroy',$dok->id_dokumentasi) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="btn-delete">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;">
                            Belum ada dokumen/foto
                        </td>

                    </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="upload-card" style="margin-top:25px;">

                <h3>
                    Tambah Foto Dokumentasi Baru
                </h3>

                <input
                    type="file"
                    name="foto_dokumentasi[]"
                    multiple
                    accept=".jpg,.jpeg,.png">

                <small>(JPG / PNG)</small>

            </div>

            <div class="form-actions">

                <a href="{{ route('admin.material.keluar') }}"
                   class="btn-kembali">

                    ← Kembali

                </a>

                <div>

                    <button type="reset"
                            class="btn-reset">

                        Reset

                    </button>

                    <button type="submit"
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

function isiSatuan(){

    let select=document.getElementById('id_material');

    let selected=select.options[select.selectedIndex];

    document.getElementById('satuan').value=
        selected.getAttribute('data-satuan') || '';

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

</script>

</body>
</html>
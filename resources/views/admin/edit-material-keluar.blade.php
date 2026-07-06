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

    <a href="{{ route('profile.edit') }}" class="profile-link">
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
        <a href="{{ route('admin.kelola.area') }}">Kelola Area</a>
        <a href="{{ route('admin.data.material') }}">Data Material</a>
        <a href="{{ route('admin.material.masuk') }}">Material Masuk</a>
        <a href="{{ route('admin.material.keluar') }}" class="active">Material Keluar</a>
        <a href="{{ route('admin.stok.material') }}">Stok Material</a>
        <a href="{{ route('admin.cluster') }}">Cluster</a>
        <a href="{{ route('admin.dokumen') }}">Dokumen</a>
        <a href="{{ route('admin.surat.jalan') }}">Surat Jalan</a>
        <a href="{{ route('admin.notifikasi') }}">Notifikasi</a>
    </div>

    <div class="logout">
        <form method="POST" action="{{ route('logout') }}" onsubmit="return konfirmasiLogout()">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

</div>

<div class="content">

    <div class="topbar">
        <h1>Edit Material Keluar</h1>

        <input type="text" placeholder="🔍 Cari material...">

        <h2>
            👤 Hello, {{ Auth::user()->nama_user }}(Admin)
        </h2>
    </div>

    <div class="form-card">

        <div class="form-header">
            <div>
                <h1>Edit Material Keluar</h1>
                <p>Perbarui data material keluar.</p>
            </div>
        </div>

        <form method="POST"
              action="{{ route('admin.material.keluar.update',$transaksi->id_transaksi) }}"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date"
                           name="tanggal"
                           value="{{ $transaksi->tgl_transaksi }}"
                           required>
                </div>

                <div class="form-group">
                    <label>No Bukti / Surat Jalan</label>
                    <input type="text"
                           name="no_bukti"
                           value="{{ $transaksi->no_bukti }}"
                           required>
                </div>

                <div class="form-group">
                    <label>Material</label>

                    <select name="id_material"
                            id="id_material"
                            onchange="isiSatuan()"
                            required>

                        @foreach($materials as $material)

                        <option
                            value="{{ $material->id_material }}"
                            data-satuan="{{ $material->satuan }}"
                            {{ $transaksi->id_material==$material->id_material ? 'selected':'' }}>

                            [{{ $material->kode_material }}]
                            {{ $material->nama_material }}

                        </option>

                        @endforeach

                    </select>

                </div>

                <div class="form-group">
                    <label>Jumlah</label>

                    <input type="number"
                           name="jumlah"
                           min="1"
                           value="{{ $transaksi->jumlah }}"
                           required>
                </div>

                <div class="form-group">
                    <label>Satuan</label>

                    <input type="text"
                           id="satuan"
                           value="{{ $transaksi->material->satuan ?? '' }}"
                           readonly>
                </div>

                <div class="form-group">
                    <label>Cluster</label>

                    <select name="id_cluster" required>

                        @foreach($clusters as $cluster)

                        <option
                            value="{{ $cluster->id_cluster }}"
                            {{ $transaksi->id_cluster==$cluster->id_cluster ? 'selected':'' }}>

                            [{{ $cluster->kode_cluster }}]
                            {{ $cluster->nama_cluster }}

                        </option>

                        @endforeach

                    </select>

                </div>

                <div class="form-group">
                    <label>Project</label>

                    <input type="text"
                           name="project"
                           value="{{ $transaksi->project }}"
                           required>
                </div>

                <div class="form-group">
                    <label>Nama Penerima</label>

                    <input type="text"
                           name="nama_penerima"
                           value="{{ $transaksi->nama_penerima }}"
                           required>
                </div>

            </div>

            <div class="form-group">

                <label>Keterangan</label>

                <textarea name="keterangan"
                          rows="5">{{ $transaksi->keterangan }}</textarea>

            </div>

            <div class="card" style="margin:20px 28px;">

                <h2>Dokumen / Foto Saat Ini</h2>

                <table class="material-table">

                    <thead>

                    <tr>
                        <th>No</th>
                        <th>Nama File</th>
                        <th>Keterangan</th>
                        <th>Lihat</th>
                    </tr>

                    </thead>

                    <tbody>

                    @forelse($transaksi->dokumentasiTransaksi as $dok)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ basename($dok->file_dokumentasi) }}</td>

                        <td>{{ $dok->keterangan ?? '-' }}</td>

                        <td>

                            <a href="{{ asset('storage/'.$dok->file_dokumentasi) }}"
                               target="_blank"
                               class="btn-view">

                                Lihat

                            </a>

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

            <div class="form-grid" style="margin:20px 28px;">

                <div class="form-group">

                    <label>Tambah Foto Dokumentasi Baru</label>

                    <input type="file"
                           name="foto_dokumentasi[]"
                           multiple
                           accept="image/*">

                </div>

                <div class="form-group">

                    <label>Tambah Dokumen Baru</label>

                    <input type="file"
                           name="dokumen[]"
                           multiple
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">

                </div>

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

<script>

function isiSatuan(){

    let select=document.getElementById('id_material');

    let selected=select.options[select.selectedIndex];

    document.getElementById('satuan').value=
        selected.getAttribute('data-satuan') || '';

}

function konfirmasiLogout(){

    return confirm("Apakah Anda yakin ingin logout?");

}

</script>

</body>
</html>
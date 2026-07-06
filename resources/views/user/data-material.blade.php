<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Material</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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
        <a href="{{ route('data.material') }}">Data Material</a>
        <a href="{{ route('material.masuk') }}">Material Masuk</a>
        <a href="{{ route('material.keluar') }}">Material Keluar</a>
        <a href="{{ route('stok.material') }}">Stok Material</a>
        <a href="{{ route('cluster') }}">Cluster</a>
        <a href="{{ route('dokumen') }}">Dokumen</a>
        <a href="{{ route('surat.jalan') }}">Surat Jalan</a>
    </div>

    <div class="logout">
        <form method="POST" action="{{ route('logout') }}" onsubmit="return konfirmasiLogout()">
            @csrf
            <button type="submit" class="logout-btn">🚪 Logout</button>
        </form>
    </div>
</div>

<div class="content">
    <div class="topbar">
        <h1>📊 Data Material</h1>
        <input type="text" placeholder="🔍 Cari material...">
        <h2>👤 Hello, {{ Auth::user()->nama_user }}! (Petugas)</h2>
    </div>

    <div class="card">

    <div class="card-header-material">
        <h2>Data Material</h2>

        <a href="{{ route('data.material.create') }}" class="btn-tambah">
        + Tambah Material
        </a>
    </div>

    <table class="material-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Material</th>
                <th>Nama Material</th>
                <th>Jenis</th>
                <th>Satuan</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($materials as $material)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $material->kode_material }}</td>
                    <td>{{ $material->nama_material }}</td>
                    <td>{{ $material->jenis_material }}</td>
                    <td>{{ $material->satuan }}</td>
                    <td>{{ $material->keterangan }}</td>
                    <td>
                        <a href="{{ route('data.material.edit', $material->id_material) }}" class="btn-edit">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;">
                        Belum ada data material
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
<script>
function konfirmasiLogout() {
    return confirm("Apakah Anda yakin ingin logout?");
}
</script>
</body>
</html>
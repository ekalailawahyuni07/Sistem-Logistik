<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cluster</title>
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
        <a href="{{ route('cluster') }}" class="active">Cluster</a>
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
        <h1>🏢 Data Cluster</h1>
        <input type="text" placeholder="🔍 Cari cluster...">
        <h2>👤 Hello, {{ Auth::user()->nama_user }}! (Petugas)</h2>
    </div>

    <div class="card">
        <div class="card-header-material">
            <h2>Data Cluster</h2>

            <a href="{{ route('cluster.create') }}" class="btn-tambah">
                + Tambah Cluster
            </a>
        </div>

        <table class="material-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Cluster</th>
                    <th>Nama Cluster</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($clusters as $cluster)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $cluster->kode_cluster }}</td>
                        <td>{{ $cluster->nama_cluster }}</td>
                        <td>
                            <a href="{{ route('cluster.edit', $cluster->id_cluster) }}" class="btn-edit">
                                Edit
                            </a>
                            <a href="{{ route('cluster.show', $cluster->id_cluster) }}" class="btn-view">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center;">
                            Belum ada data cluster
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function konfirmasiLogout() {
    return confirm("Apakah Anda yakin ingin logout?");
}
</script>

</body>
</html>
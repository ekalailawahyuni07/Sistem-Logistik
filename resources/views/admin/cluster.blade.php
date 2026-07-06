<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cluster Admin</title>
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
        <a href="{{ route('admin.material.keluar') }}">Material Keluar</a>
        <a href="{{ route('admin.stok.material') }}">Stok Material</a>
        <a href="{{ route('admin.cluster') }}" class="active">Cluster</a>
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
        <h1>Data Cluster</h1>
        <input type="text" placeholder="🔍 Cari cluster...">
        <h2>👤 Hello, {{ Auth::user()->nama_user }} (Admin)</h2>
    </div>

    <div class="card">
        <div class="card-header-material">
            <h2>Data Cluster</h2>

            <a href="{{ route('admin.cluster.create') }}" class="btn-tambah">
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
                            <a href="{{ route('admin.cluster.edit', $cluster->id_cluster) }}" class="btn-edit">
                                Edit
                            </a>
                            <a href="{{ route('admin.cluster.show', $cluster->id_cluster) }}" class="btn-view">
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
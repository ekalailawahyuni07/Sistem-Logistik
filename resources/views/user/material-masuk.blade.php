<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Material Masuk</title>
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
        <h1>📥 Material Masuk</h1>
        <input type="text" placeholder="🔍 Cari material masuk...">
        <h2>👤 Hello, {{ Auth::user()->nama_user }}! (Petugas)</h2>
    </div>

    <div class="card">

        <div class="card-header-material">
            <h2>Material Masuk Area Pontianak</h2>

            <a href="{{ route('material.masuk.create') }}" class="btn-tambah">
                + Tambah Material Masuk
            </a>
        </div>

        <table class="material-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>No Bukti / Surat Jalan</th>
                    <th>Kode Material</th>
                    <th>Nama Material</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($masuk as $item)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d-m-Y') }}</td>

                    <td>{{ $item->no_bukti }}</td>

                    <td>{{ $item->material->kode_material }}</td>

                    <td>{{ $item->material->nama_material }}</td>

                    <td>{{ $item->jumlah }}</td>

                    <td>{{ $item->material->satuan }}</td>

                    <td>{{ $item->keterangan }}</td>

                    <td>
                        <a href="{{ route('material.masuk.edit', $item->id_transaksi) }}" class="btn-edit">
                            Edit
                        </a>
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="9" style="text-align:center;">
                        Belum ada data material masuk
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
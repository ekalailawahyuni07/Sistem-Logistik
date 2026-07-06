<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Notifikasi</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

<div class="sidebar">
    <div class="logo">
        <img src="{{ asset('images/logo-tkm.png') }}">
    </div>

    <div class="profile">
        <div class="avatar">👤</div>
        <h4>{{ Auth::user()->nama_user }}</h4>
        <p>{{ Auth::user()->email }}</p>
    </div>

    <div class="menu">
        <a href="{{ route('admin.dashboard') }}">🏠 Dashboard</a>
        <a href="{{ route('admin.verifikasi.user') }}">👥 Verifikasi User</a>
        <a href="{{ route('admin.kelola.area') }}">🌍 Kelola Area</a>
        <a href="{{ route('admin.data.material') }}">📦 Data Material</a>
        <a href="{{ route('admin.material.masuk') }}">📥 Material Masuk</a>
        <a href="{{ route('admin.material.keluar') }}">📤 Material Keluar</a>
        <a href="{{ route('admin.stok.material') }}">📊 Stok Material</a>
        <a href="{{ route('admin.cluster') }}">🏢 Cluster</a>
        <a href="{{ route('admin.dokumen') }}">📁 Dokumen</a>
        <a href="{{ route('admin.surat.jalan') }}">🚚 Surat Jalan</a>
        <a href="{{ route('admin.notifikasi') }}" class="active">🔔 Notifikasi</a>
    </div>

    <div class="logout">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-btn">
                🚪 Logout
            </button>
        </form>
    </div>
</div>

<div class="content">

    <div class="topbar">
        <h1>🔔 Notifikasi Aktivitas Logistik</h1>
        <h2>👤 Hello, {{ Auth::user()->nama_user }}</h2>
    </div>

    <div class="card">

        <table class="material-table">

            <thead>
            <tr>
                <th>No</th>
                <th>Waktu</th>
                <th>Aktivitas</th>
                <th>Material</th>
                <th>Jumlah</th>
                <th>No Bukti</th>
            </tr>
            </thead>

            <tbody>

            @forelse($notifikasi as $item)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $item->created_at }}</td>

                    <td>

                        @if($item->jenis_transaksi=='masuk')

                            <span style="color:green;font-weight:bold">
                                Material Masuk
                            </span>

                        @else

                            <span style="color:red;font-weight:bold">
                                Material Keluar
                            </span>

                        @endif

                    </td>

                    <td>
                        {{ $item->material->nama_material }}
                    </td>

                    <td>
                        {{ $item->jumlah }}
                    </td>

                    <td>
                        {{ $item->no_bukti }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" style="text-align:center">
                        Belum ada notifikasi.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

</body>
</html>
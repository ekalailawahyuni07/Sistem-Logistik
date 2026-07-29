<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Cluster</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

<div class="sidebar">

    <div class="logo">
        <img src="{{ asset('images/logo-tkm.png') }}" alt="Logo PT">
    </div>

    <a href="{{ route('admin.profile.edit') }}"
       class="profile-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">

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
        <a href="{{ route('admin.cluster') }}" class="active">Daftar Kluster</a>
        <a href="{{ route('admin.data.material') }}">Master Data Material</a>
        <a href="{{ route('admin.material.masuk') }}">Material Masuk</a>
        <a href="{{ route('admin.material.keluar') }}">Material Keluar</a>
        <a href="{{ route('admin.stok.material') }}">Stok Material</a>
        <a href="{{ route('admin.dokumen') }}">Dokumen</a>
        <a href="{{ route('admin.surat.jalan') }}">Surat Jalan</a>
        <a href="{{ route('admin.notifikasi') }}">Log Keluar Masuk</a>
    </div>

    <div class="logout">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                Keluar
            </button>
        </form>
    </div>

</div>

<div class="content">

    <div class="card">

        <div class="form-header">

            <div>
                <h1>Detail Kluster</h1>
                <p>Informasi kluster dan riwayat material keluar.</p>
            </div>

        </div>

        <div class="detail-info">

            <p><strong>Kode Kluster :</strong> {{ $cluster->kode_cluster }}</p>
            <p><strong>Nama Kluster :</strong> {{ $cluster->nama_cluster }}</p>
            <p><strong>Area :</strong> {{ $cluster->area->nama_area ?? '-' }}</p>

        </div>

        <br>

        <h2>Riwayat Material Keluar</h2>

        <table class="material-table">

            <thead>

                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>No Surat Jalan</th>
                    <th>Material</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Penerima</th>
                    <th>Project</th>
                </tr>

            </thead>

            <tbody>

                @forelse($materialKeluar as $item)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>
                            {{ \Carbon\Carbon::parse($item->tgl_transaksi)->format('d-m-Y') }}
                        </td>

                        <td>{{ $item->no_bukti }}</td>

                        <td>{{ $item->material->nama_material ?? '-' }}</td>

                        <td>{{ $item->jumlah }}</td>

                        <td>{{ $item->material->satuan ?? '-' }}</td>

                        <td>{{ $item->nama_penerima ?? '-' }}</td>

                        <td>{{ $item->project ?? '-' }}</td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8" style="text-align:center;">
                            Belum ada riwayat material keluar.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

        <div class="form-footer">

            <a href="{{ route('admin.cluster') }}" class="btn-kembali">
                ← Kembali
            </a>

        </div>

    </div>

</div>

</body>
</html>
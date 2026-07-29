<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
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
        <a href="{{ route('admin.dashboard') }}"class="active">Dashboard</a>
        <a href="{{ route('admin.verifikasi.user') }}">Verifikasi User</a>
        <a href="{{ route('admin.kelola.area') }}">Kelola Area & Kluster</a>
        <a href="{{ route('admin.cluster') }}">Daftar Kluster</a>
        <a href="{{ route('admin.data.material') }}">Master Data Material</a>
        <a href="{{ route('admin.material.masuk') }}">Material Masuk</a>
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
    <div class="topbar">
        <div class="topbar-left">
            <h1>Dashboard Admin</h1>
            <input type="text" placeholder="🔍 Cari data...">
            <h2>👤 Halo, {{ Auth::user()->nama_user }} (Admin)</h2>
        </div>

        <div class="topbar-right">
            <a href="{{ route('admin.notifikasi') }}" class="notification" title="Log Keluar Masuk">
                🔔
            </a>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="card grafik-card">
            <div class="card-title-row">

                <h2>
                    Grafik Material Masuk dan Keluar
                    <span>(Bulan Ini)</span>
                </h2>

                <div class="dashboard-info-cards">

                    {{-- USER PENDING --}}
                    <a href="{{ route('admin.verifikasi.user') }}" class="pending-user-card">

                        <div class="pending-icon">👤</div>

                        <div class="pending-text">

                            <b>{{ $userPending }} Pengguna</b>

                            <span>Sedang menunggu verifikasi</span>

                        </div>

                        <div class="pending-arrow">›</div>

                    </a>

                </div>

            </div>

            <div class="chart-container">
                <canvas id="grafikMaterial"></canvas>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Monitoring Material Berdasarkan Area</h2>

        @forelse($monitoringArea as $area)
            <div class="area-monitoring">
                <h3>Area {{ $area['nama_area'] }}</h3>

                <div class="stats">
                    <div class="stat blue">
                        <b>{{ $area['total_keluar'] }}</b>
                        <p>Material Keluar</p>
                    </div>

                    <div class="stat orange">
                        <b>{{ $area['total_masuk'] }}</b>
                        <p>Material Masuk</p>
                    </div>

                    <div class="stat blue">
                        <b>{{ $area['total_stok'] }}</b>
                        <p>Total Stock Material</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-box">
                Belum ada data area.
            </div>
        @endforelse
    </div>

    <div class="card">
        <h2>Ringkasan Logistik Keseluruhan</h2>

        <div class="stats">
            <div class="stat blue">
                <b>{{ $totalKeluar }}</b>
                <p>Material Keluar</p>
            </div>

            <div class="stat orange">
                <b>{{ $totalMasuk }}</b>
                <p>Material Masuk</p>
            </div>

            <div class="stat blue">
                <b>{{ $totalStock }}</b>
                <p>Total Stock Material</p>
            </div>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('grafikMaterial');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
        datasets: [
            {
                label: 'Material Keluar',
                data: @json($grafikKeluar),
                backgroundColor: '#071f49',
                borderRadius: 4
            },
            {
                label: 'Material Masuk',
                data: @json($grafikMasuk),
                backgroundColor: '#d8782f',
                borderRadius: 4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

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
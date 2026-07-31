<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Kluster</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .card h1,
        .card h2,
        .card p,
        .card strong {
            color: #000000 !important;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="logo">
        <img src="{{ asset('images/logo-tkm.png') }}" alt="Logo PT">
    </div>

    <div class="profile">
        @if(Auth::user()->foto_profile)
            <img src="{{ asset('storage/' . Auth::user()->foto_profile) }}" class="profile-img">
        @else
            <div class="avatar">👤</div>
        @endif

        <h4>{{ Auth::user()->nama_user }}</h4>
        <p>{{ Auth::user()->email }}</p>
    </div>

    <div class="menu">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('data.material') }}">Master Data Material</a>
        <a href="{{ route('material.masuk') }}">Material Masuk</a>
        <a href="{{ route('material.keluar') }}">Material Keluar</a>
        <a href="{{ route('stok.material') }}">Stok Material</a>
        <a href="{{ route('cluster') }}" class="active">Daftar Kluster</a>
        <a href="{{ route('dokumen') }}">Dokumen</a>
        <a href="{{ route('surat.jalan') }}">Surat Jalan</a>
    </div>

    <div class="logout">
        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
            @csrf
            <button type="button" class="logout-btn" onclick="bukaModalLogout()">
                Keluar
            </button>
        </form>
    </div>
</div>

<div class="content">

    <div class="card">

        <h1>📋 Detail Kluster</h1>

        <hr>

        <p><strong>Kode Kluster :</strong> {{ $cluster->kode_cluster }}</p>

        <p><strong>Nama Kluster :</strong> {{ $cluster->nama_cluster }}</p>

        <p><strong>Area :</strong> {{ $cluster->area->nama_area ?? '-' }}</p>

        <br>

        <h2>Riwayat Material Keluar</h2>

        <table class="material-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>No Bukti</th>
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
                    <td>{{ $item->tgl_transaksi }}</td>
                    <td>{{ $item->no_bukti }}</td>
                    <td>{{ $item->material->nama_material ?? '-' }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ $item->material->satuan ?? '-' }}</td>
                    <td>{{ $item->nama_penerima }}</td>
                    <td>{{ $item->project }}</td>
                </tr>

                @empty

                <tr>
                    <td colspan="8" class="text-center">
                        Belum ada material keluar pada kluster ini.
                    </td>
                </tr>

                @endforelse

            </tbody>
        </table>

        <br>

        <a href="{{ route('cluster') }}" class="btn-kembali">
            ← Kembali
        </a>

    </div>

</div>

<div id="modalLogout" class="modal-hapus">
    <div class="modal-box">
        <div class="modal-icon logout-icon">🚪</div>
        <h2>Keluar</h2>
        <p>Apakah Anda yakin ingin keluar dari sistem?</p>
        <div class="modal-warning-text">
            Anda harus masuk kembali untuk mengakses sistem.
        </div>
        <div class="modal-actions">
            <button type="button" class="btn-batal-modal" onclick="tutupModalLogout()">Batal</button>
            <button type="button" class="btn-logout-modal" onclick="submitLogout()">Ya, Keluar</button>
        </div>
    </div>
</div>

<script>
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
    let modalLogout = document.getElementById("modalLogout");
    if(event.target == modalLogout){
        tutupModalLogout();
    }
}
</script>

</body>
</html>
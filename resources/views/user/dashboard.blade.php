<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard User</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

<div class="sidebar">
    <div class="logo">
    <img src="{{ asset('images/logo-tkm.png') }}" alt="Logo PT">
</div>

    <a href="{{ route('profile.edit') }}" class="profile-link">
        <div class="profile">
            <div class="avatar">👤</div>
            <h4>{{ Auth::user()->nama_user }}</h4>
            <p>{{ Auth::user()->email }}</p>
        </div>
    </a>

    <div class="menu">
        <a href="{{ route('dashboard') }}"class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('data.material') }}">Master Data Material</a>
        <a href="{{ route('material.masuk') }}">Material Masuk</a>
        <a href="{{ route('material.keluar') }}">Material Keluar</a>
        <a href="{{ route('stok.material') }}">Stok Material</a>
        <a href="{{ route('cluster') }}">Daftar Kluster</a>
        <a href="{{ route('dokumen') }}">Daftar Dokumen</a>
        <a href="{{ route('surat.jalan') }}">Surat Jalan</a>
    </div>

    <!-- Tambahkan ini -->
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
        <h1>Dashboard</h1>

        <input type="text" placeholder="🔍 Cari material / Surat Jalan...">

        <h2>👤 Halo, {{ Auth::user()->nama_user }}! (Petugas)</h2>
    </div>

    <div class="card">

        <div class="card-header-material">

            <h2>
                Grafik Material Masuk dan Keluar
                <span>(Bulan Ini)</span>
            </h2>

        </div>

        <canvas id="grafikMaterial" height="90"></canvas>

    </div>

    <div class="card">
        <h2>Monitoring Material Berdasarkan Area</h2>
        <h3>

        Area :
        <b>

        {{ $area->nama_area }}

        </b>

        </h3>

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

new Chart(ctx,{

type:'bar',

data:{

labels:[
'Minggu 1',
'Minggu 2',
'Minggu 3',
'Minggu 4'
],

datasets:[

{

label:'Material Keluar',

data:@json($grafikKeluar),

backgroundColor:'#081F4D'

},

{

label:'Material Masuk',

data:@json($grafikMasuk),

backgroundColor:'#F28C28'

}

]

},

options:{

responsive:true,

plugins:{
legend:{
position:'top'
}
}

}

});

</script>

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

    let modal=document.getElementById("modalLogout");

    if(event.target==modal){

        tutupModalLogout();

    }

}
</script>

</body>
</html>
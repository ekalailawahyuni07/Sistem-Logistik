<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Stok Material</title>
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
        <a href="{{ route('data.material') }}">Master Data Material</a>
        <a href="{{ route('material.masuk') }}">Material Masuk</a>
        <a href="{{ route('material.keluar') }}">Material Keluar</a>
        <a href="{{ route('stok.material') }}" class="active">Stok Material</a>
        <a href="{{ route('cluster') }}">Daftar Kluster</a>
        <a href="{{ route('dokumen') }}">Daftar Dokumen</a>
        <a href="{{ route('surat.jalan') }}">Surat Jalan</a>
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
        <h1>Stok Material</h1>
        <h2>👤 Halo, {{ Auth::user()->nama_user }}! (Petugas)</h2>
    </div>
    
    <div class="card">
        <div class="material-card-header">
            <h2>Rekap Jurnal Logistik Area {{ Auth::user()->area->nama_area ?? '' }}</h2>

            <div class="material-toolbar">
                <input
                    type="text"
                    id="searchStok"
                    class="material-search"
                    placeholder="🔍 Cari stok material..."
                    onkeyup="cariStok()"
                >
            </div>
        </div>

        <table class="material-table" id="tabelStok">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode / Designator Material</th>
                    <th>Nama Material</th>
                    <th>Satuan</th>
                    <th>Material IN</th>
                    <th>Material OUT</th>
                    <th>Stock</th>
                    <th>Keterangan</th>
                </tr>
            </thead>

            <tbody>
                @forelse($materials as $material)
                    @php
                        $masuk = $material->total_masuk ?? 0;
                        $keluar = $material->total_keluar ?? 0;
                        $stock = $masuk - $keluar;
                    @endphp

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $material->kode_material }}</td>
                        <td>{{ $material->nama_material }}</td>
                        <td>{{ $material->satuan }}</td>
                        <td class="text-right">{{ $masuk }}</td>
                        <td class="text-right">{{ $keluar }}</td>

                        <td class="text-right
                            @if($stock <= 0)
                                stock-habis
                            @elseif($stock <= 10)
                                stock-menipis
                            @else
                                stock-aman
                            @endif
                        ">
                            {{ $stock }}
                        </td>

                        <td>
                            @if($stock <= 0)
                                Stok habis
                            @elseif($stock <= 10)
                                Stok menipis
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            Belum ada data material
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
<script>
function cariStok() {
    let input = document.getElementById("searchStok").value.toLowerCase();
    let rows = document.querySelectorAll("#tabelStok tbody tr");

    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(input) ? "" : "none";
    });
}

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
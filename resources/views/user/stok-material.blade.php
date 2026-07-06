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
        <a href="{{ route('data.material') }}">Data Material</a>
        <a href="{{ route('material.masuk') }}">Material Masuk</a>
        <a href="{{ route('material.keluar') }}">Material Keluar</a>
        <a href="{{ route('stok.material') }}" class="active">Stok Material</a>
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
        <h1>📦 Stok Material</h1>
        <input type="text" id="searchStok" placeholder="🔍 Cari stok material..." onkeyup="cariStok()">
        <h2>👤 Hello, {{ Auth::user()->nama_user }}! (Petugas)</h2>
    </div>

    <div class="summary-cards">
        <div class="summary-box biru">
            <h3>Total Material</h3>
            <p>{{ $totalMaterial }}</p>
        </div>

        <div class="summary-box hijau">
            <h3>Material IN</h3>
            <p>{{ $totalMasuk }}</p>
        </div>

        <div class="summary-box merah">
            <h3>Material OUT</h3>
            <p>{{ $totalKeluar }}</p>
        </div>

        <div class="summary-box orange">
            <h3>Total Stock</h3>
            <p>{{ $totalStock }}</p>
        </div>

        <div class="summary-box kuning">
            <h3>Stok Menipis</h3>
            <p>{{ $stokMenipis }}</p>
        </div>
    </div>

    <div class="card">
        <h2>Rekap Jurnal Logistik Area Pontianak</h2>

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

<script>
function cariStok() {
    let input = document.getElementById("searchStok").value.toLowerCase();
    let rows = document.querySelectorAll("#tabelStok tbody tr");

    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(input) ? "" : "none";
    });
}

function konfirmasiLogout() {
    return confirm("Apakah Anda yakin ingin logout?");
}
</script>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Stok Material</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        html { height: 100%; }
        body { height: 100vh; overflow: hidden; }
        .sidebar { overflow-y: auto; height: 100vh; }
        .user-page-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            padding: 18px 28px;
        }
        .user-page-container .topbar { flex-shrink: 0; margin-bottom: 12px; }
        .user-page-container .card {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            padding: 14px 16px;
        }
        .material-card-header { flex-shrink: 0; margin-bottom: 12px; }
        .material-toolbar {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .material-search {
            height: 38px;
            padding: 0 14px;
            border: 1.5px solid #cdd5e0;
            border-radius: 8px;
            font-size: 13px;
            box-sizing: border-box;
            outline: none;
        }
        .filter-area {
            height: 38px !important;
            padding: 0 34px 0 14px !important;
            border: 1.5px solid #cdd5e0 !important;
            border-radius: 8px !important;
            font-size: 13px !important;
            background-color: #ffffff !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%231a237e' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 10px center !important;
            background-size: 14px 14px !important;
            color: #2d3748 !important;
            outline: none !important;
            cursor: pointer !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            box-sizing: border-box !important;
        }
        .filter-area:focus {
            border-color: #1a237e;
        }
        .btn-pdf {
            height: 38px;
            padding: 0 16px;
            background: #e53e3e;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            white-space: nowrap;
            box-sizing: border-box;
        }
        .btn-pdf:hover {
            background: #c53030;
        }
        .table-container-scroll {
            flex: 1;
            overflow-y: auto;
            overflow-x: auto;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        .table-container-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .table-container-scroll::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .table-container-scroll::-webkit-scrollbar-thumb { background: #a0aec0; border-radius: 4px; }
        .material-table thead th {
            position: sticky;
            top: 0;
            background: #1a3a6e;
            color: #ffffff;
            z-index: 2;
            padding: 10px;
            text-align: center;
            white-space: nowrap;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="logo">
        <img src="{{ asset('images/logo-tkm.png') }}" alt="Logo PT">
    </div>

    <a href="{{ route('profile.edit') }}" style="text-decoration:none; color:inherit;">
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
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('data.material') }}">Master Data Material</a>
        <a href="{{ route('material.masuk') }}">Material Masuk</a>
        <a href="{{ route('material.keluar') }}">Material Keluar</a>
        <a href="{{ route('stok.material') }}" class="active">Stok Material</a>
        <a href="{{ route('cluster') }}">Daftar Kluster</a>
        <a href="{{ route('dokumen') }}">Dokumen</a>
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

<div class="content user-page-container">
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
                    onkeyup="filterStok()"
                >

                <select id="filterProject" class="filter-area" onchange="filterStok()">
                    <option value="">Semua Project</option>
                    @if(isset($projects))
                        @foreach($projects as $p)
                            <option value="{{ strtolower($p->project) }}">{{ $p->project }}</option>
                        @endforeach
                    @endif
                </select>

                <a href="{{ route('stok.material.pdf') }}" class="btn-pdf" id="btnPdfStok">
                    📄 Export PDF
                </a>
            </div>
        </div>

        <div class="table-container-scroll">
            <table class="material-table" id="tabelStok">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Material</th>
                        <th>Nama Material</th>
                        <th>Project</th>
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

                        <tr data-project="{{ strtolower($material->project ?? '') }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $material->kode_material }}</td>
                            <td>{{ $material->nama_material }}</td>
                            <td>{{ $material->project ?? '-' }}</td>
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
                            <td colspan="9" class="text-center">
                                Belum ada data material
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
                Ya, Keluar
            </button>

        </div>

    </div>

</div>

<script>
function filterStok() {
    let searchText = document.getElementById("searchStok").value.toLowerCase();
    let selectedProject = document.getElementById("filterProject").value.toLowerCase();
    let rows = document.querySelectorAll("#tabelStok tbody tr");

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        let rowProject = (row.getAttribute("data-project") || "").toLowerCase();

        let matchesSearch = text.includes(searchText);
        let matchesProject = (selectedProject === "" || rowProject === selectedProject);

        if (matchesSearch && matchesProject) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });

    let btnPdf = document.getElementById("btnPdfStok");
    let pdfUrl = "{{ route('stok.material.pdf') }}";
    let rawProjectVal = document.getElementById("filterProject").value;
    if (rawProjectVal !== "") {
        pdfUrl += "?project=" + encodeURIComponent(rawProjectVal);
    }
    btnPdf.href = pdfUrl;
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
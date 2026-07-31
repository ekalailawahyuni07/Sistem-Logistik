<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Area & Cluster</title>

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <style>
        .area-page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 22px;
        }

        .area-page-header h2 {
            margin: 0;
            color: #061b40;
        }

        .area-page-header p {
            margin: 5px 0 0;
            color: #64748b;
        }

        .area-list {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .area-card {
            background: #ffffff;
            border: 1px solid #dbe3ed;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 18px rgba(6, 27, 64, 0.09);
        }

        .area-card-header {
            padding: 20px 24px;
            background: linear-gradient(135deg, #061b40, #0d3b75);
            color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .area-title {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .area-title-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.14);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 25px;
        }

        .area-title h3 {
            margin: 0 0 4px;
            font-size: 21px;
        }

        .area-title small {
            color: #dbeafe;
        }

        .area-header-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .btn-area-edit,
        .btn-area-delete,
        .btn-cluster-add {
            border: none;
            border-radius: 8px;
            padding: 9px 13px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-area-edit {
            background: #facc15;
            color: #422006;
        }

        .btn-area-delete {
            background: #dc2626;
            color: #ffffff;
        }

        .btn-cluster-add {
            background: #16a34a;
            color: #ffffff;
        }

        .area-summary {
            display: flex;
            gap: 15px;
            padding: 18px 24px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .area-summary-item {
            min-width: 145px;
            padding: 12px 16px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
        }

        .area-summary-item span {
            display: block;
            color: #64748b;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .area-summary-item strong {
            color: #061b40;
            font-size: 21px;
        }

        .cluster-section {
            padding: 22px 24px 26px;
            overflow-x: auto;
        }

        .cluster-section-header {
            margin-bottom: 14px;
        }

        .cluster-section-header h4 {
            margin: 0;
            color: #061b40;
            font-size: 18px;
        }

        .cluster-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cluster-table th,
        .cluster-table td {
            border: 1px solid #dbe3ed;
            padding: 12px;
        }

        .cluster-table th {
            background: #061b40;
            color: #ffffff;
            text-align: center;
        }

        .cluster-table td:first-child,
        .cluster-table td:last-child {
            text-align: center;
        }

        .cluster-actions {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 7px;
        }

        .btn-cluster-edit,
        .btn-cluster-delete {
            border: none;
            border-radius: 7px;
            padding: 7px 12px;
            color: #ffffff;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-cluster-edit {
            background: #f59e0b;
        }

        .btn-cluster-delete {
            background: #dc2626;
        }

        .empty-area,
        .empty-cluster {
            text-align: center;
            color: #64748b;
        }

        .empty-area {
            padding: 45px 20px;
            background: #ffffff;
            border-radius: 15px;
        }

        .empty-cluster {
            padding: 25px !important;
        }

        .area-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 99999;
            padding: 20px;
            background: rgba(6, 27, 64, 0.68);
            align-items: center;
            justify-content: center;
        }

        .area-modal.show {
            display: flex;
        }

        .area-modal-box {
            width: 100%;
            max-width: 470px;
            padding: 26px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.28);
            animation: modalMasuk 0.2s ease;
        }

        .area-modal-header {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 20px;
        }

        .area-modal-icon {
            width: 50px;
            height: 50px;
            background: #061b40;
            color: #ffffff;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 25px;
        }

        .area-modal-header h2 {
            margin: 0;
            color: #061b40;
        }

        .area-modal-header p {
            margin: 4px 0 0;
            color: #64748b;
        }

        .modal-form-group {
            margin-bottom: 18px;
        }

        .modal-form-group label {
            display: block;
            margin-bottom: 7px;
            color: #061b40;
            font-weight: bold;
        }

        .modal-form-group input {
            width: 100%;
            box-sizing: border-box;
            padding: 12px 13px;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            outline: none;
        }

        .modal-form-group input:focus {
            border-color: #061b40;
            box-shadow: 0 0 0 3px rgba(6, 27, 64, 0.12);
        }

        .area-modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 22px;
        }

        .btn-modal-cancel,
        .btn-modal-save,
        .btn-modal-delete {
            border: none;
            border-radius: 9px;
            padding: 11px 18px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-modal-cancel {
            background: #e2e8f0;
            color: #1e293b;
        }

        .btn-modal-save {
            background: #061b40;
            color: #ffffff;
        }

        .btn-modal-delete {
            background: #dc2626;
            color: #ffffff;
        }

        .delete-modal-content {
            text-align: center;
        }

        .delete-modal-icon {
            margin-bottom: 10px;
            font-size: 52px;
        }

        @keyframes modalMasuk {
            from {
                opacity: 0;
                transform: scale(0.92);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>

<body>

<div class="sidebar">
    <div class="logo">
        <img src="{{ asset('images/logo-tkm.png') }}" alt="Logo PT">
    </div>

    <a href="{{ route('admin.profile.edit') }}"class="profile-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
        <div class="profile">
            @if(Auth::user()->foto_profile)
                <img
                    src="{{ asset('storage/' . Auth::user()->foto_profile) }}"
                    class="profile-img"
                    alt="Foto Profil"
                >
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
        <a href="{{ route('admin.kelola.area') }}" class="active">Kelola Area & Kluster</a>
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
        <h1>Kelola Area & Kluster</h1>
        <h2>
            👤 Halo, {{ Auth::user()->nama_user }} (Admin)
        </h2>
    </div>

    @if(session('success'))
        <div class="alert" style="background:#dcfce7; color:#166534;">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert" style="background:#fee2e2; color:#991b1b;">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert" style="background:#fee2e2; color:#991b1b;">
            <strong>Data belum berhasil diproses:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="area-page-header">

        <div class="area-info">
            <h2>Daftar Area dan Kluster</h2>
        </div>

        <div class="header-action">
            <input
                type="text"
                id="searchArea"
                placeholder="🔍 Cari area atau cluster..."
                onkeyup="cariAreaDanCluster()"
                class="search-area"
            >
            <select
                id="filterArea"
                class="filter-area"
                onchange="cariAreaDanCluster()"
            >
                <option value="">Semua Area</option>

                @foreach($areas as $area)
                    <option value="{{ strtolower($area->nama_area) }}">
                        {{ $area->nama_area }}
                    </option>
                @endforeach
            </select>
            <button
                type="button"
                class="btn-tambah"
                onclick="bukaModalTambahArea()"
            >
                + Tambah Area
            </button>
        </div>
    </div>

    <div class="area-list">
        @forelse($areas as $area)
            <div class="area-card" data-area="{{ strtolower(trim($area->nama_area)) }}">
                <div class="area-card-header">
                    <div class="area-title">
                        <div class="area-title-icon">📍</div>

                        <div>
                            <h3>{{ $area->nama_area }}</h3>
                            <small>ID Area: {{ $area->id_area }}</small>
                        </div>
                    </div>

                    <div class="area-header-actions">
                        <button
                            type="button"
                            class="btn-cluster-add"
                            data-area-id="{{ $area->id_area }}"
                            data-area-name="{{ $area->nama_area }}"
                            onclick="bukaModalTambahCluster(this)"
                        >
                            + Tambah Kluster
                        </button>

                        <button
                            type="button"
                            class="btn-area-edit"
                            data-area-id="{{ $area->id_area }}"
                            data-area-name="{{ $area->nama_area }}"
                            onclick="bukaModalEditArea(this)"
                        >
                            Edit Area
                        </button>

                        <button
                            type="button"
                            class="btn-area-delete"
                            data-delete-url="{{ route('admin.kelola.area.destroy', $area->id_area) }}"
                            data-delete-name="{{ $area->nama_area }}"
                            data-delete-type="area"
                            onclick="bukaModalHapus(this)"
                        >
                            Hapus Area
                        </button>
                    </div>
                </div>

                <div class="area-summary">
                    <div class="area-summary-item">
                        <span>Jumlah User</span>
                        <strong>{{ $area->users->count() }}</strong>
                    </div>

                    <div class="area-summary-item">
                        <span>Jumlah Kluster</span>
                        <strong>{{ $area->clusters->count() }}</strong>
                    </div>
                </div>

                <div class="cluster-section">
                    <div class="cluster-section-header">
                        <h4>Daftar Kluster</h4>
                    </div>

                    <table class="cluster-table">
                        <thead>
                            <tr>
                                <th style="width:70px;">No</th>
                                <th>Kode Kluster</th>
                                <th>Nama Kluster</th>
                                <th style="width:210px;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($area->clusters as $cluster)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>{{ $cluster->kode_cluster }}</td>

                                <td>
                                    <a
                                        href="{{ route('admin.cluster', ['cluster' => $cluster->id_cluster]) }}"
                                        class="cluster-name-link"
                                        title="Buka cluster {{ $cluster->nama_cluster }}"
                                    >
                                        {{ $cluster->nama_cluster }}
                                    </a>
                                </td>

                                <td>
                                    <div class="cluster-actions">
                                        <button
                                            type="button"
                                            class="btn-cluster-edit"
                                            data-cluster-id="{{ $cluster->id_cluster }}"
                                            data-cluster-code="{{ $cluster->kode_cluster }}"
                                            data-cluster-name="{{ $cluster->nama_cluster }}"
                                            onclick="bukaModalEditCluster(this)"
                                        >
                                            Edit
                                        </button>

                                        <button
                                            type="button"
                                            class="btn-cluster-delete"
                                            data-delete-url="{{ route('admin.kelola.area.cluster.destroy', $cluster->id_cluster) }}"
                                            data-delete-name="{{ $cluster->nama_cluster }}"
                                            data-delete-type="cluster"
                                            onclick="bukaModalHapus(this)"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="empty-cluster">
                                        Belum ada kluster dalam area ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="empty-area">
                <h2>Belum Ada Data Area</h2>
                <p>Klik Tambah Area untuk membuat area baru.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- MODAL TAMBAH AREA --}}
<div id="modalTambahArea" class="area-modal">
    <div class="area-modal-box">
        <div class="area-modal-header">
            <div class="area-modal-icon">🌍</div>

            <div>
                <h2>Tambah Area</h2>
                <p>Masukkan nama area baru.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.kelola.area.store') }}">
            @csrf

            <div class="modal-form-group">
                <label>Nama Area</label>

                <input
                    type="text"
                    id="nama_area_tambah"
                    name="nama_area"
                    placeholder="Contoh: Pontianak"
                    maxlength="100"
                    required
                >
            </div>

            <div class="area-modal-actions">
                <button
                    type="button"
                    class="btn-modal-cancel"
                    onclick="tutupModal('modalTambahArea')"
                >
                    Batal
                </button>

                <button type="submit" class="btn-modal-save">
                    💾 Simpan Area
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT AREA --}}
<div id="modalEditArea" class="area-modal">
    <div class="area-modal-box">
        <div class="area-modal-header">
            <div class="area-modal-icon">✏️</div>

            <div>
                <h2>Edit Area</h2>
                <p>Perbarui nama area.</p>
            </div>
        </div>

        <form method="POST" id="formEditArea">
            @csrf
            @method('PUT')

            <div class="modal-form-group">
                <label>Nama Area</label>

                <input
                    type="text"
                    id="nama_area_edit"
                    name="nama_area"
                    maxlength="100"
                    required
                >
            </div>

            <div class="area-modal-actions">
                <button
                    type="button"
                    class="btn-modal-cancel"
                    onclick="tutupModal('modalEditArea')"
                >
                    Batal
                </button>

                <button type="submit" class="btn-modal-save">
                    ✓ Update Area
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL TAMBAH KLUSTER --}}
<div id="modalTambahCluster" class="area-modal">
    <div class="area-modal-box">
        <div class="area-modal-header">
            <div class="area-modal-icon">🏢</div>

            <div>
                <h2>Tambah Kluster</h2>
                <p id="teksAreaTambahCluster">Tambahkan kluster ke area.</p>
            </div>
        </div>

        <form method="POST" id="formTambahCluster">
            @csrf

            <div class="modal-form-group">
                <label>Kode Kluster</label>

                <input
                    type="text"
                    id="kode_cluster_tambah"
                    name="kode_cluster"
                    placeholder="Contoh: CL001"
                    maxlength="50"
                    required
                >
            </div>

            <div class="modal-form-group">
                <label>Nama Kluster</label>

                <input
                    type="text"
                    id="nama_cluster_tambah"
                    name="nama_cluster"
                    placeholder="Contoh: Sungai Rengas RW 6"
                    maxlength="100"
                    required
                >
            </div>

            <div class="area-modal-actions">
                <button
                    type="button"
                    class="btn-modal-cancel"
                    onclick="tutupModal('modalTambahCluster')"
                >
                    Batal
                </button>

                <button type="submit" class="btn-modal-save">
                    💾 Simpan Kluster
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT KLUSTER --}}
<div id="modalEditCluster" class="area-modal">
    <div class="area-modal-box">
        <div class="area-modal-header">
            <div class="area-modal-icon">✏️</div>

            <div>
                <h2>Edit Kluster</h2>
                <p>Perbarui kode dan nama kluster.</p>
            </div>
        </div>

        <form method="POST" id="formEditCluster">
            @csrf
            @method('PUT')

            <div class="modal-form-group">
                <label>Kode Kluster</label>

                <input
                    type="text"
                    id="kode_cluster_edit"
                    name="kode_cluster"
                    maxlength="50"
                    required
                >
            </div>

            <div class="modal-form-group">
                <label>Nama Kluster</label>

                <input
                    type="text"
                    id="nama_cluster_edit"
                    name="nama_cluster"
                    maxlength="100"
                    required
                >
            </div>

            <div class="area-modal-actions">
                <button
                    type="button"
                    class="btn-modal-cancel"
                    onclick="tutupModal('modalEditCluster')"
                >
                    Batal
                </button>

                <button type="submit" class="btn-modal-save">
                    ✓ Update Kluster
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL HAPUS --}}
<div id="modalHapus" class="area-modal">
    <div class="area-modal-box delete-modal-content">
        <div class="delete-modal-icon">⚠️</div>

        <h2 id="judulModalHapus">Konfirmasi Hapus</h2>

        <p id="pesanModalHapus">
            Data akan dihapus permanen.
        </p>

        <form method="POST" id="formHapus">
            @csrf
            @method('DELETE')

            <div
                class="area-modal-actions"
                style="justify-content:center;"
            >
                <button
                    type="button"
                    class="btn-modal-cancel"
                    onclick="tutupModal('modalHapus')"
                >
                    Batal
                </button>

                <button type="submit" class="btn-modal-delete">
                    Ya, Hapus
                </button>
            </div>
        </form>
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
const baseKelolaArea = @json(url('/admin/kelola-area'));

function bukaModal(idModal) {
    document.getElementById(idModal).classList.add('show');
}

function tutupModal(idModal) {
    document.getElementById(idModal).classList.remove('show');
}

function bukaModalTambahArea() {
    document.getElementById('nama_area_tambah').value = '';
    bukaModal('modalTambahArea');
}

function bukaModalEditArea(button) {
    const idArea = button.dataset.areaId;
    const namaArea = button.dataset.areaName;

    document.getElementById('formEditArea').action =
        baseKelolaArea + '/' + idArea + '/update';

    document.getElementById('nama_area_edit').value = namaArea;

    bukaModal('modalEditArea');
}

function bukaModalTambahCluster(button) {
    const idArea = button.dataset.areaId;
    const namaArea = button.dataset.areaName;

    document.getElementById('formTambahCluster').action =
        baseKelolaArea + '/' + idArea + '/cluster/store';

    document.getElementById('teksAreaTambahCluster').textContent =
        'Tambahkan kluster ke area "' + namaArea + '".';

    document.getElementById('kode_cluster_tambah').value = '';
    document.getElementById('nama_cluster_tambah').value = '';

    bukaModal('modalTambahCluster');
}

function bukaModalEditCluster(button) {
    const idCluster = button.dataset.clusterId;
    const kodeCluster = button.dataset.clusterCode;
    const namaCluster = button.dataset.clusterName;

    document.getElementById('formEditCluster').action =
        baseKelolaArea + '/cluster/' + idCluster + '/update';

    document.getElementById('kode_cluster_edit').value = kodeCluster;
    document.getElementById('nama_cluster_edit').value = namaCluster;

    bukaModal('modalEditCluster');
}

function bukaModalHapus(button) {
    const deleteUrl = button.dataset.deleteUrl;
    const deleteName = button.dataset.deleteName;
    const deleteType = button.dataset.deleteType;

    document.getElementById('formHapus').action = deleteUrl;

    if (deleteType === 'area') {
        document.getElementById('judulModalHapus').textContent =
            'Hapus Area';

        document.getElementById('pesanModalHapus').textContent =
            'Yakin ingin menghapus area "' + deleteName +
            '"? Area hanya dapat dihapus jika tidak memiliki user dan cluster.';
    } else {
        document.getElementById('judulModalHapus').textContent =
            'Hapus Cluster';

        document.getElementById('pesanModalHapus').textContent =
            'Yakin ingin menghapus cluster "' + deleteName +
            '"? Cluster tidak dapat dihapus jika masih digunakan dalam transaksi.';
    }

    bukaModal('modalHapus');
}

function cariAreaDanCluster() {

    const keyword = document
        .getElementById("searchArea")
        .value
        .toLowerCase()
        .trim();

    const areaDipilih = document
        .getElementById("filterArea")
        .value
        .toLowerCase()
        .trim();

    document.querySelectorAll(".area-card").forEach(card => {

        const namaArea = card.dataset.area.toLowerCase().trim();

        const cocokArea =
            areaDipilih === "" ||
            namaArea === areaDipilih;

        const cocokKeyword =
            card.innerText.toLowerCase().includes(keyword);

        if (cocokArea && cocokKeyword) {
            card.style.display = "";
        } else {
            card.style.display = "none";
        }

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

document.querySelectorAll('.area-modal').forEach(modal => {
    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.classList.remove('show');
        }
    });
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        document.querySelectorAll('.area-modal.show').forEach(modal => {
            modal.classList.remove('show');
        });
    }
});
</script>

</body>
</html>
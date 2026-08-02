<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Material Keluar</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .form-header h1,
        .form-header h2,
        .form-header p,
        .form-group label,
        .form-group input,
        .form-group select,
        .form-group textarea {
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
        @if(Auth::user() && Auth::user()->foto_profile)
            <img src="{{ asset('storage/' . Auth::user()->foto_profile) }}" alt="Foto Profile" class="profile-img">
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
        <a href="{{ route('material.keluar') }}" class="active">Material Keluar</a>
        <a href="{{ route('stok.material') }}">Stok Material</a>
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

<div class="content">

    <div class="form-card">
        <div class="form-header">
            <div class="form-icon">📤</div>
            <div>
                <h2>Tambah Material Keluar</h2>
                <p>Isi tanggal dan no bukti, lalu tambahkan beberapa material yang keluar.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('material.keluar.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label>
                        Tanggal <span style="color:red;">*</span>
                    </label>
                    <input type="date" name="tanggal" required>
                </div>

                <div class="form-group">
                    <label>
                        No HP Penerima <span style="color:red;">*</span>
                    </label>

                    <input
                        type="text"
                        name="no_hp"
                        placeholder="Masukkan nomor HP penerima"
                        minlength="11"
                        maxlength="13"
                        pattern="[0-9]{11,13}"
                        inputmode="numeric"
                        required>
                </div>

                <div class="form-group">
                    <label>
                        Yang Menerima <span style="color:red;">*</span>
                    </label>

                    <input
                        type="text"
                        name="nama_sopir"
                        placeholder="Masukkan nama yang menerima"
                        required>
                </div>

                <div class="form-group">
                    <label>
                        Kendaraan <span style="color:red;">*</span>
                    </label>

                    <input
                        type="text"
                        name="kendaraan"
                        placeholder="Contoh: Pickup L300"
                        required>
                </div>

                <div class="form-group">
                    <label>
                        Plat Nomor <span style="color:red;">*</span>
                    </label>

                    <input
                        type="text"
                        name="plat_nomor"
                        placeholder="Contoh: KB 1234 XX"
                        required>
                </div>

                <div class="form-group">
                    <label>
                        No Surat Jalan <span style="color:red;">*</span>
                    </label>
                    <input type="text" name="no_bukti" placeholder="Masukkan no bukti / surat jalan" required>
                </div>

                <div class="form-group">
                    <label>
                        Cluster <span style="color:red;">*</span>
                    </label>
                    <select name="id_cluster" required>
                        <option value="">-- Pilih Cluster --</option>
                        @foreach($clusters as $cluster)
                            <option value="{{ $cluster->id_cluster }}">
                                [{{ $cluster->kode_cluster }}] {{ $cluster->nama_cluster }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>
                        Project <span style="color:red;">*</span>
                    </label>

                    <select name="project" required>

                        <option value="">
                            -- Pilih Project --
                        </option>

                        @foreach($projects as $project)

                            <option value="{{ $project->project }}">
                                {{ $project->project }}
                            </option>

                        @endforeach

                    </select>
                </div>

                <div class="form-group">
                    <label>
                        Nama Penerima <span style="color:red;">*</span>
                    </label>
                    <input type="text" name="nama_penerima" placeholder="Masukkan nama penerima" required>
                </div>
            </div>

            <div class="material-detail">
                <div class="detail-header">
                    <h3>Detail Material Keluar</h3>
                    <button type="button" class="btn-add-row" onclick="tambahBaris()">+ Tambah Baris</button>
                </div>

                <table class="material-table">
                    <thead>
                        <tr>
                            <th style="width:45%;">
                                Material <span style="color:red;">*</span>
                            </th>

                            <th style="width:15%;">
                                Jumlah <span style="color:red;">*</span>
                            </th>

                            <th style="width:15%;">
                                Satuan
                            </th>

                            <th style="width:10%;">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody id="materialBody">
                        <tr>
                            <td>
                                <select name="id_material[]" class="select-material" required onchange="isiSatuan(this)">
                                    <option value="">-- Pilih Material --</option>
                                    @foreach($materials as $material)

                                        @if($material->stok > 0)

                                            <option
                                                value="{{ $material->id_material }}"
                                                data-satuan="{{ $material->satuan }}"
                                                data-stok="{{ $material->stok }}">

                                                [{{ $material->kode_material }}]
                                                {{ $material->nama_material }}
                                                (Stok: {{ $material->stok }})

                                            </option>

                                        @endif

                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="number" name="jumlah[]" min="1" placeholder="Masukkan jumlah" required>
                            </td>

                            <td>
                                <input type="text" class="satuan-field" readonly>
                            </td>

                            <td style="text-align:center;">
                                <button type="button" class="btn-hapus-row" onclick="hapusBaris(this)">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" rows="5" placeholder="Opsional"></textarea>
            </div>

            <div class="form-grid" style="margin: 20px 28px;">
                <div class="form-group">

                    <label>
                        Upload Foto Dokumentasi Pengeluaran Material <span style="color:red;">*</span>
                    </label>

                    <input
                        type="file"
                        name="dokumen[]"
                        multiple
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                        required>

                    <small style="color:#6b7280;font-size:13px;">
                        (Surat Jalan / PDF / DOC / JPG / PNG)
                    </small>

                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('material.keluar') }}" class="btn-kembali">← Kembali</a>

                <div>
                    <button type="reset" class="btn-reset">Reset</button>
                    <button type="submit" class="btn-update">✓ Simpan Semua</button>
                </div>
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
function isiSatuan(select) {

    let selected = select.options[select.selectedIndex];

    let satuan = selected.dataset.satuan || "";
    let stok = Number(selected.dataset.stok || 0);

    console.log(stok);

    let row = select.closest("tr");

    row.querySelector(".satuan-field").value = satuan;

    let jumlah = row.querySelector('input[name="jumlah[]"]');

    jumlah.value = "";
    jumlah.max = stok;

    jumlah.oninput = function () {

        let nilai = Number(this.value);

        if (nilai > stok) {

            alert("Stok tidak mencukupi!\n\nStok tersedia hanya " + stok);

            this.value = stok;
        }

    };

}

function tambahBaris() {
    let tbody = document.getElementById('materialBody');
    let barisPertama = tbody.querySelector('tr');
    let barisBaru = barisPertama.cloneNode(true);

    barisBaru.querySelectorAll('input').forEach(input => {
        input.value = '';
    });

    barisBaru.querySelector('select').selectedIndex = 0;

    tbody.appendChild(barisBaru);
}

function hapusBaris(button) {
    let tbody = document.getElementById('materialBody');

    if (tbody.rows.length > 1) {
        button.closest('tr').remove();
    } else {
        alert('Minimal harus ada 1 baris material.');
    }
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
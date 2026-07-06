<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Material Masuk</title>
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
        <a href="{{ route('stok.material') }}">Stok Material</a>
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
        <h1>📥 Tambah Material Masuk</h1>
        <input type="text" placeholder="🔍 Cari material masuk...">
        <h2>👤 Hello, {{ Auth::user()->nama_user }}! (Petugas)</h2>
    </div>

    <div class="form-card">
        <div class="form-header">
            <div class="form-icon">📥</div>
            <div>
                <h2>Tambah Material Masuk</h2>
                <p>Isi tanggal dan no bukti, lalu tambahkan beberapa material yang masuk.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('material.masuk.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" required>
                </div>

                <div class="form-group">
                    <label>No Bukti / Surat Jalan</label>
                    <input type="text" name="no_bukti" placeholder="Contoh: 1654/EMR/NRO-GDR/11/2025" required>
                </div>
            </div>

            <div class="material-detail">
                <div class="detail-header">
                    <h3>Detail Material Masuk</h3>
                    <button type="button" class="btn-add-row" onclick="tambahBaris()">+ Tambah Baris</button>
                </div>

                <table class="material-table">
                    <thead>
                        <tr>
                            <th style="width: 35%;">Material</th>
                            <th style="width: 15%;">Jumlah</th>
                            <th style="width: 15%;">Satuan</th>
                            <th>Keterangan</th>
                            <th style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="materialBody">
                        <tr>
                            <td>
                                <select name="id_material[]" class="select-material" onchange="isiSatuan(this)" required>
                                    <option value="">-- Pilih Material --</option>
                                    @foreach($materials as $material)
                                        <option 
                                            value="{{ $material->id_material }}"
                                            data-satuan="{{ $material->satuan }}">
                                            [{{ $material->kode_material }}] {{ $material->nama_material }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="number" name="jumlah[]" min="1" placeholder="Masukkan jumlah" required>
                            </td>

                            <td>
                                <input type="text" class="satuan-field" readonly>
                            </td>

                            <td>
                                <input type="text" name="keterangan[]" placeholder="Opsional">
                            </td>

                            <td style="text-align:center;">
                                <button type="button" class="btn-hapus-row" onclick="hapusBaris(this)">Hapus</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="form-grid" style="margin: 20px 28px;">
                <div class="form-group">
                    <label>Upload Foto Dokumentasi</label>
                    <input type="file" name="foto_dokumentasi[]" multiple accept="image/*">
                </div>

                <div class="form-group">
                    <label>Upload Dokumen</label>
                    <input type="file" name="dokumen[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                </div>
            </div>
            
            <div class="form-actions">
                <a href="{{ route('material.masuk') }}" class="btn-kembali">← Kembali</a>

                <div>
                    <button type="reset" class="btn-reset">Reset</button>
                    <button type="submit" class="btn-update">✓ Simpan Semua</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function isiSatuan(select) {
        let selected = select.options[select.selectedIndex];
        let satuan = selected.getAttribute('data-satuan') || '';

        let row = select.closest('tr');
        row.querySelector('.satuan-field').value = satuan;
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
</script>
<script>
function isiSatuan(select) {
    let selected = select.options[select.selectedIndex];
    let satuan = selected.getAttribute('data-satuan') || '';

    let row = select.closest('.material-row');
    row.querySelector('.satuan-field').value = satuan;
}
</script>
</body>
</html>
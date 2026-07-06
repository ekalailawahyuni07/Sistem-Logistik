<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Material Admin</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

<div class="content">

    <div class="form-card">

        <div class="form-header">
            <div>
                <h1>📦 Tambah Material Admin</h1>
                <p>Silahkan isi data material baru.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.data.material.store') }}">
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label>Kode Material</label>
                    <input type="text" name="kode_material" placeholder="Masukkan kode material" required>
                </div>

                <div class="form-group">
                    <label>Nama Material</label>
                    <input type="text" name="nama_material" placeholder="Masukkan nama material" required>
                </div>

                <div class="form-group">
                    <label>Jenis Material</label>
                    <input type="text" name="jenis_material" placeholder="Masukkan jenis material" required>
                </div>

                <div class="form-group">
                    <label>Satuan</label>
                    <input type="text" name="satuan" placeholder="Contoh : Batang" required>
                </div>

                <div class="form-group full">
                    <label>Keterangan</label>
                    <textarea name="keterangan" rows="5" placeholder="Masukkan keterangan..."></textarea>
                </div>

            </div>

            <div class="form-footer">
                <a href="{{ route('admin.data.material') }}" class="btn-kembali">
                    ← Kembali
                </a>

                <button type="submit" class="btn-simpan">
                    💾 Simpan
                </button>
            </div>

        </form>

    </div>

</div>

</body>
</html>
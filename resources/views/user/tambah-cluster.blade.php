<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Cluster</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

<div class="content">

    <div class="form-card">

        <div class="form-header">
            <div>
                <h1>Tambah Cluster</h1>
                <p>Silahkan isi data cluster baru.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('cluster.store') }}">
            @csrf

            <div class="form-grid">

                <div class="form-grid">

                    <div class="form-group">
                        <label>ID Cluster</label>
                        <input
                            type="text"
                            name="kode_cluster"
                            placeholder="Contoh: CL001"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Nama Cluster</label>
                        <input
                            type="text"
                            name="nama_cluster"
                            placeholder="Masukkan nama cluster"
                            required>
                    </div>

                </div>

            </div>

            <div class="form-footer">

                <a href="{{ route('cluster') }}" class="btn-kembali">
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
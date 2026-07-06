<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Cluster</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

<div class="content">

    <div class="form-card">

        <div class="form-header">
            <div>
                <h1>Edit Cluster</h1>
                <p>Silahkan ubah data cluster.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.cluster.update', $cluster->id_cluster) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group">
                    <label>ID Cluster</label>
                    <input
                        type="text"
                        name="kode_cluster"
                        value="{{ $cluster->kode_cluster }}"
                        required>
                </div>

                <div class="form-group">
                    <label>Nama Cluster</label>
                    <input
                        type="text"
                        name="nama_cluster"
                        value="{{ $cluster->nama_cluster }}"
                        required>
                </div>

            </div>

            <div class="form-footer">

                <a href="{{ route('admin.cluster') }}" class="btn-kembali">
                    ← Kembali
                </a>

                <button type="submit" class="btn-update">
                    ✓ Update
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>
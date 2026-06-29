<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Cluster</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

<div class="content">

    <div class="card">

        <h1>📋 Detail Cluster</h1>

        <hr>

        <p><strong>ID Cluster :</strong> {{ $cluster->kode_cluster }}</p>

        <p><strong>Nama Cluster :</strong> {{ $cluster->nama_cluster }}</p>

        <br>

        <a href="{{ route('cluster') }}" class="btn-kembali">
            ← Kembali
        </a>

    </div>

</div>

</body>
</html>
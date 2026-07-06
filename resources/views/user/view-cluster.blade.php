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

        <p><strong>Kode Cluster :</strong> {{ $cluster->kode_cluster }}</p>

        <p><strong>Nama Cluster :</strong> {{ $cluster->nama_cluster }}</p>

        <p><strong>Area :</strong> {{ $cluster->area->nama_area ?? '-' }}</p>

        <br>

        <h2>Riwayat Material Keluar</h2>

        <table class="material-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>No Bukti</th>
                    <th>Material</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Penerima</th>
                    <th>Project</th>
                </tr>
            </thead>

            <tbody>

                @forelse($materialKeluar as $item)

                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->tgl_transaksi }}</td>
                    <td>{{ $item->no_bukti }}</td>
                    <td>{{ $item->material->nama_material ?? '-' }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>{{ $item->material->satuan ?? '-' }}</td>
                    <td>{{ $item->nama_penerima }}</td>
                    <td>{{ $item->project }}</td>
                </tr>

                @empty

                <tr>
                    <td colspan="8" class="text-center">
                        Belum ada material keluar pada cluster ini.
                    </td>
                </tr>

                @endforelse

            </tbody>
        </table>

        <br>

        <a href="{{ route('cluster') }}" class="btn-kembali">
            ← Kembali
        </a>

    </div>

</div>

</body>
</html>
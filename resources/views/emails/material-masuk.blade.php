<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Notifikasi Material Masuk</title>
</head>

<body style="margin:0;background:#f4f6f9;font-family:Arial,sans-serif;">

<div style="max-width:700px;margin:30px auto;background:#ffffff;border-radius:10px;overflow:hidden;border:1px solid #ddd;">

    <div style="background:#081f4d;padding:20px;color:white;">
        <h2 style="margin:0;">
            📦 Material Masuk
        </h2>

        <p style="margin:7px 0 0;">
            PT. Technology Karya Mandiri
        </p>
    </div>

    <div style="padding:25px;">
        <h3>Halo Admin,</h3>

        <p>
            Terdapat transaksi material masuk baru yang telah disimpan ke sistem.
        </p>

        <table width="100%" cellpadding="11" style="border-collapse:collapse;">
            <tr style="background:#f7f7f7;">
                <td width="35%"><b>Kode Material</b></td>
                <td>{{ $transaksi->material->kode_material ?? '-' }}</td>
            </tr>

            <tr>
                <td><b>Nama Material</b></td>
                <td>{{ $transaksi->material->nama_material ?? '-' }}</td>
            </tr>

            <tr style="background:#f7f7f7;">
                <td><b>Jumlah</b></td>
                <td>
                    {{ $transaksi->jumlah }}
                    {{ $transaksi->material->satuan ?? '' }}
                </td>
            </tr>

            <tr>
                <td><b>Tanggal</b></td>
                <td>{{ $transaksi->tgl_transaksi }}</td>
            </tr>

            <tr style="background:#f7f7f7;">
                <td><b>No. Bukti</b></td>
                <td>{{ $transaksi->no_bukti ?? '-' }}</td>
            </tr>

            <tr>
                <td><b>Cluster</b></td>
                <td>
                    {{ $transaksi->material->cluster->nama_cluster
                        ?? $transaksi->cluster->nama_cluster
                        ?? '-' }}
                </td>
            </tr>

            <tr style="background:#f7f7f7;">
                <td><b>Petugas</b></td>
                <td>{{ $transaksi->user->nama_user ?? '-' }}</td>
            </tr>

            <tr>
                <td><b>Keterangan</b></td>
                <td>{{ $transaksi->keterangan ?? '-' }}</td>
            </tr>
        </table>

        <div style="margin-top:25px;padding:15px;background:#fff8e1;border-left:5px solid #f28c28;">
            Email ini dikirim otomatis oleh Sistem MATRILOG.
        </div>
    </div>

    <div style="background:#081f4d;color:white;text-align:center;padding:15px;font-size:13px;">
        © {{ date('Y') }} PT. Technology Karya Mandiri
    </div>
</div>

</body>
</html>
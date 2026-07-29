<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peringatan Stok Menipis</title>
</head>

<body style="margin:0;background:#f4f6f9;font-family:Arial,sans-serif;">

<div style="max-width:700px;margin:30px auto;background:#ffffff;border-radius:10px;overflow:hidden;border:1px solid #ddd;">

    <div style="background:#b91c1c;padding:20px;color:white;">
        <h2 style="margin:0;">
            ⚠️ Peringatan Stok Material
        </h2>

        <p style="margin:7px 0 0;">
            Stok material sudah mencapai batas minimum
        </p>
    </div>

    <div style="padding:25px;">
        <h3>Halo Admin,</h3>

        <p>
            Sistem mendeteksi bahwa stok material berikut hampir habis.
        </p>

        <table width="100%" cellpadding="11" style="border-collapse:collapse;">
            <tr style="background:#f7f7f7;">
                <td width="35%"><b>Kode Material</b></td>
                <td>{{ $material->kode_material }}</td>
            </tr>

            <tr>
                <td><b>Nama Material</b></td>
                <td>{{ $material->nama_material }}</td>
            </tr>

            <tr style="background:#f7f7f7;">
                <td><b>Stok Tersisa</b></td>
                <td style="color:#dc2626;font-weight:bold;">
                    {{ $material->stok }} {{ $material->satuan }}
                </td>
            </tr>

            <tr>
                <td><b>Cluster</b></td>
                <td>{{ $material->cluster->nama_cluster ?? '-' }}</td>
            </tr>

            <tr style="background:#f7f7f7;">
                <td><b>Area</b></td>
                <td>{{ $material->cluster->area->nama_area ?? '-' }}</td>
            </tr>
        </table>

        <div style="margin-top:25px;padding:15px;background:#fee2e2;border-left:5px solid #dc2626;color:#991b1b;">
            Segera lakukan penambahan stok agar kegiatan operasional tidak terganggu.
        </div>
    </div>

    <div style="background:#081f4d;color:white;text-align:center;padding:15px;font-size:13px;">
        © {{ date('Y') }} PT. Technology Karya Mandiri
    </div>
</div>

</body>
</html>
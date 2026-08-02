<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Material - Area {{ $namaArea }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #061b40;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            color: #061b40;
            text-transform: uppercase;
        }
        .header p {
            margin: 4px 0 0 0;
            font-size: 12px;
            color: #555;
        }
        .meta-info {
            margin-bottom: 15px;
            font-size: 11px;
        }
        .meta-info table {
            width: 100%;
            border: none;
        }
        .meta-info td {
            border: none;
            padding: 2px 0;
            text-align: left;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th {
            background-color: #061b40;
            color: #ffffff;
            font-weight: bold;
            padding: 8px 6px;
            border: 1px solid #061b40;
            text-align: center;
            font-size: 10px;
        }
        table.data-table td {
            padding: 6px;
            border: 1px solid #cbd5e0;
            text-align: center;
            font-size: 10.5px;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-left { text-align: left !important; }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .footer {
            margin-top: 30px;
            width: 100%;
            font-size: 10px;
        }
        .footer table {
            width: 100%;
            border: none;
        }
        .footer td {
            border: none;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>PT. TECHNOLOGY KARYA MANDIRI</h2>
        <p>Laporan Rekapitulasi Stok Material — Area {{ $namaArea }}</p>
    </div>

    <div class="meta-info">
        <table>
            <tr>
                <td style="width: 50%;"><strong>Petugas Logistik:</strong> {{ $user->nama_user }}</td>
                <td style="width: 50%; text-align: right;"><strong>Tanggal Cetak:</strong> {{ date('d-m-Y H:i') }}</td>
            </tr>
            @if($selectedProject)
            <tr>
                <td colspan="2"><strong>Filter Project:</strong> {{ $selectedProject }}</td>
            </tr>
            @endif
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 18%;">Kode Material</th>
                <th style="width: 25%;">Nama Material</th>
                <th style="width: 15%;">Project</th>
                <th style="width: 10%;">Satuan</th>
                <th style="width: 8%;">IN</th>
                <th style="width: 8%;">OUT</th>
                <th style="width: 12%;">Sisa Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse($materials as $material)
                @php
                    $masuk = $material->total_masuk ?? 0;
                    $keluar = $material->total_keluar ?? 0;
                    $stock = $masuk - $keluar;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $material->kode_material }}</td>
                    <td class="text-left">{{ $material->nama_material }}</td>
                    <td class="text-left">{{ $material->project_display ?? $material->project ?? '-' }}</td>
                    <td>{{ $material->satuan }}</td>
                    <td class="text-right">{{ number_format($masuk) }}</td>
                    <td class="text-right">{{ number_format($keluar) }}</td>
                    <td class="text-right" style="font-weight: bold; {{ $stock <= 10 ? 'color: #c53030;' : '' }}">
                        {{ number_format($stock) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada data stok material</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <table>
            <tr>
                <td style="width: 70%;"></td>
                <td style="width: 30%;">
                    <p>Petugas Logistik Area {{ $namaArea }},</p>
                    <br><br><br>
                    <p style="font-weight: bold; text-decoration: underline;">{{ $user->nama_user }}</p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>

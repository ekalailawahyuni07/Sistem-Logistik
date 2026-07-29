<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Surat Jalan Admin</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <style>
        .surat-container {
            background: white;
            border: 2px solid #333;
            padding: 25px;
            max-width: 1000px;
            margin: 30px auto;
        }

        .surat-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 25px;
            text-transform: uppercase;
            text-align: center;
        }

        .surat-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 60px;
            margin-bottom: 20px;
        }

        .surat-info p {
            margin: 4px 0;
            font-size: 16px;
        }

        .surat-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .surat-table th,
        .surat-table td {
            border: 1px solid #333;
            padding: 10px;
            text-align: center;
        }

        .surat-table th {
            background: #e5e7eb;
        }

        .ttd-area {
            display: grid;
            grid-template-columns: repeat(3,1fr);
            gap: 30px;
            text-align: center;
            margin-top: 70px;
        }

        .ttd-box{
            min-height:140px;
        }

        .btn-print{
            background:#061b40;
            color:white;
            border:none;
            padding:10px 18px;
            border-radius:6px;
            cursor:pointer;
            font-weight:bold;
        }

        @media print{
            .btn-print,
            .btn-kembali{
                display:none;
            }

            body{
                background:white;
            }

            .surat-container{
                margin:0;
                max-width:100%;
            }
        }

    </style>

</head>
<body>

<div class="content">

    <div style="max-width:1000px;margin:30px auto 10px;display:flex;justify-content:space-between;">

        <a href="{{ route('admin.surat.jalan') }}" class="btn-kembali">
            ← Kembali
        </a>

        <button onclick="window.print()" class="btn-print">
            🖨 Cetak Surat Jalan
        </button>

    </div>

    <div class="surat-container">

        <div class="surat-title">
            SURAT JALAN / PENGAMBILAN MATERIAL
        </div>

        <div class="surat-info">

            <div>

                <p><strong>Tanggal</strong> :
                    {{ $transaksi->tgl_transaksi }}
                </p>

                <p><strong>Nama Proyek</strong> :
                    {{ $transaksi->project ?? '-' }}
                </p>

                <p><strong>Lokasi Proyek</strong> :
                    {{ $transaksi->cluster->nama_cluster ?? '-' }}
                </p>

            </div>

            <div>

                <p><strong>Kepada</strong> :
                    {{ $transaksi->nama_penerima ?? '-' }}
                </p>

                <p><strong>No. HP</strong> :
                    {{ $transaksi->no_hp ?? '-' }}
                </p>

                <p><strong>No. SJ</strong> :
                    {{ $transaksi->no_bukti }}
                </p>

            </div>

        </div>

        <p style="margin-top:15px;">

            Kami kirimkan barang-barang di bawah ini dengan kendaraan

            <strong>{{ $transaksi->kendaraan ?? '-' }}</strong>

            plat nomor

            <strong>{{ $transaksi->plat_nomor ?? '-' }}</strong>

        </p>

        <table class="surat-table">

            <thead>

            <tr>

                <th>No</th>
                <th>Nama Barang</th>
                <th>Volume</th>
                <th>Satuan</th>
                <th>Keterangan</th>

            </tr>

            </thead>

            <tbody>

            @foreach($items as $item)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $item->material->nama_material ?? '-' }}</td>

                    <td>{{ $item->jumlah }}</td>

                    <td>{{ $item->material->satuan ?? '-' }}</td>

                    <td>{{ $item->keterangan ?? '-' }}</td>

                </tr>

            @endforeach

            @for($i=$items->count();$i<8;$i++)

                <tr>

                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                </tr>

            @endfor

            </tbody>

        </table>

        <div class="ttd-area">

            <div class="ttd-box">

                <p><strong>Yang Menyerahkan</strong></p>

                <br><br><br>

                <p>
                    <strong>
                        {{ $transaksi->user->nama_user ?? '-' }}
                    </strong>
                </p>

                <small>Petugas</small>

            </div>

            <div class="ttd-box">

                <p><strong>Yang Menerima</strong></p>

                <br><br><br>

            <p>
                <strong>
                    {{ $transaksi->nama_sopir ?? '-' }}
                </strong>
            </p>

            <small>Yang Menerima</small>

            </div>

            <div class="ttd-box">

                <p><strong>Mengetahui</strong></p>

                <br><br><br>

                <p>
                    <strong>
                        Dimas Bimantoro
                    </strong>
                </p>

                <small>Admin Gudang</small>

            </div>

        </div>

    </div>

</div>

</body>
</html>
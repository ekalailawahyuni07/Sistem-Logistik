<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Material Keluar</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

<div class="content">
    <div class="form-card">
        <div class="form-header">
            <div>
                <h1>Edit Material Keluar</h1>
                <p>Perbarui data material keluar.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('material.keluar.update', $transaksi->id_transaksi) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label>
                        Tanggal <span style="color:red;">*</span>
                    </label>
                    <input type="date" name="tanggal" value="{{ \Carbon\Carbon::parse($transaksi->tgl_transaksi)->format('Y-m-d') }}" required>                
                </div>
                <div class="form-group">
                    <label>
                        No Surat Jalan <span style="color:red;">*</span>
                    </label>
                    <input type="text" name="no_bukti" value="{{ $transaksi->no_bukti }}" required>
                </div>

                <div class="form-group">
                    <label>Material</label>
                    <select name="id_material" id="id_material" onchange="isiSatuan()" required>
                        @foreach($materials as $material)
                            <option
                                value="{{ $material->id_material }}"
                                data-satuan="{{ $material->satuan }}"
                                {{ $transaksi->id_material == $material->id_material ? 'selected' : '' }}>
                                [{{ $material->kode_material }}] {{ $material->nama_material }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>
                        Jumlah <span style="color:red;">*</span>
                    </label>
                    <input type="number" name="jumlah" min="1" value="{{ $transaksi->jumlah }}" required>
                </div>

                <div class="form-group">
                    <label>Satuan</label>
                    <input type="text" id="satuan" value="{{ $transaksi->material->satuan ?? '' }}" readonly>
                </div>

                <div class="form-group">
                    <label>
                        Cluster <span style="color:red;">*</span>
                    </label>
                    <select name="id_cluster" required>
                        @foreach($clusters as $cluster)
                            <option
                                value="{{ $cluster->id_cluster }}"
                                {{ $transaksi->id_cluster == $cluster->id_cluster ? 'selected' : '' }}>
                                [{{ $cluster->kode_cluster }}] {{ $cluster->nama_cluster }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>
                        Project <span style="color:red;">*</span>
                    </label>

                    <select name="project" required>

                        <option value="">
                            -- Pilih Project --
                        </option>

                        @foreach($projects as $project)

                            <option
                                value="{{ $project->project }}"
                                {{ $transaksi->project == $project->project ? 'selected' : '' }}>

                                {{ $project->project }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="form-group">
                    <label>
                        Nama Penerima <span style="color:red;">*</span>
                    </label>
                    <input type="text" name="nama_penerima" value="{{ $transaksi->nama_penerima }}" required>
                </div>

                <div class="form-group">
                    <label>
                        Yang Menerima <span style="color:red;">*</span>
                    </label>

                    <input
                        type="text"
                        name="nama_sopir"
                        class="form-control"
                        value="{{ old('nama_sopir', $transaksi->nama_sopir) }}"
                        placeholder="Masukkan nama yang menerima"
                        required>
                </div>

            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" rows="5">{{ $transaksi->keterangan }}</textarea>
            </div>

            <div class="card" style="margin: 20px 28px;">
                <h2>Dokumen / Foto Saat Ini</h2>

                <table class="material-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama File</th>
                            <th>Keterangan</th>
                            <th>Lihat</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($transaksi->dokumentasiTransaksi as $dok)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ basename($dok->file_dokumentasi) }}</td>
                                <td>{{ $dok->keterangan ?? '-' }}</td>
                                <td>
                                    <a href="{{ asset('storage/' . $dok->file_dokumentasi) }}" target="_blank" class="btn-view">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center;">
                                    Belum ada dokumen/foto
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="form-grid" style="margin: 20px 28px;">
                <div class="form-group" style="margin-top:20px;">

                    <label>
                        Upload Foto Dokumentasi Pengeluaran Material Baru 
                    </label>

                    <input
                        type="file"
                        name="dokumen[]"
                        multiple
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">

                    <small style="color:#6b7280;font-size:13px;">
                        (Opsional - PDF / DOC / DOCX / JPG / PNG)
                    </small>

                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('material.keluar') }}" class="btn-kembali">← Kembali</a>

                <div>
                    <button type="reset" class="btn-reset">Reset</button>
                    <button type="submit" class="btn-update">✓ Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function isiSatuan() {
    let select = document.getElementById('id_material');
    let selected = select.options[select.selectedIndex];

    document.getElementById('satuan').value = selected.getAttribute('data-satuan') || '';
}
</script>

</body>
</html>
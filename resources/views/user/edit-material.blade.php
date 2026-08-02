<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Material</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .form-header h1,
        .form-header h2,
        .form-group label,
        .topbar h1,
        .form-group input,
        .form-group select,
        .form-group textarea {
            color: #000000 !important;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="logo">
        <img src="{{ asset('images/logo-tkm.png') }}" alt="Logo PT">
    </div>

    <a href="{{ route('profile.edit') }}" style="text-decoration:none; color:inherit;">
        <div class="profile">
            @if(Auth::user()->foto_profile)
                <img src="{{ asset('storage/' . Auth::user()->foto_profile) }}" class="profile-img">
            @else
                <div class="avatar">👤</div>
            @endif

            <h4>{{ Auth::user()->nama_user }}</h4>
            <p>{{ Auth::user()->email }}</p>
        </div>
    </a>

    <div class="menu">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('data.material') }}">Master Data Material</a>
        <a href="{{ route('material.masuk') }}">Material Masuk</a>
        <a href="{{ route('material.keluar') }}">Material Keluar</a>
        <a href="{{ route('stok.material') }}">Stok Material</a>
        <a href="{{ route('cluster') }}">Daftar Kluster</a>
        <a href="{{ route('dokumen') }}">Dokumen</a>
        <a href="{{ route('surat.jalan') }}">Surat Jalan</a>
    </div>

    <div class="logout">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">Keluar</button>
        </form>
    </div>
</div>

<div class="content">

    <div class="form-card">
        <div class="form-header">
            <div>
                <h2>Edit Material</h2>
                <p>Perbarui informasi material pada form di bawah ini.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('data.material.update', $material->id_material) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label>Kode Material <span style="color:red;">*</span></label>
                    <input type="text" name="kode_material" value="{{ old('kode_material', $material->kode_material) }}" required>
                </div>

                <div class="form-group">
                    <label>Nama Material <span style="color:red;">*</span></label>
                    <input type="text" name="nama_material" value="{{ old('nama_material', $material->nama_material) }}" required>
                </div>

                <div class="form-group">
                    <label>Project <span style="color:red;">*</span></label>

                    <input
                        type="text"
                        name="project"
                        value="{{ old('project', $material->project) }}"
                        placeholder="Masukkan nama project"
                        required>
                </div>

                <div class="form-group">
                   <label>Jenis Material <span style="color:red;">*</span></label>
                    <select name="jenis_material" required>

                        <option value="">-- Pilih Jenis Material --</option>

                        <option value="Tiang"
                            {{ old('jenis_material', $material->jenis_material) == 'Tiang' ? 'selected' : '' }}>
                            Tiang
                        </option>

                        <option value="Aksesoris"
                            {{ old('jenis_material', $material->jenis_material) == 'Aksesoris' ? 'selected' : '' }}>
                            Aksesoris
                        </option>

                        <option value="Kabel"
                            {{ old('jenis_material', $material->jenis_material) == 'Kabel' ? 'selected' : '' }}>
                            Kabel
                        </option>

                        <option value="ODP"
                            {{ old('jenis_material', $material->jenis_material) == 'ODP' ? 'selected' : '' }}>
                            ODP
                        </option>

                        <option value="Lainnya"
                            {{ old('jenis_material', $material->jenis_material) == 'Lainnya' ? 'selected' : '' }}>
                            Lainnya
                        </option>

                    </select>

                </div>

                <div class="form-group">
                    <label>Satuan <span style="color:red;">*</span></label>
                    <input type="text" name="satuan" value="{{ old('satuan', $material->satuan) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" rows="5">{{ old('keterangan', $material->keterangan) }}</textarea>
            </div>

            <div class="form-actions">
                <a href="{{ route('data.material') }}" class="btn-kembali">← Kembali</a>

                <div>
                    <button type="reset" class="btn-reset">Reset</button>
                    <button type="submit" class="btn-update">✓ Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

</body>
</html>
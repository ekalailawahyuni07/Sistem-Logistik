<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Area Admin</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

<div class="content">
    <div class="form-card">
        <div class="form-header">
            <div class="form-icon">✏️</div>

            <div>
                <h2>Edit Area</h2>
                <p>Perbarui kode dan nama area.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="alert" style="background:#fee2e2; color:#991b1b;">
                <strong>Data belum dapat diperbarui.</strong>

                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('admin.kelola.area.update', $area->id_area) }}"
        >
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label for="kode_area">Kode Area</label>

                    <input
                        type="text"
                        id="kode_area"
                        name="kode_area"
                        value="{{ old('kode_area', $area->kode_area) }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="nama_area">Nama Area</label>

                    <input
                        type="text"
                        id="nama_area"
                        name="nama_area"
                        value="{{ old('nama_area', $area->nama_area) }}"
                        required
                    >
                </div>
            </div>

            <div class="form-actions">
                <a
                    href="{{ route('admin.kelola.area') }}"
                    class="btn-kembali"
                >
                    ← Kembali
                </a>

                <div>
                    <button type="reset" class="btn-reset">
                        Reset
                    </button>

                    <button type="submit" class="btn-update">
                        ✓ Update
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

</body>
</html>
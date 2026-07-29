<section class="profile-form-section">

    <div class="profile-form-header">
        <div class="profile-icon">👤</div>
        <div>
            <h2>Informasi Profile</h2>
            <p>Ubah foto profile, nama, email, nomor telepon, dan alamat akun.</p>
        </div>
    </div>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="form-upload-area">
            <div class="upload-wrapper">
                @if($user->foto_profile)
                    <img
                        id="previewFoto"
                        src="{{ asset('storage/' . $user->foto_profile) }}"
                        class="upload-preview"
                        alt="Foto Profile">
                @else
                    <img
                        id="previewFoto"
                        src=""
                        class="upload-preview"
                        alt="Foto Profile"
                        style="display:none;">
                @endif

                <label for="foto_profile" class="upload-box">
                    <span>☁️</span>
                    <strong>Pilih atau upload foto profile</strong>
                    <small>PNG, JPG, JPEG maks. 2MB</small>
                </label>

                <input
                    id="foto_profile"
                    name="foto_profile"
                    type="file"
                    hidden
                    accept="image/*"
                    onchange="previewProfile(event)">
            </div>

            @error('foto_profile')
                <small class="error-text">{{ $message }}</small>
            @enderror
        </div>

        <div class="profile-form-grid">
            <div class="form-group">
                <label>Nama User <span style="color:red;">*</span></label>
                <div class="input-icon">
                    <span>👤</span>
                    <input type="text" name="nama_user" value="{{ old('nama_user', $user->nama_user) }}" required>
                </div>
                @error('nama_user')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Email <span style="color:red;">*</span></label>
                <div class="input-icon">
                    <span>✉️</span>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>
                @error('email')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>
                    Nomor Telepon <span style="color:red;">*</span>
                </label>

                <div class="input-icon">
                    <span>📞</span>

                    <input
                        type="text"
                        name="no_telp"
                        value="{{ old('no_telp', $user->no_telp) }}"
                        placeholder="Contoh: 081234567890"
                        minlength="11"
                        maxlength="13"
                        required>
                </div>

                <small style="color:#6b7280;">
                    Minimal 11 digit, maksimal 13 digit.
                </small>

                @error('no_telp')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <div class="input-icon textarea-box">
                    <span>📍</span>
                    <textarea name="alamat" placeholder="Masukkan alamat lengkap">{{ old('alamat', $user->alamat) }}</textarea>
                </div>
                @error('alamat')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="profile-actions">
            <button type="submit" class="btn-profile-save">💾 Simpan Profile</button>
            <a href="{{ route('dashboard') }}" class="btn-profile-cancel">↻ Batal</a>
        </div>

        @if (session('status') === 'profile-updated')
            <p class="success-text">Profile berhasil disimpan.</p>
        @endif
    </form>

    <script>
        function previewProfile(event) {
            const file = event.target.files[0];

            if (!file) return;

            const url = URL.createObjectURL(file);

            const previewFoto = document.getElementById('previewFoto');
            const previewAvatar = document.getElementById('previewAvatar');
            const bigProfile = document.getElementById('bigProfile');

            if (previewAvatar) {
                previewAvatar.style.display = 'none';
            }

            if (previewFoto) {
                previewFoto.src = url;
                previewFoto.style.display = 'flex';
            }

            if (bigProfile) {
                bigProfile.src = url;
            }
        }
    </script>

</section>
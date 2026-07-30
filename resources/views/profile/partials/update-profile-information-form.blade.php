<section class="profile-form-section">

    <div class="profile-form-header">
        <div>
            <h2 style="color: #0b2857;">Informasi Profile</h2>
            <p>Ubah foto profile, nama, email, nomor telepon, dan alamat akun.</p>
        </div>
    </div>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="form-upload-area" style="margin-bottom: 25px;">
            <div class="upload-wrapper" style="display: flex; align-items: center; gap: 20px; padding: 0; grid-template-columns: none;">
                @if($user->foto_profile)
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                        <img
                            id="previewFoto"
                            src="{{ asset('storage/' . $user->foto_profile) }}"
                            class="upload-preview"
                            alt="Foto Profile"
                            style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #0b2857;">

                        <button type="button" onclick="if(confirm('Apakah Anda yakin ingin menghapus foto profile?')) { document.getElementById('formHapusFoto').submit(); }" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                            🗑️ Hapus Foto
                        </button>
                    </div>
                @else
                    <img
                        id="previewFoto"
                        src=""
                        class="upload-preview"
                        alt="Foto Profile"
                        style="display:none; width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #0b2857;">
                @endif

                <label for="foto_profile" class="upload-box" style="flex: 1; margin: 0; padding: 18px 22px;">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <span style="font-size: 32px;">☁️</span>
                        <div>
                            <strong style="color: #0b2857; font-size: 15px; display: block; margin: 0;">Pilih atau upload foto profile</strong>
                            <small style="color: #64748b; font-size: 13px;">PNG, JPG, JPEG maks. 2MB</small>
                        </div>
                    </div>
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
                <small class="error-text" style="color: #dc2626; display: block; margin-top: 5px;">{{ $message }}</small>
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
        @if (session('status') === 'profile-photo-deleted')
            <p class="success-text" style="color: #16a34a; font-weight: 600; margin-top: 10px;">Foto profile berhasil dihapus.</p>
        @endif
    </form>

    @if($user->foto_profile)
        <form id="formHapusFoto" method="POST" action="{{ route('profile.photo.destroy') }}" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    @endif

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
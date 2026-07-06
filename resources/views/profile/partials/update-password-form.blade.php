<section class="password-form-section">

    <div class="profile-form-header">
        <div class="profile-icon">🔒</div>
        <div>
            <h2>Update Password</h2>
            <p>Gunakan password yang kuat agar akun tetap aman.</p>
        </div>
    </div>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="profile-form-grid">

            <div class="form-group">
                <label>Password Saat Ini</label>
                <div class="input-icon">
                    <span>🔑</span>
                    <input type="password" name="current_password" autocomplete="current-password">
                </div>
                @error('current_password', 'updatePassword')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Password Baru</label>
                <div class="input-icon">
                    <span>🔐</span>
                    <input type="password" name="password" autocomplete="new-password">
                </div>
                @error('password', 'updatePassword')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label>Konfirmasi Password</label>
                <div class="input-icon">
                    <span>✅</span>
                    <input type="password" name="password_confirmation" autocomplete="new-password">
                </div>
                @error('password_confirmation', 'updatePassword')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>

        </div>

        <div class="profile-actions">
            <button type="submit" class="btn-profile-save">🔒 Simpan Password</button>
        </div>

        @if (session('status') === 'password-updated')
            <p class="success-text">Password berhasil diperbarui.</p>
        @endif
    </form>

</section>
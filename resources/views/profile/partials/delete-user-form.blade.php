<section class="delete-user-section">

    <div class="profile-form-header">
        <div class="profile-icon" style="background: #fee2e2; color: #dc2626;">🗑️</div>
        <div>
            <h2 style="color: #dc2626;">Hapus Akun / Profile</h2>
            <p>Setelah akun Anda dihapus, semua data dan informasi akun akan dihapus secara permanen.</p>
        </div>
    </div>

    <div style="padding-top: 20px;">
        <button type="button" onclick="bukaModalHapusAkun()" style="background-color: #dc2626 !important; color: #ffffff !important; border: none !important; padding: 12px 24px !important; border-radius: 8px !important; font-weight: 600 !important; font-size: 15px !important; cursor: pointer !important; display: inline-flex !important; align-items: center !important; gap: 8px !important; text-decoration: none !important;">
            🗑️ Hapus Akun Saya
        </button>
    </div>

    <!-- Modal Konfirmasi Hapus Akun -->
    <div id="modalHapusAkun" class="modal-hapus" style="{{ $errors->userDeletion->isNotEmpty() ? 'display:flex;' : 'display:none;' }}">
        <div class="modal-box">
            <div class="modal-icon logout-icon" style="background-color: #fee2e2 !important; color: #dc2626 !important;">
                ⚠️
            </div>

            <h2 style="color: #061b40 !important; margin: 0 0 10px 0 !important; font-size: 22px !important; font-weight: 700 !important;">Konfirmasi Hapus Akun</h2>

            <p style="color: #4b5563 !important; font-size: 14px !important; margin-bottom: 20px !important; line-height: 1.5 !important;">
                Apakah Anda yakin ingin menghapus akun Anda? Semua data akan dihapus secara permanen. Masukkan password Anda untuk mengonfirmasi.
            </p>

            <form method="post" action="{{ route('profile.destroy') }}" id="formHapusAkun">
                @csrf
                @method('delete')

                <div class="form-group" style="margin-bottom: 20px; text-align: left;">
                    <label style="color: #000000 !important; display: block; margin-bottom: 8px; font-weight: 600;">Password Saat Ini</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="Masukkan password Anda"
                        style="width: 100% !important; padding: 12px !important; border: 1px solid #cbd5e1 !important; border-radius: 8px !important; box-sizing: border-box !important; color: #000000 !important; background-color: #ffffff !important;"
                        required>

                    @if($errors->userDeletion->get('password'))
                        <small class="error-text" style="color: #dc2626 !important; display: block; margin-top: 5px;">
                            {{ $errors->userDeletion->get('password')[0] }}
                        </small>
                    @endif
                </div>

                <div class="modal-actions" style="display: flex !important; justify-content: center !important; gap: 12px !important; margin-top: 20px !important;">
                    <button
                        type="button"
                        onclick="tutupModalHapusAkun()"
                        style="background-color: #6b7280 !important; color: #ffffff !important; border: none !important; padding: 10px 22px !important; border-radius: 8px !important; font-weight: bold !important; cursor: pointer !important;"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        style="background-color: #dc2626 !important; color: #ffffff !important; border: none !important; padding: 10px 22px !important; border-radius: 8px !important; font-weight: bold !important; cursor: pointer !important;"
                    >
                        Ya, Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function bukaModalHapusAkun() {
        document.getElementById("modalHapusAkun").style.display = "flex";
    }

    function tutupModalHapusAkun() {
        document.getElementById("modalHapusAkun").style.display = "none";
    }
    </script>

</section>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MATRILOG - Login</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">

    @vite('resources/css/app.css')

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
        .font-poppins {
            font-family: 'Poppins', sans-serif;
        }
        .font-montserrat {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen flex">

        <!-- ==================== LEFT PANEL - BRANDING ==================== -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-white">
            
            <!-- Setengah elips - tegas tapi smooth -->
            <div class="absolute top-0 bottom-0 left-0 bg-[#0a0f3d]" style="right: 0.5%; clip-path: ellipse(100% 100% at 0% 50%);"></div>

            <div class="relative z-10 flex flex-col justify-between h-full p-12">

                {{-- TOP: Logo TKM (Gambar) --}}
                <div>
                    <img src="{{ asset('images/logo-tkm.png') }}" alt="PT. Technology Karya Mandiri" class="h-10 w-auto">
                </div>

                {{-- CENTER: MATRILOG (Font) - di tengah vertikal --}}
                <div class="flex flex-col items-start -mt-4" style="padding-left: 60px;">
                    <h1 class="text-white font-bold tracking-wide" style="font-family: 'Times New Roman', Times, serif; font-size: 80px;">MATRILOG</h1>
                    <p class="text-white/70 mt-2 tracking-wider" style="font-family: 'Times New Roman', Times, serif; font-size: 28px;">Material Tracking & Logistic System</p>
                </div>

                {{-- BOTTOM: Spacer --}}
                <div></div>

            </div>
        </div>

        <!-- ==================== RIGHT PANEL - LOGIN FORM ==================== -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
            <div class="w-full max-w-md">

                <h2 class="text-3xl font-bold text-gray-900 mb-8 font-poppins">Masuk</h2>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    {{-- Nama Pengguna --}}
                    <div>
                        <label for="nama_user" class="block text-sm font-medium text-gray-700 mb-1 font-montserrat">Nama Pengguna</label>
                        <input 
                            id="nama_user" 
                            name="nama_user" 
                            type="text" 
                            placeholder="Masukkan Nama Pengguna" 
                            value="{{ old('nama_user') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a237e] focus:border-transparent outline-none transition font-montserrat @error('nama_user') border-red-500 @enderror"
                            required 
                            autofocus
                        >
                        @error('nama_user')
                            <p class="text-red-500 text-xs mt-1 font-montserrat">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kata Sandi --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1 font-montserrat">Kata Sandi</label>
                        <div class="relative">
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                placeholder="Masukkan Kata Sandi"
                                class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a237e] focus:border-transparent outline-none transition font-montserrat @error('password') border-red-500 @enderror"
                                required
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <button 
                                    type="button" 
                                    onclick="togglePasswordVisibility()" 
                                    class="text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer"
                                    title="Lihat / Sembunyikan Kata Sandi"
                                >
                                    <svg id="eyeOff" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12c1.292 4.338 5.31 7.5 10.066 7.5.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                    </svg>
                                    <svg id="eyeOn" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" class="hidden">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1 font-montserrat">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me & Forgot Password --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#1a237e] focus:ring-[#1a237e]">
                            <span class="text-sm text-gray-600 font-montserrat">Ingat Saya</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-[#1a237e] hover:underline font-montserrat font-medium">
                                Lupa Kata Sandi?
                            </a>
                        @endif
                    </div>

                    {{-- Login Button --}}
                    <button 
                        type="submit" 
                        class="w-full bg-[#1a237e] hover:bg-[#0d1557] text-white font-semibold py-3 px-4 rounded-lg transition duration-200 ease-in-out transform hover:scale-[1.02] font-poppins"
                    >
                        Masuk
                    </button>

                    {{-- Sign Up Link --}}
                    <p class="text-center text-sm text-gray-600 mt-6 font-montserrat">
                        Belum memiliki akun? 
                        <a href="{{ route('register') }}" class="text-[#1a237e] font-semibold hover:underline font-poppins">
                            Daftar Sekarang
                        </a>
                    </p>

                </form>
            </div>
        </div>

    </div>

    <script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const eyeOff = document.getElementById('eyeOff');
        const eyeOn = document.getElementById('eyeOn');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeOff.classList.add('hidden');
            eyeOn.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            eyeOff.classList.remove('hidden');
            eyeOn.classList.add('hidden');
        }
    }
    </script>

    @if (session('success') || session('status'))
        <div id="registrationModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity duration-300">
            <div class="bg-white rounded-3xl max-w-md w-full p-8 shadow-2xl text-center transform transition-all border border-gray-100 scale-100">
                
                {{-- Circular Success Icon Badge --}}
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-emerald-100 mb-5">
                    <svg class="h-10 w-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                {{-- Modal Title --}}
                <h3 class="text-2xl font-bold text-gray-900 mb-3 font-poppins">
                    Pendaftaran Berhasil!
                </h3>

                {{-- Modal Message Body --}}
                <p class="text-gray-600 text-sm leading-relaxed mb-5 font-montserrat">
                    {{ session('success') ?? session('status') }}
                </p>

                {{-- Warning Note Box --}}
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 mb-6 text-left flex items-start gap-3">
                    <p class="text-xs text-amber-800 font-montserrat leading-5">
                        <strong>Informasi:</strong> Admin akan meninjau dan menyetujui akun Anda terlebih dahulu sebelum Anda dapat masuk ke dalam sistem.
                    </p>
                </div>

                {{-- Action Button --}}
                <button 
                    type="button" 
                    onclick="closeRegistrationModal()" 
                    class="w-full bg-[#1a237e] hover:bg-[#0d1557] text-white font-semibold py-3.5 px-6 rounded-xl transition duration-200 shadow-lg shadow-indigo-950/20 font-poppins cursor-pointer"
                >
                    Saya Mengerti
                </button>

            </div>
        </div>

        <script>
            function closeRegistrationModal() {
                const modal = document.getElementById('registrationModal');
                if (modal) {
                    modal.classList.add('opacity-0');
                    setTimeout(() => modal.remove(), 300);
                }
            }
        </script>
    @endif
</body>
</html>
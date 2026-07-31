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
                                class="w-full px-4 py-3 pr-11 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a237e] focus:border-transparent outline-none transition font-montserrat @error('password') border-red-500 @enderror"
                                required
                            >
                            <button 
                                type="button" 
                                onclick="togglePasswordVisibility()" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none cursor-pointer"
                                title="Lihat / Sembunyikan Kata Sandi"
                            >
                                <span id="eyeIcon" class="text-xl select-none">🙈</span>
                            </button>
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
        const eyeIcon = document.getElementById('eyeIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.textContent = '👁️';
        } else {
            passwordInput.type = 'password';
            eyeIcon.textContent = '🙈';
        }
    }
    </script>
</body>
</html>
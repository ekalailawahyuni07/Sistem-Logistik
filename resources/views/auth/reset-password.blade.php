<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MATRILOG - Reset Kata Sandi</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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

        <!-- ==================== RIGHT PANEL - RESET PASSWORD FORM ==================== -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
            <div class="w-full max-w-md">

                <!-- Tombol Kembali -->
                <div class="mb-6">
                    <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-semibold text-[#1a237e] hover:text-[#0d1557] transition-colors group font-montserrat">
                        <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Login
                    </a>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 mb-2 font-poppins">Atur Ulang Kata Sandi</h2>
                <p class="text-gray-600 text-sm mb-6 font-montserrat leading-relaxed">
                    Silakan masukkan email dan kata sandi baru Anda.
                </p>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1 font-montserrat">Email</label>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            placeholder="Masukkan email" 
                            value="{{ old('email', $request->email) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a237e] focus:border-transparent outline-none transition font-montserrat @error('email') border-red-500 @enderror"
                            required 
                            autofocus
                            autocomplete="username"
                        >
                        @error('email')
                            <p class="text-red-500 text-xs mt-1 font-montserrat">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kata Sandi Baru --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1 font-montserrat">Kata Sandi Baru</label>
                        <div class="relative">
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                placeholder="Masukkan kata sandi baru"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a237e] focus:border-transparent outline-none transition font-montserrat pr-10 @error('password') border-red-500 @enderror"
                                required 
                                autocomplete="new-password"
                            >
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" onclick="togglePassword('password', this)">
                                <i class="far fa-eye-slash text-gray-400"></i>
                            </span>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1 font-montserrat">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Kata Sandi --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1 font-montserrat">Konfirmasi Kata Sandi Baru</label>
                        <div class="relative">
                            <input 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                type="password" 
                                placeholder="Konfirmasi kata sandi baru"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a237e] focus:border-transparent outline-none transition font-montserrat pr-10"
                                required 
                                autocomplete="new-password"
                            >
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" onclick="togglePassword('password_confirmation', this)">
                                <i class="far fa-eye-slash text-gray-400"></i>
                            </span>
                        </div>
                        @error('password_confirmation')
                            <p class="text-red-500 text-xs mt-1 font-montserrat">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <button 
                        type="submit" 
                        class="w-full bg-[#1a237e] hover:bg-[#0d1557] text-white font-semibold py-3 px-4 rounded-lg transition duration-200 ease-in-out transform hover:scale-[1.02] font-poppins mt-2"
                    >
                        Simpan Kata Sandi Baru
                    </button>

                </form>
            </div>
        </div>

    </div>

    <script>
        function togglePassword(inputId, element) {
            const input = document.getElementById(inputId);
            const icon = element.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MATRILOG - Register</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">

    {{-- Font Awesome untuk icon mata --}}
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
<body class="antialiased min-h-screen flex items-center justify-center bg-[#0a0f3d]">

    <div class="w-full max-w-md mx-auto p-6">

        {{-- Form card dengan ujung lebih lembut --}}
        <div class="bg-white rounded-3xl shadow-2xl p-8">

            {{-- Title --}}
            <h2 class="text-3xl font-bold text-gray-900 mb-8 font-poppins text-center">Registered Here</h2>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                {{-- Nama User --}}
                <div>
                    <label for="nama_user" class="block text-sm font-medium text-gray-700 mb-1 font-montserrat">Nama User</label>
                    <input 
                        id="nama_user" 
                        name="nama_user" 
                        type="text" 
                        placeholder="Masukkan nama"
                        value="{{ old('nama_user') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1a237e] focus:border-transparent outline-none transition font-montserrat @error('nama_user') border-red-500 @enderror"
                        required 
                        autofocus 
                        autocomplete="nama_user"
                    >
                    @error('nama_user')
                        <p class="text-red-500 text-xs mt-1 font-montserrat">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 font-montserrat">
                        Role
                    </label>

                    <input
                        type="text"
                        value="Petugas"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100"
                        readonly
                    >

                    <input type="hidden" name="id_role" value="2">
                </div>

                {{-- Area --}}
                <div>
                    <label for="id_area" class="block text-sm font-medium text-gray-700 mb-1 font-montserrat">Area</label>
                    <select 
                        id="id_area" 
                        name="id_area" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1a237e] focus:border-transparent outline-none transition font-montserrat @error('id_area') border-red-500 @enderror"
                        required
                    >
                        <option value="" disabled selected>Pilih Area</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id_area }}" {{ old('id_area') == $area->id_area ? 'selected' : '' }}>
                                {{ $area->nama_area }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_area')
                        <p class="text-red-500 text-xs mt-1 font-montserrat">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1 font-montserrat">Email</label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        placeholder="Masukkan email"
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1a237e] focus:border-transparent outline-none transition font-montserrat @error('email') border-red-500 @enderror"
                        required 
                        autocomplete="username"
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1 font-montserrat">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1 font-montserrat">Password</label>
                    <div class="relative">
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            placeholder="Masukkan password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1a237e] focus:border-transparent outline-none transition font-montserrat pr-10 @error('password') border-red-500 @enderror"
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

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1 font-montserrat">Confirm Password</label>
                    <div class="relative">
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            placeholder="Konfirmasi password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#1a237e] focus:border-transparent outline-none transition font-montserrat pr-10"
                            required 
                            autocomplete="new-password"
                        >
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" onclick="togglePassword('password_confirmation', this)">
                            <i class="far fa-eye-slash text-gray-400"></i>
                        </span>
                    </div>
                </div>

                {{-- Register Button --}}
                <button 
                    type="submit" 
                    class="w-full bg-[#1a237e] hover:bg-[#0d1557] text-white font-semibold py-3 px-4 rounded-xl transition duration-200 ease-in-out transform hover:scale-[1.02] font-poppins mt-2"
                >
                    Register
                </button>

                {{-- Info verifikasi --}}
                <div class="flex items-start gap-2 mt-4 p-3 bg-blue-50 rounded-xl">
                    <i class="fas fa-info-circle text-[#1a237e] mt-0.5"></i>
                    <p class="text-sm text-gray-600 font-montserrat">
                        Akun akan diverifikasi oleh admin sebelum dapat digunakan
                    </p>
                </div>

                {{-- Login Link --}}
                <p class="text-center text-sm text-gray-600 mt-4 font-montserrat">
                    Already have an Account? 
                    <a href="{{ route('login') }}" class="text-[#1a237e] font-semibold hover:underline font-poppins">
                        Login here
                    </a>
                </p>

            </form>
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
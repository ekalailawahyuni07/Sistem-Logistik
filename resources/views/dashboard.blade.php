<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MATRILOG - Admin Dashboard</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
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
        .sidebar {
            background: #0a0f3d;
        }
        .sidebar a.active {
            background: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #4f46e5;
        }
        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="antialiased bg-gray-50">

    <div class="flex h-screen overflow-hidden">

        {{-- ==================== SIDEBAR ==================== --}}
        <aside class="sidebar w-64 flex-shrink-0 hidden lg:flex flex-col text-white">
            
            {{-- Logo --}}
            <div class="p-6 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-tkm.png') }}" alt="Logo" class="h-10 w-auto">
                    <div>
                        <h3 class="font-bold text-sm font-poppins">MATRILOG</h3>
                        <p class="text-xs text-white/60">Admin Panel</p>
                    </div>
                </div>
            </div>

            {{-- Menu --}}
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="#" class="active flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-home w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-white/70 hover:text-white transition">
                    <i class="fas fa-users w-5 text-center"></i>
                    <span>Data User</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-white/70 hover:text-white transition">
                    <i class="fas fa-box w-5 text-center"></i>
                    <span>Material</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-white/70 hover:text-white transition">
                    <i class="fas fa-truck w-5 text-center"></i>
                    <span>Logistic</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-white/70 hover:text-white transition">
                    <i class="fas fa-clipboard-list w-5 text-center"></i>
                    <span>Tracking</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-white/70 hover:text-white transition">
                    <i class="fas fa-file-alt w-5 text-center"></i>
                    <span>Laporan</span>
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-white/70 hover:text-white transition">
                    <i class="fas fa-cog w-5 text-center"></i>
                    <span>Pengaturan</span>
                </a>
            </nav>

            {{-- Logout --}}
            <div class="p-4 border-t border-white/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-white/70 hover:text-white hover:bg-white/5 transition w-full">
                        <i class="fas fa-sign-out-alt w-5 text-center"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ==================== MAIN CONTENT ==================== --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Top Bar --}}
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center gap-4">
                        {{-- Mobile menu toggle --}}
                        <button class="lg:hidden text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-xl font-bold text-gray-900 font-poppins">Dashboard</h1>
                    </div>
                    <div class="flex items-center gap-4">
                        {{-- Notification --}}
                        <button class="relative text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 flex items-center justify-center rounded-full">3</span>
                        </button>
                        {{-- Profile --}}
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-[#1a237e] rounded-full flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr(Auth::user()->nama_user ?? 'A', 0, 1)) }}
                            </div>
                            <div class="hidden sm:block">
                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->nama_user ?? 'Admin' }}</p>
                                <p class="text-xs text-gray-500">{{ Auth::user()->role->nama_role ?? 'Admin' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <main class="flex-1 overflow-y-auto p-6">

                {{-- Stat Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    
                    {{-- Card 1: Total User --}}
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 card-hover transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 font-montserrat">Total User</p>
                                <h3 class="text-2xl font-bold text-gray-900 mt-1 font-poppins">24</h3>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-green-500 font-medium">+12%</span>
                            <span class="text-gray-400 ml-2">dari bulan lalu</span>
                        </div>
                    </div>

                    {{-- Card 2: Total Material --}}
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 card-hover transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 font-montserrat">Total Material</p>
                                <h3 class="text-2xl font-bold text-gray-900 mt-1 font-poppins">156</h3>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-box text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-green-500 font-medium">+8%</span>
                            <span class="text-gray-400 ml-2">dari bulan lalu</span>
                        </div>
                    </div>

                    {{-- Card 3: Pengiriman --}}
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 card-hover transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 font-montserrat">Pengiriman Aktif</p>
                                <h3 class="text-2xl font-bold text-gray-900 mt-1 font-poppins">12</h3>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-truck text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-yellow-500 font-medium">3 pending</span>
                            <span class="text-gray-400 ml-2">perlu dicek</span>
                        </div>
                    </div>

                    {{-- Card 4: Pendaftar Baru --}}
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 card-hover transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 font-montserrat">Pendaftar Baru</p>
                                <h3 class="text-2xl font-bold text-gray-900 mt-1 font-poppins">5</h3>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-plus text-purple-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-red-500 font-medium">2 perlu verifikasi</span>
                        </div>
                    </div>
                </div>

                {{-- Row: Tabel & Aktivitas --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Tabel User Terbaru --}}
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-900 font-poppins">User Terbaru</h3>
                            <a href="#" class="text-sm text-[#1a237e] hover:underline font-medium">Lihat Semua →</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        <th class="px-6 py-3">Nama</th>
                                        <th class="px-6 py-3">Email</th>
                                        <th class="px-6 py-3">Role</th>
                                        <th class="px-6 py-3">Area</th>
                                        <th class="px-6 py-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Budi Santoso</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">budi@email.com</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">Staff</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">Jakarta</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">Pending</span>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Siti Rahmawati</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">siti@email.com</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">Manager</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">Bandung</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Aktif</span>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Ahmad Hidayat</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">ahmad@email.com</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">Admin</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">Surabaya</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Aktif</span>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Dewi Lestari</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">dewi@email.com</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">User</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">Medan</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Nonaktif</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Aktivitas Terbaru --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900 font-poppins">Aktivitas Terbaru</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            {{-- Item 1 --}}
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-user-plus text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-900"><span class="font-medium">Budi Santoso</span> mendaftar</p>
                                    <p class="text-xs text-gray-400 mt-1">2 jam yang lalu</p>
                                </div>
                            </div>
                            {{-- Item 2 --}}
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-check text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-900">User <span class="font-medium">Siti</span> diverifikasi</p>
                                    <p class="text-xs text-gray-400 mt-1">5 jam yang lalu</p>
                                </div>
                            </div>
                            {{-- Item 3 --}}
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-truck text-yellow-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-900">Pengiriman #TRK-001 <span class="font-medium">dalam perjalanan</span></p>
                                    <p class="text-xs text-gray-400 mt-1">1 hari yang lalu</p>
                                </div>
                            </div>
                            {{-- Item 4 --}}
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-red-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-900">Material <span class="font-medium">Besi</span> hampir habis</p>
                                    <p class="text-xs text-gray-400 mt-1">2 hari yang lalu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    {{-- Mobile Bottom Nav --}}
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 px-4 py-2">
        <div class="flex justify-around">
            <a href="#" class="flex flex-col items-center text-[#1a237e]">
                <i class="fas fa-home text-lg"></i>
                <span class="text-xs mt-1">Dashboard</span>
            </a>
            <a href="#" class="flex flex-col items-center text-gray-400 hover:text-[#1a237e]">
                <i class="fas fa-users text-lg"></i>
                <span class="text-xs mt-1">User</span>
            </a>
            <a href="#" class="flex flex-col items-center text-gray-400 hover:text-[#1a237e]">
                <i class="fas fa-box text-lg"></i>
                <span class="text-xs mt-1">Material</span>
            </a>
            <a href="#" class="flex flex-col items-center text-gray-400 hover:text-[#1a237e]">
                <i class="fas fa-cog text-lg"></i>
                <span class="text-xs mt-1">Settings</span>
            </a>
        </div>
    </nav>

</body>
</html>
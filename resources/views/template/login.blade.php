<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | {{ config('app.name') }}</title>
    <meta name="description" content="NetsinCode - PT. Nayaka Era Husada" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="https://vectorez.biz.id/wp-content/uploads/2024/05/Logo-Nayaka-Era-Husada@0.5x.png" />

    <!-- Fonts: Plus Jakarta Sans (Modern, Clean, Professional) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif']
                    , }
                    , colors: {
                        brand: {
                            50: '#f0f9ff'
                            , 100: '#e0f2fe'
                            , 500: '#0ea5e9', // Medical Blue/Cyan styling
                            600: '#0284c7'
                            , 700: '#0369a1'
                            , 900: '#0c4a6e'
                        , }
                    }
                    , animation: {
                        'fade-in': 'fadeIn 0.5s ease-out'
                        , 'slide-up': 'slideUp 0.5s ease-out'
                    , }
                    , keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            }
                            , '100%': {
                                opacity: '1'
                            }
                        , }
                        , slideUp: {
                            '0%': {
                                transform: 'translateY(20px)'
                                , opacity: '0'
                            }
                            , '100%': {
                                transform: 'translateY(0)'
                                , opacity: '1'
                            }
                        , }
                    }
                }
            }
        }

    </script>
    @toastifyCss

    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Smooth Gradient Background for Image Side */
        .bg-overlay {
            background: linear-gradient(135deg, rgba(12, 74, 110, 0.9) 0%, rgba(2, 132, 199, 0.8) 100%);
        }

    </style>
</head>
<body class="bg-gray-50 text-slate-800 antialiased h-screen overflow-hidden">
    @php
    $rememberDeviceDecoded = isset($rememberDevice) ? json_decode($rememberDevice) : null;
    @endphp
    <div class="flex h-full w-full">

        <!-- Left Side: Image & Branding (Hidden on Mobile) -->
        <div class="hidden lg:flex w-7/12 relative bg-brand-900 items-center justify-center overflow-hidden">
            <!-- Background Image -->
            <img src="https://www.nayakaerahusada.com/storage/slide/01K1F7KWT8P6P8P1SQRFESXXWB.jpg" alt="Background Hospital" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-50">

            <!-- Overlay Gradient -->
            <div class="absolute inset-0 bg-overlay"></div>

            <!-- Content -->
            <div class="relative z-10 p-12 text-white max-w-2xl animate-slide-up">
                <div class="mb-6 flex items-center gap-3">
                    <span class="text-2xl font-bold tracking-wide">PT. NAYAKA ERA HUSADA</span>
                </div>
                <h1 class="text-5xl font-bold mb-6 leading-tight">KONSOLIDASI <br /> <span class="text-brand-100">HUB PPK DAN KEUANGAN </span></h1>
                <p class="text-lg text-brand-100 mb-8 max-w-lg">
                    Memberikan pelayanan kesehatan terbaik dengan dukungan teknologi informasi yang handal, cepat, dan akurat.
                </p>

                <!-- Decorative Elements -->
                <div class="flex gap-4">
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full border border-white/20">
                        <i class="fa-solid fa-shield-heart text-green-400"></i>
                        <span class="text-sm font-medium">Terpercaya</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full border border-white/20">
                        <i class="fa-solid fa-laptop-medical text-blue-300"></i>
                        <span class="text-sm font-medium">Terintegrasi</span>
                    </div>
                </div>
            </div>

            <!-- Decorative Circles -->
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-brand-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-5/12 flex items-center justify-center bg-white relative">
            <div class="w-full max-w-md p-8 lg:p-12 animate-fade-in">

                <!-- Mobile Logo (Visible only on small screens) -->
                <div class="lg:hidden text-center mb-8">
                    <img src="https://www.nayakaerahusada.com/assets/icon.png" alt="Logo" class="h-16 mx-auto mb-2">
                    <h3 class="text-xl font-bold text-brand-900">Klinik Nayaka Husada</h3>
                </div>

                <!-- Header -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-slate-900 mb-2">Selamat Datang</h2>
                    <p class="text-slate-500">Silakan masuk ke akun Anda untuk memulai.</p>
                </div>

                <!-- Form -->
                <form action="#" method="POST" class="space-y-6" id="loginForm">
                    @csrf
                    <!-- Username Input -->
                    <div class="space-y-2">
                        <label for="username" class="text-sm font-medium text-slate-700">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-regular fa-user"></i>
                            </div>
                            <input type="text" value="{{ $rememberDeviceDecoded ? $rememberDeviceDecoded->username : '' }}" name="username" class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition duration-200 sm:text-sm bg-slate-50 focus:bg-white" placeholder="Masukkan username Anda" required autofocus>
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="password" class="text-sm font-medium text-slate-700">Password</label>
                            <!-- Optional Forgot Password Link -->
                            <!-- <a href="#" class="text-sm font-medium text-brand-600 hover:text-brand-500">Lupa password?</a> -->
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input type="password" id="password" name="password" value="{{ $rememberDeviceDecoded ? $rememberDeviceDecoded->password : '' }}" class="block w-full pl-10 pr-10 py-3 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition duration-200 sm:text-sm bg-slate-50 focus:bg-white" placeholder="••••••••••••" required>
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer focus:outline-none">
                                <i class="fa-regular fa-eye-slash" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember_device" type="checkbox" {{ $rememberDevice ? 'checked' : '' }} value="1" class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded cursor-pointer">
                            <label for="remember-me" class="ml-2 block text-sm text-slate-600 cursor-pointer select-none">
                                Simpan data login
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="group w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-brand-600 hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition duration-200 transform hover:-translate-y-0.5">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fa-solid fa-right-to-bracket text-brand-500 group-hover:text-brand-400 transition"></i>
                        </span>
                        Login
                    </button>
                </form>

                <!-- Footer Info -->
                <div class="mt-8 text-center">
                    <p class="text-xs text-slate-400">
                        &copy; {{ '2026' }} PT. Nayaka Era Husada.<br>All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Javascript Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Password Toggle Logic
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');
            const eyeIcon = document.querySelector('#eyeIcon');

            if (togglePassword && password && eyeIcon) {
                togglePassword.addEventListener('click', function(e) {
                    // Toggle type
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);

                    // Toggle icon
                    if (type === 'text') {
                        eyeIcon.classList.remove('fa-eye-slash');
                        eyeIcon.classList.add('fa-eye');
                        eyeIcon.classList.add('text-brand-600');
                    } else {
                        eyeIcon.classList.remove('fa-eye');
                        eyeIcon.classList.add('fa-eye-slash');
                        eyeIcon.classList.remove('text-brand-600');
                    }
                });
            }
        });

    </script>
    @toastifyJs
</body>
</html>

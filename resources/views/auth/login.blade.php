<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Travel Social Network</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .bg-primary { background-color: #5A67D8; }
        .text-primary { color: #5A67D8; }
        .border-primary { border-color: #5A67D8; }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen font-sans">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">

        <div class="flex justify-center mb-6">
            <div class="bg-primary text-white p-3 rounded-xl shadow-md">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Login to continue</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf <div class="mb-4">
                <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Email"
                       class="w-full px-4 py-3 rounded-lg bg-gray-50 border @error('email') border-red-500 @else border-gray-200 @enderror focus:outline-none focus:border-primary focus:bg-white transition duration-200">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6 relative">
                <input type="password" name="password" required placeholder="Password"
                       class="w-full px-4 py-3 rounded-lg bg-gray-50 border @error('password') border-red-500 @else border-gray-200 @enderror focus:outline-none focus:border-primary focus:bg-white transition duration-200">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-gray-500 hover:text-primary transition">
                        Forgot Password?
                    </a>
                @endif
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-indigo-600 text-white font-semibold py-3 px-8 rounded-xl transition duration-300 shadow-md mb-6">
                Login
            </button>
        </form>

        <div class="relative flex items-center justify-center mb-6">
            <span class="absolute bg-white px-3 text-sm text-gray-400">OR</span>
            <div class="w-full border-t border-gray-200"></div>
        </div>

        <div class="text-center">
            <p class="text-gray-500 text-sm mb-4">Don't have an account?</p>
            <a href="{{ route('register') }}" class="inline-block bg-white hover:bg-gray-50 text-gray-800 font-semibold py-2 px-8 rounded-full border border-gray-300 transition duration-300">
                Sign up here
            </a>
        </div>
    </div>

</body>
</html>

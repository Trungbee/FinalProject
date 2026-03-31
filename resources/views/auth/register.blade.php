<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Travel Social Network</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .bg-primary { background-color: #5A67D8; }
        .text-primary { color: #5A67D8; }
        .border-primary { border-color: #5A67D8; }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen font-sans py-10">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md relative">

        <a href="{{ route('login') }}" class="absolute top-6 left-6 text-gray-400 hover:text-gray-600 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>

        <div class="flex justify-center mb-6 mt-4">
            <div class="bg-primary text-white p-3 rounded-xl shadow-md">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Create Account</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf <div class="mb-4">
                <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Full Name"
                       class="w-full px-4 py-3 rounded-lg bg-gray-50 border @error('name') border-red-500 @else border-gray-200 @enderror focus:outline-none focus:border-primary focus:bg-white transition duration-200">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="Email Address"
                       class="w-full px-4 py-3 rounded-lg bg-gray-50 border @error('email') border-red-500 @else border-gray-200 @enderror focus:outline-none focus:border-primary focus:bg-white transition duration-200">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <input type="password" name="password" required placeholder="Password"
                       class="w-full px-4 py-3 rounded-lg bg-gray-50 border @error('password') border-red-500 @else border-gray-200 @enderror focus:outline-none focus:border-primary focus:bg-white transition duration-200">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-8">
                <input type="password" name="password_confirmation" required placeholder="Confirm Password"
                       class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:border-primary focus:bg-white transition duration-200">
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-indigo-600 text-white font-semibold py-3 px-8 rounded-xl transition duration-300 shadow-md mb-6">
                Create Account
            </button>
        </form>

        <div class="text-center">
            <p class="text-gray-500 text-sm mb-4">Already have an account?</p>
            <a href="{{ route('login') }}" class="inline-block bg-white hover:bg-gray-50 text-gray-800 font-semibold py-2 px-8 rounded-full border border-gray-300 transition duration-300">
                Back to Login
            </a>

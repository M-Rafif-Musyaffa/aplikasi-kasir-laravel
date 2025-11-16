<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Aplikasi Kasir') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans text-gray-900 antialiased bg-gray-100 flex items-center justify-center min-h-screen p-4">

        <div class="relative w-full max-w-5xl bg-white rounded-3xl shadow-xl overflow-hidden min-h-[700px] flex flex-col md:flex-row">

            <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-8">
                        <a href="/" class="text-2xl font-bold text-gray-800">{{ config('app.name', 'KasirApp') }}</a>
                    </div>

                    <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Welcome Back!</h1>
                    <p class="text-gray-500 text-lg mb-8">Login to manage your sales and inventory efficiently.</p>

                    <div class="w-full">
                        {{ $slot }}
                    </div>
                </div>

                <div class="mt-8 text-sm text-gray-400">
                    &copy; {{ date('Y') }} Aplikasi Kasir. All rights reserved.
                </div>
            </div>

            <div class="w-full md:w-1/2 hidden md:block relative">
                <div class="absolute inset-0 bg-cover bg-center"
                     style="background-image: url('{{ asset('images/login-image.png') }}');">
                </div>
                <div class="absolute inset-0 bg-black opacity-20"></div>
            </div>

        </div>
    </body>
</html>

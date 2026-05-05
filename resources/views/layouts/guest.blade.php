<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="auth-page min-h-screen flex flex-col sm:justify-center items-center px-4 py-8">
            <div class="auth-brand text-center">
                <a href="/" class="inline-flex items-center justify-center auth-logo">
                    <x-application-logo class="w-14 h-14 fill-current" />
                </a>
                <h1 class="mt-3 mb-1 text-2xl font-semibold">Helpdesk</h1>
                <p class="mb-0 text-sm">Support ticket system</p>
            </div>

            <div class="auth-card w-full sm:max-w-md mt-6 px-6 py-5 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>

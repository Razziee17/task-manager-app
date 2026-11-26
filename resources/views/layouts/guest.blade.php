<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="color-scheme" content="light">
        <title>@yield('title') | {{ config('app.name', 'Task Manager') }}</title>
        @vite(['resources/css/app.css'])
    </head>
    <body class="guest-body">
        <main class="guest-page">
            <a href="{{ route('login') }}" class="brand-link guest-brand">
                <span class="brand-icon">T</span>
                <span>
                    <span class="brand-name">Task Manager</span>
                    <span class="brand-tagline">A little more focus, every day</span>
                </span>
            </a>

            <section class="auth-card">
                @yield('content')
            </section>

            <p class="guest-footer">Plan your day, one task at a time.</p>
        </main>
    </body>
</html>

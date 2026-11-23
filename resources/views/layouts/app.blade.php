<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="color-scheme" content="light">

        <title>@yield('title', 'Overview') | {{ config('app.name', 'Task Manager') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="app-body">
        <div class="app-shell">
            <header class="app-header">
                <div class="app-header-content">
                    <a href="{{ route('dashboard') }}" class="brand-link">
                        <span class="brand-icon">T</span>
                        <span>
                            <span class="brand-name">Task Manager</span>
                            <span class="brand-tagline">A little more focus, every day</span>
                        </span>
                    </a>

                    <nav aria-label="Main navigation" class="main-navigation">
                        <a
                            href="{{ route('dashboard') }}"
                            class="navigation-link {{ request()->routeIs('dashboard') ? 'navigation-link-active' : '' }}"
                        >
                            Overview
                        </a>
                        <a
                            href="{{ route('tasks.index') }}"
                            class="navigation-link {{ request()->routeIs('tasks.*') ? 'navigation-link-active' : '' }}"
                        >
                            Tasks
                        </a>
                    </nav>

                    <span class="workspace-label">
                        Personal workspace
                    </span>
                </div>
            </header>

            <main class="main-content">
                @yield('content')
            </main>
        </div>
    </body>
</html>

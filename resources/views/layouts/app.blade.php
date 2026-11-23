<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="color-scheme" content="light">

        <title>@yield('title', 'Overview') | {{ config('app.name', 'Task Manager') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
        <div class="min-h-screen">
            <header class="border-b border-slate-200 bg-white">
                <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-5 py-4 sm:px-8">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <span class="grid size-10 place-items-center rounded-xl bg-indigo-600 text-lg font-bold text-white">T</span>
                        <span>
                            <span class="block text-sm font-semibold tracking-tight text-slate-950">Task Manager</span>
                            <span class="block text-xs text-slate-500">A little more focus, every day</span>
                        </span>
                    </a>

                    <nav aria-label="Main navigation" class="flex items-center gap-1">
                        <a
                            href="{{ route('dashboard') }}"
                            @class([
                                'rounded-lg px-3 py-2 text-sm font-medium transition',
                                'bg-indigo-50 text-indigo-700' => request()->routeIs('dashboard'),
                                'text-slate-600 hover:bg-slate-100 hover:text-slate-950' => ! request()->routeIs('dashboard'),
                            ])
                        >
                            Overview
                        </a>
                        <a
                            href="{{ route('tasks.index') }}"
                            @class([
                                'rounded-lg px-3 py-2 text-sm font-medium transition',
                                'bg-indigo-50 text-indigo-700' => request()->routeIs('tasks.*'),
                                'text-slate-600 hover:bg-slate-100 hover:text-slate-950' => ! request()->routeIs('tasks.*'),
                            ])
                        >
                            Tasks
                        </a>
                    </nav>

                    <span class="hidden rounded-full border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 sm:inline-flex">
                        Personal workspace
                    </span>
                </div>
            </header>

            <main class="mx-auto w-full max-w-7xl px-5 py-10 sm:px-8 sm:py-12">
                @yield('content')
            </main>
        </div>
    </body>
</html>

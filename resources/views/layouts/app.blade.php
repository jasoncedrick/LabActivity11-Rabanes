<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Barangay Bulletin - Purok Rabanes')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-50 text-slate-800 antialiased">
    <header class="border-b border-emerald-800 bg-emerald-950 text-white">
        <nav class="mx-auto flex max-w-6xl flex-wrap items-center justify-end gap-4 px-5 pt-4 text-sm">
            @auth
                <span class="text-emerald-200">Hi, {{ auth()->user()->name }}</span>
                @if (auth()->user()->author)
                    <a href="{{ route('posts.create') }}" class="rounded-lg bg-amber-400 px-3 py-1.5 font-semibold text-emerald-950 hover:bg-amber-300">New post</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="underline hover:text-emerald-200">Log out</button>
                </form>
            @endauth
            @guest
                <a href="{{ route('login') }}" class="underline hover:text-emerald-200">Log in</a>
            @endguest
        </nav>
        <div class="mx-auto max-w-6xl px-5 pb-8 pt-4 sm:pb-12">
            <p class="mb-3 text-xs font-semibold uppercase tracking-widest text-emerald-300">Matina Crossing · Davao City</p>
            <a href="{{ route('posts.index') }}" class="text-2xl font-bold sm:text-4xl">Barangay Bulletin - Purok Rabanes</a>
            <p class="mt-3 text-emerald-100">Announcements, reminders, and activities for our community.</p>
        </div>
    </header>
    <main class="mx-auto max-w-6xl px-5 py-8">
        @if (session('status'))
            <p class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900">{{ session('status') }}</p>
        @endif
        @yield('content')
    </main>
    <footer class="mx-auto max-w-6xl border-t border-stone-200 px-5 py-6 text-sm text-slate-500">Barangay Bulletin · Purok Rabanes · Lab Activity 11</footer>
</body>
</html>

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
        <div class="mx-auto max-w-6xl px-5 py-8 sm:py-12">
            <p class="mb-3 text-xs font-semibold uppercase tracking-widest text-emerald-300">Matina Crossing · Davao City</p>
            <a href="{{ route('posts.index') }}" class="text-2xl font-bold sm:text-4xl">Barangay Bulletin - Purok Rabanes</a>
            <p class="mt-3 text-emerald-100">Announcements, reminders, and activities for our community.</p>
        </div>
    </header>
    <main class="mx-auto max-w-6xl px-5 py-8">@yield('content')</main>
    <footer class="mx-auto max-w-6xl border-t border-stone-200 px-5 py-6 text-sm text-slate-500">Barangay Bulletin · Purok Rabanes · Lab Activity 10</footer>
</body>
</html>

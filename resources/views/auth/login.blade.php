@extends('layouts.app')
@section('title', 'Log in - Purok Rabanes')
@section('content')
<div class="mx-auto max-w-md rounded-xl border border-stone-200 bg-white p-6 shadow-sm sm:p-8">
    <h1 class="mb-6 text-2xl font-bold">Log in</h1>

    @include('partials.errors')

    <form method="POST" action="{{ url('/login') }}" class="space-y-4">
        @csrf
        <div>
            <label for="email" class="mb-1 block text-sm font-medium">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" class="w-full rounded-lg border px-3 py-2 @error('email') border-red-500 @else border-stone-300 @enderror">
            @error('email')
                <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="password" class="mb-1 block text-sm font-medium">Password</label>
            <input id="password" name="password" type="password" class="w-full rounded-lg border px-3 py-2 @error('password') border-red-500 @else border-stone-300 @enderror">
            @error('password')
                <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>
        <button class="w-full rounded-lg bg-emerald-800 px-5 py-2 font-semibold text-white hover:bg-emerald-900">Log in</button>
    </form>
</div>
@endsection

@extends('pioneer-portal.layout')
@section('title', 'Amin Login')

@section('content')
<div class="max-w-md mx-auto px-6 py-16">
    <form method="POST" action="{{ route('pioneer.login') }}" class="bg-white border border-gray-200 rounded-2xl shadow-lg p-8">
        @csrf
        <h1 class="font-bold text-2xl text-gray-900 mb-1">Amin Login</h1>
        <p class="text-sm text-gray-500 mb-6">Log in to manage your causes.</p>

        <div class="space-y-4">
            <div>
                <input type="text" name="username" value="{{ old('username') }}" placeholder="Username" required autocomplete="username"
                       class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                @error('username') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <input type="password" name="password" placeholder="Password" required autocomplete="current-password"
                   class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">

            <label class="flex items-center gap-2 text-sm text-gray-500">
                <input type="checkbox" name="remember" class="accent-blue-600"> Remember me
            </label>
        </div>

        <button type="submit" class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-full transition">Log in</button>
        <p class="text-sm text-gray-500 text-center mt-4">
            New here? <a href="{{ route('pioneer.register') }}" class="text-blue-600 font-medium">Create an account</a>
        </p>
    </form>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6 text-center">Connexion</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Champ Email --}}
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500
                          @error('email') border-red-500 @enderror">
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Champ Mot de passe --}}
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
            <input type="password" name="password" id="password" required
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- Se souvenir de moi --}}
        <div class="mb-6 flex items-center">
            <input type="checkbox" name="remember" id="remember" class="mr-2">
            <label for="remember" class="text-sm text-gray-600">Se souvenir de moi</label>
        </div>

        {{-- Bouton --}}
        <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
            Se connecter
        </button>
    </form>

    {{-- Lien vers inscription --}}
    <p class="text-center mt-4 text-sm text-gray-600">
        Pas encore inscrit ?
        <a href="{{ route('register') }}" class="text-blue-600 hover:underline">S'inscrire</a>
    </p>
</div>
@endsection
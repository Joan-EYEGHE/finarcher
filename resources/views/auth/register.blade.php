{{-- @extends = cette vue utilise le layout app.blade.php --}}
@extends('layouts.app')

{{-- Remplit le @yield('title') du layout --}}
@section('title', 'Inscription')

{{-- Remplit le @yield('content') du layout --}}
@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6 text-center">Inscription</h2>

    <form method="POST" action="{{ route('register') }}">
        {{-- Token CSRF obligatoire --}}
        @csrf

        {{-- Champ Nom --}}
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500
                          @error('name') border-red-500 @enderror">

            {{-- Affiche l'erreur de validation si elle existe --}}
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

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

        {{-- Champ Numéro (optionnel) --}}
        <div class="mb-4">
            <label for="numero" class="block text-sm font-medium text-gray-700 mb-1">Numéro (optionnel)</label>
            <input type="text" name="numero" id="numero" value="{{ old('numero') }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- Champ Mot de passe --}}
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
            <input type="password" name="password" id="password" required
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500
                          @error('password') border-red-500 @enderror">
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirmation mot de passe --}}
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- Bouton --}}
        <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
            S'inscrire
        </button>
    </form>

    {{-- Lien vers connexion --}}
    <p class="text-center mt-4 text-sm text-gray-600">
        Déjà inscrit ?
        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Se connecter</a>
    </p>
</div>
@endsection
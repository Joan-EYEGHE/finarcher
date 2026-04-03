@extends('layouts.app')

@section('title', 'Nouveau compte')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6">Nouveau compte</h2>

    <form method="POST" action="{{ route('portefeuilles.store') }}">
        @csrf

        <div class="mb-4">
            <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom du compte</label>
            <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500
                          @error('nom') border-red-500 @enderror">
            @error('nom')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Menu déroulant des devises --}}
        <div class="mb-4">
            <label for="devise_id" class="block text-sm font-medium text-gray-700 mb-1">Devise</label>
            <select name="devise_id" id="devise_id" required
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500
                           @error('devise_id') border-red-500 @enderror">
                <option value="">-- Choisir une devise --</option>
                @foreach($devises as $devise)
                    <option value="{{ $devise->id }}"
                            {{ old('devise_id') == $devise->id ? 'selected' : '' }}>
                        {{ $devise->nom }} ({{ $devise->symbole }})
                    </option>
                @endforeach
            </select>
            @error('devise_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="icone" class="block text-sm font-medium text-gray-700 mb-1">Icône (optionnel)</label>
            <input type="text" name="icone" id="icone" value="{{ old('icone') }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Créer
            </button>
            <a href="{{ route('portefeuilles.index') }}"
               class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
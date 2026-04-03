@extends('layouts.app')

@section('title', 'Nouveau revenu')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6">Nouveau revenu</h2>

    <form method="POST" action="{{ route('revenus.store') }}">
        @csrf

        <div class="mb-4">
            <label for="date_operation" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
            <input type="date" name="date_operation" id="date_operation"
                   value="{{ old('date_operation', date('Y-m-d')) }}" required
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500
                          @error('date_operation') border-red-500 @enderror">
            @error('date_operation')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="portefeuille_id" class="block text-sm font-medium text-gray-700 mb-1">Compte</label>
            <select name="portefeuille_id" id="portefeuille_id" required
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500
                           @error('portefeuille_id') border-red-500 @enderror">
                <option value="">-- Choisir un compte --</option>
                @foreach($portefeuilles as $portefeuille)
                    <option value="{{ $portefeuille->id }}"
                            {{ old('portefeuille_id') == $portefeuille->id ? 'selected' : '' }}>
                        {{ $portefeuille->nom }}
                    </option>
                @endforeach
            </select>
            @error('portefeuille_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="acteur_id" class="block text-sm font-medium text-gray-700 mb-1">Contact</label>
            <select name="acteur_id" id="acteur_id" required
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500
                           @error('acteur_id') border-red-500 @enderror">
                <option value="">-- Choisir un contact --</option>
                @foreach($acteurs as $acteur)
                    <option value="{{ $acteur->id }}"
                            {{ old('acteur_id') == $acteur->id ? 'selected' : '' }}>
                        {{ $acteur->nom }}
                    </option>
                @endforeach
            </select>
            @error('acteur_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="montant" class="block text-sm font-medium text-gray-700 mb-1">Montant</label>
            <input type="number" name="montant" id="montant" value="{{ old('montant') }}"
                   step="0.01" min="0.01" required
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500
                          @error('montant') border-red-500 @enderror">
            @error('montant')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="motif" class="block text-sm font-medium text-gray-700 mb-1">Motif (optionnel)</label>
            <input type="text" name="motif" id="motif" value="{{ old('motif') }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Créer
            </button>
            <a href="{{ route('revenus.index') }}"
               class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
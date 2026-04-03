@extends('layouts.app')

@section('title', 'Modifier dépense')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6">Modifier la dépense</h2>

    <form method="POST" action="{{ route('depenses.update', $depense) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="date_operation" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
            <input type="date" name="date_operation" id="date_operation"
                   value="{{ old('date_operation', $depense->date_operation->format('Y-m-d')) }}" required
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500
                          @error('date_operation') border-red-500 @enderror">
            @error('date_operation')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="designation" class="block text-sm font-medium text-gray-700 mb-1">Désignation</label>
            <input type="text" name="designation" id="designation"
                   value="{{ old('designation', $depense->designation) }}" required
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500
                          @error('designation') border-red-500 @enderror">
            @error('designation')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="categorie_id" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
            <select name="categorie_id" id="categorie_id" required
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500
                           @error('categorie_id') border-red-500 @enderror">
                @foreach($categories as $categorie)
                    <option value="{{ $categorie->id }}"
                            {{ old('categorie_id', $depense->categorie_id) == $categorie->id ? 'selected' : '' }}>
                        {{ $categorie->nom }}
                    </option>
                @endforeach
            </select>
            @error('categorie_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="portefeuille_id" class="block text-sm font-medium text-gray-700 mb-1">Compte (optionnel)</label>
            <select name="portefeuille_id" id="portefeuille_id"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Aucun compte --</option>
                @foreach($portefeuilles as $portefeuille)
                    <option value="{{ $portefeuille->id }}"
                            {{ old('portefeuille_id', $depense->portefeuille_id) == $portefeuille->id ? 'selected' : '' }}>
                        {{ $portefeuille->nom }} ({{ $portefeuille->devise->symbole }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="quantite" class="block text-sm font-medium text-gray-700 mb-1">Quantité</label>
                <input type="number" name="quantite" id="quantite"
                       value="{{ old('quantite', $depense->quantite) }}"
                       step="0.01" min="0.01" required
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500
                              @error('quantite') border-red-500 @enderror">
                @error('quantite')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="prix" class="block text-sm font-medium text-gray-700 mb-1">Prix unitaire</label>
                <input type="number" name="prix" id="prix"
                       value="{{ old('prix', $depense->prix) }}"
                       step="0.01" min="0.01" required
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500
                              @error('prix') border-red-500 @enderror">
                @error('prix')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Modifier
            </button>
            <a href="{{ route('depenses.index') }}"
               class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
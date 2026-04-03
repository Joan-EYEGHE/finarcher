@extends('layouts.app')

@section('title', 'Modifier catégorie')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6">Modifier la catégorie</h2>

    <form method="POST" action="{{ route('categories.update', $category) }}">
        @csrf
        {{-- @method('PUT') = simule une requête PUT (les formulaires HTML ne supportent que GET et POST)
             Laravel lit ce champ caché et traite la requête comme un PUT
             Équivalent Java : HiddenHttpMethodFilter de Spring --}}
        @method('PUT')

        <div class="mb-4">
            <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
            <input type="text" name="nom" id="nom" value="{{ old('nom', $category->nom) }}" required
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500
                          @error('nom') border-red-500 @enderror">
            @error('nom')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description (optionnel)</label>
            <textarea name="description" id="description" rows="3"
                      class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="mb-6">
            <label for="icone" class="block text-sm font-medium text-gray-700 mb-1">Icône (optionnel)</label>
            <input type="text" name="icone" id="icone" value="{{ old('icone', $category->icone) }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Modifier
            </button>
            <a href="{{ route('categories.index') }}"
               class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection

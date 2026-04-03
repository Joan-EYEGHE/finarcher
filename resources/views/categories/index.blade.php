@extends('layouts.app')

@section('title', 'Catégories')

@section('content')
<div class="bg-white rounded-lg shadow p-6">

    {{-- En-tête avec titre et bouton ajouter --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Catégories</h2>
        <a href="{{ route('categories.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            + Nouvelle catégorie
        </a>
    </div>

    {{-- Messages flash (succès ou erreur) --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Tableau des catégories --}}
    @if($categories->isEmpty())
        <p class="text-gray-500">Aucune catégorie trouvée.</p>
    @else
        <table class="w-full text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-sm font-medium text-gray-600">Nom</th>
                    <th class="px-4 py-3 text-sm font-medium text-gray-600">Description</th>
                    <th class="px-4 py-3 text-sm font-medium text-gray-600 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($categories as $category)
                <tr>
                    <td class="px-4 py-3">
                        {{ $category->nom }}
                        @if($category->is_default)
                            <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded ml-2">par défaut</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $category->description ?? '-' }}</td>
                    <td class="px-4 py-3 text-right">
                        @unless($category->is_default)
                            <a href="{{ route('categories.edit', $category) }}"
                               class="text-blue-600 hover:underline mr-3">Modifier</a>

                            <form method="POST" action="{{ route('categories.destroy', $category) }}"
                                  class="inline"
                                  onsubmit="return confirm('Supprimer cette catégorie ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        @endunless
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>
@endsection
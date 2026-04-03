@extends('layouts.app')

@section('title', 'Dépenses')

@section('content')
<div class="bg-white rounded-lg shadow p-6">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Dépenses</h2>
        <a href="{{ route('depenses.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            + Nouvelle dépense
        </a>
    </div>

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

    @if($depenses->isEmpty())
        <p class="text-gray-500">Aucune dépense trouvée.</p>
    @else
        <table class="w-full text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-sm font-medium text-gray-600">Date</th>
                    <th class="px-4 py-3 text-sm font-medium text-gray-600">Désignation</th>
                    <th class="px-4 py-3 text-sm font-medium text-gray-600">Catégorie</th>
                    <th class="px-4 py-3 text-sm font-medium text-gray-600">Compte</th>
                    <th class="px-4 py-3 text-sm font-medium text-gray-600 text-right">Montant</th>
                    <th class="px-4 py-3 text-sm font-medium text-gray-600 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($depenses as $depense)
                <tr>
                    <td class="px-4 py-3">{{ $depense->date_operation->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 font-medium">
                        {{ $depense->designation }}
                        @if($depense->quantite != 1)
                            <span class="text-xs text-gray-400">(× {{ $depense->quantite }})</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $depense->categorie->nom }}</td>
                    <td class="px-4 py-3 text-gray-500">
                        {{ $depense->portefeuille ? $depense->portefeuille->nom : '-' }}
                    </td>
                    <td class="px-4 py-3 text-right font-mono text-red-600">
                        -{{ number_format($depense->montant_total, 2, ',', ' ') }}
                        @if($depense->portefeuille)
                            {{ $depense->portefeuille->devise->symbole }}
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('depenses.edit', $depense) }}"
                           class="text-blue-600 hover:underline mr-3">Modifier</a>

                        <form method="POST" action="{{ route('depenses.destroy', $depense) }}"
                              class="inline"
                              onsubmit="return confirm('Supprimer cette dépense ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>
@endsection
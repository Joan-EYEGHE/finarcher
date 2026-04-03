@extends('layouts.app')

@section('title', 'Revenus')

@section('content')
<div class="bg-white rounded-lg shadow p-6">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Revenus</h2>
        <a href="{{ route('revenus.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            + Nouveau revenu
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

    @if($revenus->isEmpty())
        <p class="text-gray-500">Aucun revenu trouvé.</p>
    @else
        <table class="w-full text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-sm font-medium text-gray-600">Date</th>
                    <th class="px-4 py-3 text-sm font-medium text-gray-600">Motif</th>
                    <th class="px-4 py-3 text-sm font-medium text-gray-600">Contact</th>
                    <th class="px-4 py-3 text-sm font-medium text-gray-600">Compte</th>
                    <th class="px-4 py-3 text-sm font-medium text-gray-600 text-right">Montant</th>
                    <th class="px-4 py-3 text-sm font-medium text-gray-600 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($revenus as $revenu)
                <tr>
                    <td class="px-4 py-3">{{ $revenu->date_operation->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">{{ $revenu->motif ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $revenu->acteur->nom }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $revenu->portefeuille->nom }}</td>
                    <td class="px-4 py-3 text-right font-mono text-green-600">
                        +{{ number_format($revenu->montant, 2, ',', ' ') }}
                        {{ $revenu->portefeuille->devise->symbole }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('revenus.edit', $revenu) }}"
                           class="text-blue-600 hover:underline mr-3">Modifier</a>

                        <form method="POST" action="{{ route('revenus.destroy', $revenu) }}"
                              class="inline"
                              onsubmit="return confirm('Supprimer ce revenu ? Le solde du compte sera mis à jour.')">
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
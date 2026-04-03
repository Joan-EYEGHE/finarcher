@extends('layouts.app')

@section('title', 'Contacts')

@section('content')
<div class="bg-white rounded-lg shadow p-6">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Contacts</h2>
        <a href="{{ route('acteurs.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            + Nouveau contact
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

    @if($acteurs->isEmpty())
        <p class="text-gray-500">Aucun contact trouvé.</p>
    @else
        <table class="w-full text-left">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-sm font-medium text-gray-600">Nom</th>
                    <th class="px-4 py-3 text-sm font-medium text-gray-600">Adresse</th>
                    <th class="px-4 py-3 text-sm font-medium text-gray-600">Numéro</th>
                    <th class="px-4 py-3 text-sm font-medium text-gray-600 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($acteurs as $acteur)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $acteur->nom }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $acteur->adresse ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $acteur->numero ?? '-' }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('acteurs.edit', $acteur) }}"
                           class="text-blue-600 hover:underline mr-3">Modifier</a>

                        <form method="POST" action="{{ route('acteurs.destroy', $acteur) }}"
                              class="inline"
                              onsubmit="return confirm('Supprimer ce contact ?')">
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
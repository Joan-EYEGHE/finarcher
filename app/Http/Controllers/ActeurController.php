<?php

namespace App\Http\Controllers;

use App\Models\Acteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActeurController extends Controller
{
    public function index()
    {
        $acteurs = Acteur::where('user_id', Auth::id())
            ->orderBy('nom')
            ->get();

        return view('acteurs.index', compact('acteurs'));
    }

    public function create()
    {
        return view('acteurs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'numero' => ['nullable', 'string', 'max:20'],
        ]);

        Auth::user()->acteurs()->create($validated);

        return redirect()->route('acteurs.index')
            ->with('success', 'Contact créé avec succès.');
    }

    public function show(Acteur $acteur)
    {
        return redirect()->route('acteurs.index');
    }

public function edit(Acteur $acteur)
    {
        $this->authorize('update', $acteur);

        return view('acteurs.edit', compact('acteur'));
    }

    public function update(Request $request, Acteur $acteur)
    {
        $this->authorize('update', $acteur);

        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'numero' => ['nullable', 'string', 'max:20'],
        ]);

        $acteur->update($validated);

        return redirect()->route('acteurs.index')
            ->with('success', 'Contact modifié avec succès.');
    }

    public function destroy(Acteur $acteur)
    {
        $this->authorize('delete', $acteur);

        if ($acteur->revenus()->count() > 0) {
            return redirect()->route('acteurs.index')
                ->with('error', 'Impossible de supprimer : des revenus sont liés à ce contact.');
        }

        $acteur->delete();

        return redirect()->route('acteurs.index')
            ->with('success', 'Contact supprimé avec succès.');
    }
}
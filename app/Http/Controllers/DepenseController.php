<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use App\Models\Categorie;
use App\Models\Portefeuille;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepenseController extends Controller
{
    public function index()
    {
        $depenses = Depense::where('user_id', Auth::id())
            ->with(['categorie', 'portefeuille.devise'])
            ->orderByDesc('date_operation')
            ->get();

        return view('depenses.index', compact('depenses'));
    }

    public function create()
    {
        $categories = Categorie::where('user_id', Auth::id())->orderBy('nom')->get();
        $portefeuilles = Portefeuille::where('user_id', Auth::id())->orderBy('nom')->get();
        return view('depenses.create', compact('categories', 'portefeuilles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categorie_id' => ['required', 'exists:categories,id'],
            'portefeuille_id' => ['nullable', 'exists:portefeuilles,id'],
            'date_operation' => ['required', 'date'],
            'designation' => ['required', 'string', 'max:255'],
            'quantite' => ['required', 'numeric', 'min:0.01'],
            'prix' => ['required', 'numeric', 'min:0.01'],
        ]);

        // Vérifie que la catégorie appartient au user
        Categorie::where('id', $validated['categorie_id'])
            ->where('user_id', Auth::id())->firstOrFail();

        // montant_total calculé automatiquement dans le boot() du modèle Depense

        // Crée la dépense
        Auth::user()->depenses()->create($validated);

        // Met à jour le solde du portefeuille si un portefeuille est sélectionné
        if (!empty($validated['portefeuille_id'])) {
            $portefeuille = Portefeuille::where('id', $validated['portefeuille_id'])
                ->where('user_id', Auth::id())->firstOrFail();
            $portefeuille->solde -= $validated['quantite'] * $validated['prix'];
            $portefeuille->save();
        }

        return redirect()->route('depenses.index')
            ->with('success', 'Dépense enregistrée avec succès.');
    }

    public function show(Depense $depense)
    {
        return redirect()->route('depenses.index');
    }

    public function edit(Depense $depense)
    {
        if ($depense->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Categorie::where('user_id', Auth::id())->orderBy('nom')->get();
        $portefeuilles = Portefeuille::where('user_id', Auth::id())->orderBy('nom')->get();
        return view('depenses.edit', compact('depense', 'categories', 'portefeuilles'));
    }

    public function update(Request $request, Depense $depense)
    {
        if ($depense->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'categorie_id' => ['required', 'exists:categories,id'],
            'portefeuille_id' => ['nullable', 'exists:portefeuilles,id'],
            'date_operation' => ['required', 'date'],
            'designation' => ['required', 'string', 'max:255'],
            'quantite' => ['required', 'numeric', 'min:0.01'],
            'prix' => ['required', 'numeric', 'min:0.01'],
        ]);

        // Vérifie que la catégorie appartient au user
        Categorie::where('id', $validated['categorie_id'])
            ->where('user_id', Auth::id())->firstOrFail();

        // --- CORRECTION DU SOLDE ---
        // 1. Annuler l'ancienne dépense sur l'ancien portefeuille (si existait)
        if ($depense->portefeuille_id) {
            $ancienPortefeuille = Portefeuille::findOrFail($depense->portefeuille_id);
            $ancienPortefeuille->solde += $depense->montant_total; // on rend l'argent
            $ancienPortefeuille->save();
        }

        // 2. Appliquer la nouvelle dépense sur le nouveau portefeuille (si sélectionné)
        if (!empty($validated['portefeuille_id'])) {
            $nouveauPortefeuille = Portefeuille::where('id', $validated['portefeuille_id'])
                ->where('user_id', Auth::id())->firstOrFail();
            $nouveauPortefeuille->solde -= $validated['quantite'] * $validated['prix'];
            $nouveauPortefeuille->save();
        }

        // 3. Met à jour la dépense (montant_total recalculé par boot())
        $depense->update($validated);

        return redirect()->route('depenses.index')
            ->with('success', 'Dépense modifiée avec succès.');
    }

    public function destroy(Depense $depense)
    {
        if ($depense->user_id !== Auth::id()) {
            abort(403);
        }

        // Annule l'effet sur le solde si un portefeuille était lié
        if ($depense->portefeuille_id) {
            $portefeuille = Portefeuille::findOrFail($depense->portefeuille_id);
            $portefeuille->solde += $depense->montant_total; // on rend l'argent
            $portefeuille->save();
        }

        $depense->delete();

        return redirect()->route('depenses.index')
            ->with('success', 'Dépense supprimée avec succès.');
    }
}
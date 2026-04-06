<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use App\Models\Categorie;
use App\Models\Portefeuille;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepenseController extends Controller
{
    public function index(Request $request)
    {
        $dateDebut = $request->input('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin   = $request->input('date_fin',   now()->format('Y-m-d'));

        $query = Depense::where('user_id', Auth::id())
            ->with(['categorie', 'portefeuille'])
            ->whereBetween('date_operation', [$dateDebut, $dateFin])
            ->orderByDesc('date_operation');

        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }
        if ($request->filled('portefeuille_id')) {
            $query->where('portefeuille_id', $request->portefeuille_id);
        }

        $allFiltered     = (clone $query)->get();
        $totalDepenses   = $allFiltered->sum('montant_total');
        $countDepenses   = $allFiltered->count();
        $moyenneDepenses = $countDepenses > 0 ? $totalDepenses / $countDepenses : 0;
        $maxDepenseItem  = $allFiltered->sortByDesc('montant_total')->first();

        $depenses      = $query->paginate(15)->withQueryString();
        $categories    = Categorie::where('user_id', Auth::id())->orderBy('nom')->get();
        $portefeuilles = Portefeuille::where('user_id', Auth::id())->orderBy('nom')->get();

        return view('depenses.index', compact(
            'depenses', 'categories', 'portefeuilles',
            'dateDebut', 'dateFin',
            'totalDepenses', 'countDepenses', 'moyenneDepenses', 'maxDepenseItem'
        ));
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
        $this->authorize('update', $depense);

        $categories = Categorie::where('user_id', Auth::id())->orderBy('nom')->get();
        $portefeuilles = Portefeuille::where('user_id', Auth::id())->orderBy('nom')->get();
        return view('depenses.edit', compact('depense', 'categories', 'portefeuilles'));
    }

    public function update(Request $request, Depense $depense)
    {
        $this->authorize('update', $depense);

        $validated = $request->validate([
            'categorie_id' => ['required', 'exists:categories,id'],
            'portefeuille_id' => ['nullable', 'exists:portefeuilles,id'],
            'date_operation' => ['required', 'date'],
            'designation' => ['required', 'string', 'max:255'],
            'quantite' => ['required', 'numeric', 'min:0.01'],
            'prix' => ['required', 'numeric', 'min:0.01'],
        ]);

        Categorie::where('id', $validated['categorie_id'])
            ->where('user_id', Auth::id())->firstOrFail();

        if ($depense->portefeuille_id) {
            $ancienPortefeuille = Portefeuille::findOrFail($depense->portefeuille_id);
            $ancienPortefeuille->solde += $depense->montant_total;
            $ancienPortefeuille->save();
        }

        if (!empty($validated['portefeuille_id'])) {
            $nouveauPortefeuille = Portefeuille::where('id', $validated['portefeuille_id'])
                ->where('user_id', Auth::id())->firstOrFail();
            $nouveauPortefeuille->solde -= $validated['quantite'] * $validated['prix'];
            $nouveauPortefeuille->save();
        }

        $depense->update($validated);

        return redirect()->route('depenses.index')
            ->with('success', 'Dépense modifiée avec succès.');
    }

    public function destroy(Depense $depense)
    {
        $this->authorize('delete', $depense);

        if ($depense->portefeuille_id) {
            $portefeuille = Portefeuille::findOrFail($depense->portefeuille_id);
            $portefeuille->solde += $depense->montant_total;
            $portefeuille->save();
        }

        $depense->delete();

        return redirect()->route('depenses.index')
            ->with('success', 'Dépense supprimée avec succès.');
    }
}
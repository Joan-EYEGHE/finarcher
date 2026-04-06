<?php

namespace App\Http\Controllers;

use App\Models\Revenu;
use App\Models\Portefeuille;
use App\Models\Acteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RevenuController extends Controller
{
    public function index(Request $request)
    {
        $dateDebut = $request->get('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin   = $request->get('date_fin',   now()->format('Y-m-d'));

        $query = Revenu::where('user_id', Auth::id())
            ->with(['portefeuille.devise', 'acteur'])
            ->whereBetween('date_operation', [$dateDebut, $dateFin])
            ->orderByDesc('date_operation');

        if ($request->filled('acteur_id')) {
            $query->where('acteur_id', $request->acteur_id);
        }
        if ($request->filled('portefeuille_id')) {
            $query->where('portefeuille_id', $request->portefeuille_id);
        }

        // KPIs (avant pagination)
        $totalRevenus   = (clone $query)->sum('montant');
        $countRevenus   = (clone $query)->count();
        $moyenneRevenus = $countRevenus > 0 ? $totalRevenus / $countRevenus : 0;
        $maxRevenuItem  = $countRevenus > 0 ? (clone $query)->orderByDesc('montant')->first() : null;

        $revenus       = $query->paginate(15)->withQueryString();
        $portefeuilles = Portefeuille::where('user_id', Auth::id())->orderBy('nom')->get();
        $acteurs       = Acteur::where('user_id', Auth::id())->orderBy('nom')->get();

        return view('revenus.index', compact(
            'revenus', 'portefeuilles', 'acteurs',
            'totalRevenus', 'countRevenus', 'moyenneRevenus', 'maxRevenuItem',
            'dateDebut', 'dateFin'
        ));
    }

    public function create()
    {
        $portefeuilles = Portefeuille::where('user_id', Auth::id())->orderBy('nom')->get();
        $acteurs = Acteur::where('user_id', Auth::id())->orderBy('nom')->get();
        return view('revenus.create', compact('portefeuilles', 'acteurs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'portefeuille_id' => ['required', 'exists:portefeuilles,id'],
            'acteur_id' => ['required', 'exists:acteurs,id'],
            'date_operation' => ['required', 'date'],
            'motif' => ['nullable', 'string', 'max:255'],
            'montant' => ['required', 'numeric', 'min:0.01'],
        ]);

        // Vérifie que le portefeuille et l'acteur appartiennent au user
        $portefeuille = Portefeuille::where('id', $validated['portefeuille_id'])
            ->where('user_id', Auth::id())->firstOrFail();
        $acteur = Acteur::where('id', $validated['acteur_id'])
            ->where('user_id', Auth::id())->firstOrFail();

        // Crée le revenu
        Auth::user()->revenus()->create($validated);

        // Met à jour le solde : +montant
        $portefeuille->solde += $validated['montant'];
        $portefeuille->save();

        return redirect()->route('revenus.index')
            ->with('success', 'Revenu enregistré avec succès.');
    }

    public function show(Revenu $revenu)
    {
        return redirect()->route('revenus.index');
    }

public function edit(Revenu $revenu)
    {
        $this->authorize('update', $revenu);

        $portefeuilles = Portefeuille::where('user_id', Auth::id())->orderBy('nom')->get();
        $acteurs = Acteur::where('user_id', Auth::id())->orderBy('nom')->get();
        return view('revenus.edit', compact('revenu', 'portefeuilles', 'acteurs'));
    }

    public function update(Request $request, Revenu $revenu)
    {
        $this->authorize('update', $revenu);

        $validated = $request->validate([
            'portefeuille_id' => ['required', 'exists:portefeuilles,id'],
            'acteur_id' => ['required', 'exists:acteurs,id'],
            'date_operation' => ['required', 'date'],
            'motif' => ['nullable', 'string', 'max:255'],
            'montant' => ['required', 'numeric', 'min:0.01'],
        ]);

        Acteur::where('id', $validated['acteur_id'])
            ->where('user_id', Auth::id())->firstOrFail();

        $ancienPortefeuille = Portefeuille::findOrFail($revenu->portefeuille_id);
        $ancienPortefeuille->solde -= $revenu->montant;
        $ancienPortefeuille->save();

        $nouveauPortefeuille = Portefeuille::where('id', $validated['portefeuille_id'])
            ->where('user_id', Auth::id())->firstOrFail();
        $nouveauPortefeuille->solde += $validated['montant'];
        $nouveauPortefeuille->save();

        $revenu->update($validated);

        return redirect()->route('revenus.index')
            ->with('success', 'Revenu modifié avec succès.');
    }

    public function destroy(Revenu $revenu)
    {
        $this->authorize('delete', $revenu);

        $portefeuille = Portefeuille::findOrFail($revenu->portefeuille_id);
        $portefeuille->solde -= $revenu->montant;
        $portefeuille->save();

        $revenu->delete();

        return redirect()->route('revenus.index')
            ->with('success', 'Revenu supprimé avec succès.');
    }
}
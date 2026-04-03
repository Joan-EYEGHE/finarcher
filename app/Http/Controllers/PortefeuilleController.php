<?php

namespace App\Http\Controllers;

use App\Models\Portefeuille;
use App\Models\Devise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortefeuilleController extends Controller
{
    /**
     * Liste des portefeuilles du user connecté
     * with('devise') = charge la relation en même temps (évite le N+1)
     * Équivalent JPA : @EntityGraph ou JOIN FETCH
     */
    public function index()
    {
        $portefeuilles = Portefeuille::where('user_id', Auth::id())
            ->with('devise')
            ->orderBy('nom')
            ->get();

        return view('portefeuilles.index', compact('portefeuilles'));
    }

    /**
     * Formulaire de création
     * On charge toutes les devises pour le menu déroulant
     */
    public function create()
    {
        $devises = Devise::orderBy('nom')->get();
        return view('portefeuilles.create', compact('devises'));
    }

    /**
     * Enregistre un nouveau portefeuille
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'devise_id' => ['required', 'exists:devises,id'],
            'icone' => ['nullable', 'string', 'max:255'],
        ]);

        // Le solde commence à 0, il sera mis à jour par les revenus/dépenses
        Auth::user()->portefeuilles()->create($validated);

        return redirect()->route('portefeuilles.index')
            ->with('success', 'Compte créé avec succès.');
    }

    /**
     * Détail (redirige vers la liste)
     */
    public function show(Portefeuille $portefeuille)
    {
        return redirect()->route('portefeuilles.index');
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Portefeuille $portefeuille)
    {
        if ($portefeuille->user_id !== Auth::id()) {
            abort(403);
        }

        $devises = Devise::orderBy('nom')->get();
        return view('portefeuilles.edit', compact('portefeuille', 'devises'));
    }

    /**
     * Met à jour le portefeuille
     */
    public function update(Request $request, Portefeuille $portefeuille)
    {
        if ($portefeuille->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'devise_id' => ['required', 'exists:devises,id'],
            'icone' => ['nullable', 'string', 'max:255'],
        ]);

        $portefeuille->update($validated);

        return redirect()->route('portefeuilles.index')
            ->with('success', 'Compte modifié avec succès.');
    }

    /**
     * Supprime le portefeuille (soft delete)
     */
    public function destroy(Portefeuille $portefeuille)
    {
        if ($portefeuille->user_id !== Auth::id()) {
            abort(403);
        }

        // Bloque si des revenus ou dépenses sont liés
        if ($portefeuille->revenus()->count() > 0 || $portefeuille->depenses()->count() > 0) {
            return redirect()->route('portefeuilles.index')
                ->with('error', 'Impossible de supprimer : des opérations sont liées à ce compte.');
        }

        $portefeuille->delete();

        return redirect()->route('portefeuilles.index')
            ->with('success', 'Compte supprimé avec succès.');
    }
}
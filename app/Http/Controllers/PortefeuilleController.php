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
    public function index(Request $request)
    {
        $dateDebut = $request->input('date_debut');
        $dateFin   = $request->input('date_fin');

        $query = Portefeuille::where('user_id', Auth::id())
            ->with('devise')
            ->withCount([
                'revenus' => function ($q) use ($dateDebut, $dateFin) {
                    if ($dateDebut) $q->where('date_operation', '>=', $dateDebut);
                    if ($dateFin)   $q->where('date_operation', '<=', $dateFin);
                },
                'depenses' => function ($q) use ($dateDebut, $dateFin) {
                    if ($dateDebut) $q->where('date_operation', '>=', $dateDebut);
                    if ($dateFin)   $q->where('date_operation', '<=', $dateFin);
                },
            ])
            ->orderBy('nom');

        $portefeuilles = $query->paginate(12)->withQueryString();

        $devises = Devise::orderBy('nom')->get();

        return view('portefeuilles.index', compact('portefeuilles', 'devises'));
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
        $this->authorize('update', $portefeuille);

        $devises = Devise::orderBy('nom')->get();
        return view('portefeuilles.edit', compact('portefeuille', 'devises'));
    }

    public function update(Request $request, Portefeuille $portefeuille)
    {
        $this->authorize('update', $portefeuille);

        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'devise_id' => ['required', 'exists:devises,id'],
            'icone' => ['nullable', 'string', 'max:255'],
        ]);

        $portefeuille->update($validated);

        return redirect()->route('portefeuilles.index')
            ->with('success', 'Compte modifié avec succès.');
    }

    public function destroy(Portefeuille $portefeuille)
    {
        $this->authorize('delete', $portefeuille);

        if ($portefeuille->revenus()->count() > 0 || $portefeuille->depenses()->count() > 0) {
            return redirect()->route('portefeuilles.index')
                ->with('error', 'Impossible de supprimer : des opérations sont liées à ce compte.');
        }

        $portefeuille->delete();

        return redirect()->route('portefeuilles.index')
            ->with('success', 'Compte supprimé avec succès.');
    }
}
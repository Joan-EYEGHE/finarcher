<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategorieController extends Controller
{
    /**
     * Liste des catégories du user connecté
     * Équivalent Java : GET /categories → findAllByUserId()
     */
    public function index(Request $request)
    {
        $dateDebut = $request->input('date_debut');
        $dateFin   = $request->input('date_fin');

        $query = Categorie::where('user_id', Auth::id())
            ->withCount(['depenses' => function ($q) use ($dateDebut, $dateFin) {
                if ($dateDebut) $q->where('date', '>=', $dateDebut);
                if ($dateFin)   $q->where('date', '<=', $dateFin);
            }])
            ->orderBy('nom');

        if ($search = $request->input('search')) {
            $query->where('nom', 'like', '%' . $search . '%');
        }

        $categories = $query->paginate(12);
        $categories->appends($request->except('page'));

        $totalDepenses = \App\Models\Depense::whereHas('categorie', function ($q) {
            $q->where('user_id', Auth::id());
        })
        ->when($dateDebut, fn($q) => $q->where('date', '>=', $dateDebut))
        ->when($dateFin,   fn($q) => $q->where('date', '<=', $dateFin))
        ->count();

        return view('categories.index', compact('categories', 'totalDepenses'));
    }

    /**
     * Affiche le formulaire de création
     * Équivalent Java : GET /categories/create → retourne la vue du formulaire
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Enregistre une nouvelle catégorie
     * Équivalent Java : POST /categories → save()
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icone' => ['nullable', 'string', 'max:255'],
        ]);

        // Crée la catégorie en assignant le user connecté
        Auth::user()->categories()->create($validated);

        // Redirige vers la liste avec un message flash de succès
        // ->with() stocke un message temporaire en session (visible une seule fois)
        // Équivalent Java : redirectAttributes.addFlashAttribute("success", "...")
        return redirect()->route('categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Affiche le détail (on redirige vers la liste, pas besoin de page détail)
     */
    public function show(Categorie $category)
    {
        return redirect()->route('categories.index');
    }
public function edit(Categorie $category)
    {
        // $this->authorize() appelle automatiquement CategoriePolicy::update()
        // Si ça retourne false, Laravel lance une erreur 403
        // Équivalent Java : @PreAuthorize
        $this->authorize('update', $category);

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Categorie $category)
    {
        $this->authorize('update', $category);

        if ($category->is_default) {
            return redirect()->route('categories.index')
                ->with('error', 'La catégorie par défaut ne peut pas être modifiée.');
        }

        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'icone' => ['nullable', 'string', 'max:255'],
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie modifiée avec succès.');
    }

    public function destroy(Categorie $category)
    {
        $this->authorize('delete', $category);

        if ($category->is_default) {
            return redirect()->route('categories.index')
                ->with('error', 'La catégorie par défaut ne peut pas être supprimée.');
        }

        if ($category->depenses()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Impossible de supprimer : des dépenses sont liées à cette catégorie.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}
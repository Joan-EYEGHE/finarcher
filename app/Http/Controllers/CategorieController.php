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
    public function index()
    {
        // Auth::id() = l'id du user connecté
        // where() = la clause WHERE en SQL
        // orderBy() = ORDER BY
        // get() = exécute la requête et retourne une Collection (comme List<> en Java)
        $categories = Categorie::where('user_id', Auth::id())
            ->orderBy('nom')
            ->get();

        // Passe la variable $categories à la vue
        // compact('categories') crée ['categories' => $categories]
        // Équivalent Java : model.addAttribute("categories", categories)
        return view('categories.index', compact('categories'));
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

    /**
     * Affiche le formulaire d'édition
     * Équivalent Java : GET /categories/{id}/edit
     *
     * "Route Model Binding" : Laravel trouve automatiquement la Categorie par son id
     * C'est comme si Spring faisait automatiquement findById() avant d'entrer dans la méthode
     */
    public function edit(Categorie $category)
    {
        // Vérifie que la catégorie appartient au user connecté
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        return view('categories.edit', compact('category'));
    }

    /**
     * Met à jour la catégorie
     * Équivalent Java : PUT /categories/{id} → update()
     */
    public function update(Request $request, Categorie $category)
    {
        // Vérifie que la catégorie appartient au user connecté
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        // Empêche la modification de "Divers"
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

    /**
     * Supprime la catégorie (soft delete)
     * Équivalent Java : DELETE /categories/{id}
     */
    public function destroy(Categorie $category)
    {
        // Vérifie que la catégorie appartient au user connecté
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        // Empêche la suppression de "Divers"
        if ($category->is_default) {
            return redirect()->route('categories.index')
                ->with('error', 'La catégorie par défaut ne peut pas être supprimée.');
        }

        // Vérifie qu'aucune dépense n'est liée
        if ($category->depenses()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Impossible de supprimer : des dépenses sont liées à cette catégorie.');
        }

        // Soft delete (met un timestamp dans deleted_at)
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Catégorie supprimée avec succès.');
    }
}
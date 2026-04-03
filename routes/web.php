<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes publiques (accessibles sans connexion)
|--------------------------------------------------------------------------
| Équivalent Java : les URLs dans .permitAll() de Spring Security
*/

// Page d'accueil → redirige vers login
Route::get('/', function () {
    return redirect()->route('login');
});

// Afficher formulaire inscription
// Route::get = @GetMapping en Spring
// 'register' entre crochets = nom de la route (pour redirect()->route('register'))
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// Traiter formulaire inscription
// Route::post = @PostMapping en Spring
Route::post('/register', [AuthController::class, 'register']);

// Afficher formulaire connexion
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// Traiter formulaire connexion
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Routes protégées (connexion obligatoire)
|--------------------------------------------------------------------------
| middleware('auth') = l'équivalent de .authenticated() en Spring Security
| Si le user n'est pas connecté, Laravel le redirige vers 'login' automatiquement
*/

Route::middleware('auth')->group(function () {

    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard temporaire (on le remplacera plus tard)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    // CRUD Catégorie
    // Route::resource = génère automatiquement les 7 routes CRUD
    // Équivalent Java : un @RestController avec @RequestMapping("/categories")
    // qui aurait GET /categories, GET /categories/create, POST /categories,
    // GET /categories/{id}, GET /categories/{id}/edit, PUT /categories/{id}, DELETE /categories/{id}
    Route::resource('categories', \App\Http\Controllers\CategorieController::class);

    // CRUD Portefeuille (appelé "Compte" dans l'UI)
    Route::resource('portefeuilles', \App\Http\Controllers\PortefeuilleController::class);

});
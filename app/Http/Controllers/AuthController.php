<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * Affiche le formulaire d'inscription
     * Équivalent Java : le GET sur /register qui retourne la vue Thymeleaf
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Traite l'inscription
     * Équivalent Java : le POST sur /register dans ton @PostMapping
     */
    public function register(Request $request)
    {
        // Validation des données (comme @Valid + DTO en Spring)
        // Si la validation échoue, Laravel redirige automatiquement
        // vers le formulaire avec les erreurs
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'numero' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Crée le user en BDD
        // Le password est hashé automatiquement grâce au cast 'hashed' dans le modèle User
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'numero' => $validated['numero'] ?? null,
            'password' => $validated['password'],
        ]);

        // Crée la catégorie "Divers" par défaut pour ce nouveau user
        Categorie::create([
            'user_id' => $user->id,
            'nom' => 'Divers',
            'description' => 'Catégorie par défaut',
            'is_default' => true,
        ]);

        // Connecte le user automatiquement après inscription
        // Équivalent Java : SecurityContextHolder.getContext().setAuthentication(...)
        Auth::login($user);

        // Redirige vers le dashboard (on créera cette route plus tard)
        return redirect()->route('dashboard');
    }

    /**
     * Affiche le formulaire de connexion
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Traite la connexion
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Rate limiting : max 5 tentatives par minute par email
        // throttleKey = combinaison email + IP pour identifier le "qui"
        // Équivalent Java : un filtre Spring avec un compteur Redis/mémoire
        $throttleKey = strtolower($request->input('email')) . '|' . $request->ip();

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Trop de tentatives. Réessayez dans {$seconds} secondes.",
            ])->onlyInput('email');
        }

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            // Échec : on incrémente le compteur
            \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 60);

            return back()->withErrors([
                'email' => 'Email ou mot de passe incorrect.',
            ])->onlyInput('email');
        }

        // Succès : on reset le compteur
        \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalide la session et régénère le token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
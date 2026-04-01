@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-4">Bienvenue, {{ Auth::user()->name }} !</h2>
    <p class="text-gray-600">Votre espace FinArcher est prêt. Les modules arrivent bientôt.</p>
</div>
@endsection
```

---

**Récap des fichiers à créer :**
```
resources/views/
├── layouts/
│   └── app.blade.php          ← layout commun
├── auth/
│   ├── register.blade.php     ← formulaire inscription
│   └── login.blade.php        ← formulaire connexion
└── dashboard.blade.php        ← page d'accueil après connexion
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinArcher - @yield('title')</title>

    {{-- Charge Tailwind + Vite (ton npm run dev) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    {{-- Barre de navigation (visible seulement si connecté) --}}
    @auth
    <nav class="bg-white shadow p-4 flex justify-between items-center">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-600">FinArcher</a>
        <div class="flex items-center gap-4">
            <span class="text-gray-600">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-red-500 hover:text-red-700">Déconnexion</button>
            </form>
        </div>
    </nav>
    @endauth

    {{-- Contenu de la page (chaque vue remplit cette section) --}}
    <main class="max-w-4xl mx-auto mt-8 px-4">
        @yield('content')
    </main>

</body>
</html>
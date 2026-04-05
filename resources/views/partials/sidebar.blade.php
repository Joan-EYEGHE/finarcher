@php
  $currentRoute = Route::currentRouteName() ?? '';
  $userInitials = strtoupper(substr(Auth::user()->name, 0, 1));
  if (str_contains(Auth::user()->name, ' ')) {
    $parts = explode(' ', Auth::user()->name, 2);
    $userInitials = strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
  }
@endphp

<aside class="sidebar">

  {{-- Logo --}}
  <div class="sidebar-logo">
    <div class="sidebar-logo-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="5" y1="12" x2="19" y2="12"/>
        <polyline points="13 6 19 12 13 18"/>
      </svg>
    </div>
    <span class="sidebar-logo-text">FinArcher</span>
  </div>

  {{-- Navigation principale --}}
  <nav class="sidebar-nav">

    {{-- Dashboard --}}
    <a href="{{ route('dashboard') }}"
       class="sidebar-item {{ str_starts_with($currentRoute, 'dashboard') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="3" width="7" height="7" rx="1"/>
        <rect x="14" y="3" width="7" height="7" rx="1"/>
        <rect x="3" y="14" width="7" height="7" rx="1"/>
        <rect x="14" y="14" width="7" height="7" rx="1"/>
      </svg>
      Dashboard
    </a>

    {{-- Comptes (portefeuilles) --}}
    <a href="{{ route('portefeuilles.index') }}"
       class="sidebar-item {{ str_starts_with($currentRoute, 'portefeuilles') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <rect x="2" y="5" width="20" height="14" rx="2"/>
        <line x1="2" y1="10" x2="22" y2="10"/>
      </svg>
      Comptes
    </a>

    {{-- Catégories --}}
    <a href="{{ route('categories.index') }}"
       class="sidebar-item {{ str_starts_with($currentRoute, 'categories') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="3"/>
        <path d="M12 2v4m0 12v4m-7.07-15.07 2.83 2.83m8.48 8.48 2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48 2.83-2.83"/>
      </svg>
      Catégories
    </a>

    {{-- Contacts (acteurs) --}}
    <a href="{{ route('acteurs.index') }}"
       class="sidebar-item {{ str_starts_with($currentRoute, 'acteurs') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
        <circle cx="9" cy="7" r="4"/>
        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
      </svg>
      Contacts
    </a>

    {{-- Revenus --}}
    <a href="{{ route('revenus.index') }}"
       class="sidebar-item {{ str_starts_with($currentRoute, 'revenus') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
        <polyline points="17 6 23 6 23 12"/>
      </svg>
      Revenus
    </a>

    {{-- Dépenses --}}
    <a href="{{ route('depenses.index') }}"
       class="sidebar-item {{ str_starts_with($currentRoute, 'depenses') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/>
        <polyline points="17 18 23 18 23 12"/>
      </svg>
      Dépenses
    </a>

  </nav>

  {{-- Bas de sidebar : déconnexion + profil --}}
  <div class="sidebar-bottom">

    {{-- Bouton déconnexion --}}
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="sidebar-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
          <polyline points="16 17 21 12 16 7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        Déconnexion
      </button>
    </form>

    {{-- Profil utilisateur --}}
    <div class="sidebar-profile">
      <div class="sidebar-avatar">{{ $userInitials }}</div>
      <div class="sidebar-profile-info">
        <span class="sidebar-profile-name">{{ Auth::user()->name }}</span>
        <span class="sidebar-profile-email">{{ Auth::user()->email }}</span>
      </div>
    </div>

  </div>
</aside>
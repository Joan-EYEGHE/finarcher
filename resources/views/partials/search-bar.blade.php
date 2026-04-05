{{--
  Partial : barre de recherche pleine largeur + bouton "Filtres avancés"

  Variables attendues :
    $placeholder  (string)  — texte du placeholder, ex: 'Rechercher par nom...'
    $searchName   (string)  — nom du champ GET, défaut: 'search'

  Prérequis côté page parente :
    - Être dans un x-data="{ showFilters: false, ... }"
    - Wrapper la barre dans un <form method="GET" ...>
--}}

<div class="flex items-center w-full bg-white rounded-lg gap-3"
     style="border: 0.5px solid rgba(0,0,0,0.10); padding: 10px 16px;">

  {{-- Icône loupe --}}
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
       stroke="#6B7280" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
       style="flex-shrink:0">
    <circle cx="11" cy="11" r="8"/>
    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
  </svg>

  {{-- Champ de recherche --}}
  <input
    type="text"
    name="{{ $searchName ?? 'search' }}"
    value="{{ request($searchName ?? 'search') }}"
    placeholder="{{ $placeholder ?? 'Rechercher...' }}"
    autocomplete="off"
    style="flex:1; outline:none; font-size:13px; font-family:'Inter',sans-serif; background:transparent; color:#171717; border:none;"
    placeholder-color="#C4C7CC">

  {{-- Bouton Filtres avancés --}}
  <button
    type="button"
    @click="showFilters = !showFilters"
    style="display:flex; align-items:center; gap:8px; font-size:13px; color:#6B7280; background:none; border:none; cursor:pointer; flex-shrink:0; padding:0; font-family:'Inter',sans-serif;">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
         stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
      <line x1="4" y1="6" x2="20" y2="6"/>
      <line x1="4" y1="12" x2="20" y2="12"/>
      <line x1="4" y1="18" x2="20" y2="18"/>
      <circle cx="8" cy="6" r="2" fill="white"/>
      <circle cx="16" cy="12" r="2" fill="white"/>
      <circle cx="10" cy="18" r="2" fill="white"/>
    </svg>
    Filtres avancés
  </button>

</div>
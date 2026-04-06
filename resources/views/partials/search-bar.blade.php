{{--
  Partial : barre de recherche pleine largeur + bouton "Filtres avancés"

  Variables attendues :
    $placeholder   (string)       — texte du placeholder, ex: 'Rechercher par nom...'
    $searchName    (string)       — nom du champ GET, défaut: 'search' (utilisé si pas de $xModel)
    $xModel        (string|null)  — si fourni, lie l'input à une variable Alpine (filtrage temps réel)
    $withFilters   (bool|null)    — si false, masque le bouton "Filtres avancés" (défaut: true)

  Prérequis côté page parente :
    - Être dans un composant Alpine.js avec showFilters si $withFilters != false
    - Si $xModel est utilisé, l'input n'a pas de name (filtrage côté client)
    - Si pas de $xModel, wrapper dans <form method="GET">
--}}

<div class="flex items-center w-full bg-white rounded-lg"
     style="border: 0.5px solid rgba(0,0,0,0.10); padding: 10px 16px; gap: 10px;">

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
    @if(!isset($xModel))
      name="{{ $searchName ?? 'search' }}"
      value="{{ request($searchName ?? 'search') }}"
    @else
      x-model="{{ $xModel }}"
    @endif
    placeholder="{{ $placeholder ?? 'Rechercher...' }}"
    autocomplete="off"
    style="flex:1; outline:none; font-size:13px; font-family:'Inter',sans-serif; background:transparent; color:#171717; border:none;">

  {{-- Séparateur + Bouton Filtres avancés --}}
  @if(!isset($withFilters) || $withFilters !== false)
    <div style="width:1px; height:18px; background:rgba(0,0,0,0.10); flex-shrink:0;"></div>
    <button
      type="button"
      @click="showFilters = !showFilters"
      style="display:flex; align-items:center; gap:8px; font-size:13px; color:#6B7280; background:none; border:none; cursor:pointer; flex-shrink:0; padding:0; font-family:'Inter',sans-serif;">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <line x1="4" y1="21" x2="4" y2="14"/>
        <line x1="4" y1="10" x2="4" y2="3"/>
        <line x1="12" y1="21" x2="12" y2="12"/>
        <line x1="12" y1="8" x2="12" y2="3"/>
        <line x1="20" y1="21" x2="20" y2="16"/>
        <line x1="20" y1="12" x2="20" y2="3"/>
        <line x1="1" y1="14" x2="7" y2="14"/>
        <line x1="9" y1="8" x2="15" y2="8"/>
        <line x1="17" y1="16" x2="23" y2="16"/>
      </svg>
      Filtres avancés
    </button>
  @endif

</div>

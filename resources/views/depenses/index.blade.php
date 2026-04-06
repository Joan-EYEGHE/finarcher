@extends('layouts.app')

@section('title', 'Dépenses')

@section('head')
<style>
  .depenses-table {
    width: 100%;
    border-collapse: collapse;
  }
  .depenses-table thead th {
    font-size: 12px;
    font-weight: 500;
    color: #6B7280;
    text-align: left;
    padding: 12px 16px;
    border-bottom: 0.5px solid rgba(0,0,0,0.08);
    background: #FAFAF9;
  }
  .depenses-table thead th.right { text-align: right; }
  .depenses-table tbody tr { transition: background 0.1s; }
  .depenses-table tbody tr:hover { background: #FAFAF9; }
  .depenses-table tbody td {
    padding: 11px 16px;
    border-bottom: 0.5px solid #f0f0f0;
    font-size: 13px;
    vertical-align: middle;
    color: #374151;
  }
  .depenses-table tbody tr:last-child td { border-bottom: none; }
  .depenses-table tbody td.right { text-align: right; }

  .td-date { color: #6B7280; font-size: 12px; width: 100px; }
  .td-qty  { color: #374151; font-size: 12px; }
  .td-pu   { color: #6B7280; font-size: 12px; }

  .badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
  }
  .badge-alimentation { background: #FAEEDA; color: #633806; }
  .badge-transport    { background: #E6F1FB; color: #0C447C; }
  .badge-services     { background: #EEEDFE; color: #3C3489; }
  .badge-divers       { background: #F1EFE8; color: #444441; }
  .badge-sante        { background: #FCE7F3; color: #9D174D; }
  .badge-logement     { background: #E1F5EE; color: #085041; }
  .badge-default      { background: #F3F4F6; color: #374151; }

  .badge-compte {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
    background: #E1F5EE;
    color: #085041;
  }

  .amount-negative {
    color: #DC2626;
    font-weight: 500;
    font-variant-numeric: tabular-nums;
  }

  .dep-action-btn {
    width: 28px; height: 28px;
    border: 0.5px solid rgba(0,0,0,0.08);
    border-radius: 6px;
    background: transparent;
    display: inline-flex; align-items: center; justify-content: center;
    cursor: pointer;
    color: #6B7280;
    transition: all 0.15s;
  }
  .dep-action-btn:hover { background: #f3f4f6; color: #171717; }
  .dep-action-btn.danger:hover { background: #FEE2E2; color: #DC2626; }
  .dep-action-btn svg { width: 13px; height: 13px; }
  .actions-cell { display: inline-flex; align-items: center; gap: 4px; }

  .table-wrapper {
    background: #fff;
    border: 0.5px solid rgba(0,0,0,0.08);
    border-radius: 12px;
    overflow: hidden;
    margin-top: 4px;
  }

  .kpi-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 20px;
  }
  .kpi-card {
    background: #fff;
    border: 0.5px solid rgba(0,0,0,0.08);
    border-radius: 12px;
    padding: 16px 20px;
  }
  .kpi-label { font-size: 12px; color: #6B7280; margin-bottom: 6px; }
  .kpi-value { font-size: 22px; font-weight: 500; font-variant-numeric: tabular-nums; }
  .kpi-sub   { font-size: 11px; color: #6B7280; margin-top: 4px; }

  .date-input {
    padding: 7px 12px;
    border: 0.5px solid rgba(0,0,0,0.10);
    border-radius: 8px;
    font-size: 12px;
    font-family: 'Inter', sans-serif;
    color: #171717;
    background: #fff;
    outline: none;
  }
  .date-input:focus { border-color: #D97706; }

  .btn-apply {
    padding: 7px 14px;
    background: #fff;
    border: 0.5px solid rgba(0,0,0,0.10);
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
    font-family: 'Inter', sans-serif;
    color: #374151;
    cursor: pointer;
    transition: background 0.15s;
  }
  .btn-apply:hover { background: #f3f4f6; }

  .filters-panel {
    background: #fff;
    border: 0.5px solid rgba(0,0,0,0.08);
    border-radius: 10px;
    padding: 14px 16px;
    margin-top: 8px;
    display: flex;
    align-items: flex-end;
    gap: 12px;
    flex-wrap: wrap;
  }
  .filter-group { display: flex; flex-direction: column; gap: 5px; }
  .filter-label { font-size: 12px; font-weight: 500; color: #374151; }
  .filter-select {
    padding: 7px 12px;
    border: 0.5px solid rgba(0,0,0,0.10);
    border-radius: 8px;
    font-size: 13px;
    font-family: 'Inter', sans-serif;
    color: #374151;
    background: #fff;
    outline: none;
    min-width: 160px;
  }
  .filter-select:focus { border-color: #D97706; }

  .btn-reset {
    padding: 7px 14px;
    background: transparent;
    border: 0.5px solid rgba(0,0,0,0.10);
    border-radius: 8px;
    font-size: 12px;
    font-family: 'Inter', sans-serif;
    color: #6B7280;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    line-height: 1.5;
  }
  .btn-reset:hover { background: #f3f4f6; }

  /* ── État de chargement ── */
  .content-zone { position: relative; }
  .loading-overlay {
    position: absolute;
    inset: 0;
    background: rgba(248,248,247,0.70);
    backdrop-filter: blur(1px);
    border-radius: 12px;
    z-index: 20;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
  }
  @keyframes spin { to { transform: rotate(360deg); } }
  .spinner {
    width: 22px; height: 22px;
    border: 2.5px solid rgba(220,38,38,0.2);
    border-top-color: #DC2626;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
  }
  .btn-applying {
    opacity: 0.75;
    cursor: not-allowed;
    pointer-events: none;
  }

  /* ── Modale total display ── */
  .total-display {
    background: #FAFAF9;
    border: 0.5px solid rgba(0,0,0,0.08);
    border-radius: 8px;
    padding: 9px 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 100%;
    min-height: 40px;
  }
  .total-display-label { font-size: 12px; color: #6B7280; }
  .total-display-value { font-size: 14px; font-weight: 500; color: #DC2626; font-variant-numeric: tabular-nums; }
</style>
@endsection

@section('content')

<div
  x-data="depensesPage()"
  x-init="@if($errors->any()) $nextTick(() => {
    formOpen = true;
    formDateOperation = {{ json_encode(old('date_operation', '')) }};
    formDesignation = {{ json_encode(old('designation', '')) }};
    formQuantite = {{ json_encode(old('quantite', '1')) }};
    formPrix = {{ json_encode(old('prix', '')) }};
    formCategorieId = {{ json_encode(old('categorie_id', '')) }};
    formPortefeuilleId = {{ json_encode(old('portefeuille_id', '')) }};
    @if(old('_method') === 'PUT')
    formMode = 'edit';
    formAction = '/depenses/{{ old('_restore_slug', '') }}';
    currentEditSlug = '{{ old('_restore_slug', '') }}';
    @endif
  }) @endif"
  x-cloak>

  {{-- ══ En-tête + filtre période ══ --}}
  <form method="GET" action="{{ route('depenses.index') }}"
        @submit.prevent="startLoading($event.target)">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; gap:12px;">
      <h1 class="page-title">Dépenses</h1>
      <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
        <input type="date" name="date_debut" class="date-input" value="{{ $dateDebut }}">
        <span style="color:#6B7280; font-size:12px;">—</span>
        <input type="date" name="date_fin" class="date-input" value="{{ $dateFin }}">

        {{-- Conserver les filtres avancés actifs --}}
        @if(request('categorie_id'))
          <input type="hidden" name="categorie_id" value="{{ request('categorie_id') }}">
        @endif
        @if(request('portefeuille_id'))
          <input type="hidden" name="portefeuille_id" value="{{ request('portefeuille_id') }}">
        @endif

        <button type="submit" class="btn-apply" :class="{ 'btn-applying': loading }">
          <span x-show="!loading">Appliquer</span>
          <span x-show="loading" style="display:inline-flex; align-items:center; gap:5px;">
            <span style="width:12px; height:12px; border:1.5px solid rgba(55,65,81,0.3); border-top-color:#374151; border-radius:50%; display:inline-block; animation:spin 0.7s linear infinite;"></span>
            Chargement…
          </span>
        </button>

        <button
          type="button"
          @click="openCreate()"
          style="display:inline-flex; align-items:center; gap:6px; background:#D97706; color:#fff; border:none; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:500; cursor:pointer; font-family:'Inter',sans-serif; transition:background 0.15s;"
          onmouseover="this.style.background='#b45309'"
          onmouseout="this.style.background='#D97706'">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
          </svg>
          Nouvelle dépense
        </button>
      </div>
    </div>

    {{-- ══ Barre de recherche ══ --}}
    <div style="margin-bottom:0;">
      @include('partials.search-bar', [
        'placeholder' => 'Rechercher une dépense…',
        'xModel'      => 'searchTerm',
        'withFilters' => true,
      ])
    </div>

    {{-- ══ Panel filtres avancés ══ --}}
    <div x-show="showFilters" x-transition class="filters-panel">
      <div class="filter-group">
        <label class="filter-label">Catégorie</label>
        <select name="categorie_id" class="filter-select">
          <option value="">Toutes les catégories</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('categorie_id') == $cat->id ? 'selected' : '' }}>
              {{ $cat->nom }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="filter-group">
        <label class="filter-label">Compte</label>
        <select name="portefeuille_id" class="filter-select">
          <option value="">Tous les comptes</option>
          @foreach($portefeuilles as $portefeuille)
            <option value="{{ $portefeuille->id }}" {{ request('portefeuille_id') == $portefeuille->id ? 'selected' : '' }}>
              {{ $portefeuille->nom }}
            </option>
          @endforeach
        </select>
      </div>

      <div style="display:flex; align-items:center; gap:8px; margin-top:auto;">
        <button type="submit" class="btn-apply" :class="{ 'btn-applying': loading }"
          style="background:#D97706; color:#fff; border-color:#D97706;"
          onmouseover="if(!loading) this.style.background='#b45309'" onmouseout="this.style.background='#D97706'">
          <span x-show="!loading">Appliquer les filtres</span>
          <span x-show="loading" style="display:inline-flex; align-items:center; gap:5px;">
            <span style="width:12px; height:12px; border:1.5px solid rgba(255,255,255,0.4); border-top-color:#fff; border-radius:50%; display:inline-block; animation:spin 0.7s linear infinite;"></span>
            Chargement…
          </span>
        </button>
        <a href="{{ route('depenses.index') }}" class="btn-reset">Réinitialiser</a>
      </div>
    </div>
  </form>

  {{-- ══ Zone de contenu avec overlay de chargement ══ --}}
  <div class="content-zone" style="margin-top:20px;">

    {{-- Overlay chargement --}}
    <div x-show="loading" class="loading-overlay" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
      <div class="spinner"></div>
    </div>

    {{-- ══ KPIs ══ --}}
    <div class="kpi-grid">
      <div class="kpi-card">
        <div class="kpi-label">Total dépenses</div>
        <div class="kpi-value" style="color:#DC2626;">{{ number_format($totalDepenses, 0, ',', ' ') }} FCFA</div>
        <div class="kpi-sub">{{ $countDepenses }} opération{{ $countDepenses > 1 ? 's' : '' }} sur la période</div>
      </div>
      <div class="kpi-card">
        <div class="kpi-label">Dépense moyenne</div>
        <div class="kpi-value" style="color:#DC2626;">{{ number_format($moyenneDepenses, 0, ',', ' ') }} FCFA</div>
        <div class="kpi-sub">par opération</div>
      </div>
      <div class="kpi-card">
        <div class="kpi-label">Dépense maximale</div>
        @if($maxDepenseItem)
          <div class="kpi-value" style="color:#DC2626;">{{ number_format($maxDepenseItem->montant_total, 0, ',', ' ') }} FCFA</div>
          <div class="kpi-sub">{{ $maxDepenseItem->designation }} — {{ $maxDepenseItem->date_operation->format('d/m/Y') }}</div>
        @else
          <div class="kpi-value" style="color:#D1D5DB;">—</div>
          <div class="kpi-sub">aucune donnée</div>
        @endif
      </div>
    </div>

    {{-- ══ Tableau ══ --}}
    @if($depenses->isEmpty() && !request()->hasAny(['categorie_id', 'portefeuille_id']))
      <div style="text-align:center; padding:60px 0; color:#6B7280; font-size:13px;">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 12px; display:block;">
          <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/>
          <polyline points="17 18 23 18 23 12"/>
        </svg>
        Aucune dépense sur cette période.<br>
        <span style="font-size:12px; color:#9CA3AF;">Créez votre première dépense pour commencer.</span>
      </div>
    @else
      <div class="table-wrapper">
        <table class="depenses-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Description</th>
              <th>Catégorie</th>
              <th>Compte</th>
              <th class="right">Qté</th>
              <th class="right">P.U.</th>
              <th class="right">Montant</th>
              <th class="right">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($depenses as $depense)
              @php
                $nomCat = $depense->categorie->nom ?? '';
                $badgeKey = strtolower(str_replace(
                  [' ', 'é','è','ê','ë','à','â','ô','î','û','ç'],
                  ['-', 'e','e','e','e','a','a','o','i','u','c'],
                  $nomCat
                ));
                $knownBadges = ['alimentation','transport','services','divers','sante','logement'];
                $badgeClass = in_array($badgeKey, $knownBadges) ? 'badge-'.$badgeKey : 'badge-default';
              @endphp
              <tr x-show="!searchTerm || '{{ strtolower($depense->designation) }}'.includes(searchTerm.toLowerCase())">

                {{-- Date --}}
                <td class="td-date">{{ $depense->date_operation->format('d/m/Y') }}</td>

                {{-- Description --}}
                <td>{{ $depense->designation }}</td>

                {{-- Catégorie --}}
                <td>
                  <span class="badge {{ $badgeClass }}">{{ $nomCat ?: '—' }}</span>
                </td>

                {{-- Compte --}}
                <td>
                  @if($depense->portefeuille)
                    <span class="badge-compte">{{ $depense->portefeuille->nom }}</span>
                  @else
                    <span style="color:#9CA3AF;">—</span>
                  @endif
                </td>

                {{-- Qté --}}
                <td class="right td-qty">{{ number_format($depense->quantite, 0, ',', ' ') }}</td>

                {{-- P.U. --}}
                <td class="right td-pu">{{ number_format($depense->prix, 0, ',', ' ') }}</td>

                {{-- Montant --}}
                <td class="right amount-negative">−{{ number_format($depense->montant_total, 0, ',', ' ') }} FCFA</td>

                {{-- Actions --}}
                <td class="right">
                  <div class="actions-cell">
                    <button
                      class="dep-action-btn"
                      type="button"
                      title="Modifier"
                      @click="openEdit(
                        '{{ $depense->slug }}',
                        '{{ $depense->date_operation->format('Y-m-d') }}',
                        {{ json_encode($depense->designation) }},
                        '{{ $depense->quantite }}',
                        '{{ $depense->prix }}',
                        '{{ $depense->categorie_id }}',
                        '{{ $depense->portefeuille_id ?? '' }}'
                      )">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                      </svg>
                    </button>
                    <button
                      class="dep-action-btn danger"
                      type="button"
                      title="Supprimer"
                      @click="$dispatch('open-delete-modal', {
                        url: '{{ route('depenses.destroy', $depense) }}',
                        name: {{ json_encode($depense->designation) }}
                      })">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                      </svg>
                    </button>
                  </div>
                </td>

              </tr>
            @empty
              <tr>
                <td colspan="8" style="text-align:center; padding:40px; color:#9CA3AF; font-size:13px;">
                  Aucune dépense ne correspond aux filtres sélectionnés.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- ══ Pagination ══ --}}
      @include('partials.pagination', [
        'paginator'   => $depenses,
        'entityLabel' => 'dépenses',
      ])
    @endif

  </div>{{-- /content-zone --}}

  {{-- ══════════════════════════════════════════
       MODALE FORMULAIRE (création / édition)
  ══════════════════════════════════════════ --}}
  <div
    x-show="formOpen"
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="background:rgba(0,0,0,0.40);"
    @click="closeForm()">

    <div
      @click.stop
      x-transition:enter="transition ease-out duration-150"
      x-transition:enter-start="opacity-0 scale-95"
      x-transition:enter-end="opacity-100 scale-100"
      x-transition:leave="transition ease-in duration-100"
      x-transition:leave-start="opacity-100 scale-100"
      x-transition:leave-end="opacity-0 scale-95"
      class="bg-white rounded-xl shadow-xl w-full"
      style="max-width:540px; max-height:90vh; overflow-y:auto;">

      {{-- En-tête --}}
      <div style="display:flex; align-items:center; justify-content:space-between; padding:18px 24px; border-bottom:0.5px solid rgba(0,0,0,0.08);">
        <div>
          <h3 style="font-size:15px; font-weight:500; color:#171717;"
              x-text="formMode === 'create' ? 'Nouvelle dépense' : 'Modifier la dépense'"></h3>
          <p style="font-size:12px; color:#6B7280; margin-top:2px;"
             x-text="formMode === 'create' ? 'Enregistrez une nouvelle dépense.' : 'Mettez à jour les informations de la dépense.'"></p>
        </div>
        <button type="button" @click="closeForm()"
          style="width:28px; height:28px; display:flex; align-items:center; justify-content:center; border:none; background:none; cursor:pointer; border-radius:6px; color:#6B7280;"
          onmouseover="this.style.background='rgba(0,0,0,0.05)'" onmouseout="this.style.background='none'">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>

      {{-- Formulaire --}}
      <form :action="formAction" method="POST"
            style="padding:20px 24px; display:flex; flex-direction:column; gap:16px;">
        @csrf
        <input type="hidden" name="_method"       :value="formMode === 'edit' ? 'PUT' : ''">
        <input type="hidden" name="_restore_slug" :value="currentEditSlug">

        {{-- Ligne 1 : Date + Catégorie --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
          <div style="display:flex; flex-direction:column; gap:5px;">
            <label style="font-size:12px; font-weight:500; color:#374151;">Date *</label>
            <input
              type="date"
              name="date_operation"
              x-model="formDateOperation"
              required
              class="form-input">
          </div>
          <div style="display:flex; flex-direction:column; gap:5px;">
            <label style="font-size:12px; font-weight:500; color:#374151;">Catégorie *</label>
            <select name="categorie_id" x-model="formCategorieId" required class="form-input" style="cursor:pointer;">
              <option value="">Sélectionner…</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- Description --}}
        <div style="display:flex; flex-direction:column; gap:5px;">
          <label style="font-size:12px; font-weight:500; color:#374151;">Description *</label>
          <input
            type="text"
            name="designation"
            x-model="formDesignation"
            placeholder="Ex : Déjeuner Chez Loutcha"
            required
            class="form-input">
        </div>

        {{-- Ligne 3 colonnes : Quantité + Prix unitaire + Total --}}
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; align-items:end;">
          <div style="display:flex; flex-direction:column; gap:5px;">
            <label style="font-size:12px; font-weight:500; color:#374151;">Quantité *</label>
            <input
              type="number"
              name="quantite"
              x-model="formQuantite"
              placeholder="1"
              min="0.01"
              step="any"
              required
              class="form-input">
          </div>
          <div style="display:flex; flex-direction:column; gap:5px;">
            <label style="font-size:12px; font-weight:500; color:#374151;">Prix unitaire (FCFA) *</label>
            <input
              type="number"
              name="prix"
              x-model="formPrix"
              placeholder="0"
              min="0.01"
              step="any"
              required
              class="form-input">
          </div>
          <div style="display:flex; flex-direction:column; gap:5px;">
            <label style="font-size:12px; font-weight:500; color:#374151;">Total</label>
            <div class="total-display">
              <span class="total-display-label">Total</span>
              <span class="total-display-value"
                x-text="(((parseFloat(formQuantite)||0) * (parseFloat(formPrix)||0)).toLocaleString('fr-FR')) + ' FCFA'">
                0 FCFA
              </span>
            </div>
          </div>
        </div>

        {{-- Compte (optionnel) --}}
        <div style="display:flex; flex-direction:column; gap:5px;">
          <label style="font-size:12px; font-weight:500; color:#374151;">
            Compte <span style="color:#9CA3AF; font-weight:400;">(optionnel)</span>
          </label>
          <select name="portefeuille_id" x-model="formPortefeuilleId" class="form-input" style="cursor:pointer;">
            <option value="">Aucun</option>
            @foreach($portefeuilles as $portefeuille)
              <option value="{{ $portefeuille->id }}">{{ $portefeuille->nom }}</option>
            @endforeach
          </select>
        </div>

        {{-- Erreurs de validation --}}
        @if($errors->any())
          <div style="background:#FEF2F2; border:0.5px solid #FECACA; border-radius:8px; padding:10px 14px;">
            <ul style="margin:0; padding-left:16px; font-size:12px; color:#DC2626;">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        {{-- Footer --}}
        <div style="display:flex; justify-content:flex-end; gap:10px; padding-top:6px;">
          <button type="button" @click="closeForm()"
            style="padding:8px 18px; font-size:13px; font-family:'Inter',sans-serif; border:0.5px solid rgba(0,0,0,0.10); border-radius:8px; color:#6B7280; background:transparent; cursor:pointer;"
            onmouseover="this.style.background='rgba(0,0,0,0.03)'" onmouseout="this.style.background='transparent'">
            Annuler
          </button>
          <button type="submit"
            style="padding:8px 18px; font-size:13px; font-weight:500; font-family:'Inter',sans-serif; border:none; border-radius:8px; background:#D97706; color:#fff; cursor:pointer;"
            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
            Enregistrer
          </button>
        </div>
      </form>

    </div>
  </div>

  {{-- ══ Modale suppression ══ --}}
  @include('partials.delete-modal', ['entityLabel' => 'la dépense'])

</div>

@endsection

@section('scripts')
<script>
function depensesPage() {
  return {
    searchTerm: '',
    showFilters: {{ request()->hasAny(['categorie_id', 'portefeuille_id']) ? 'true' : 'false' }},
    loading: false,
    formOpen: false,
    formMode: 'create',
    formAction: '',
    currentEditSlug: '',
    formDateOperation: '{{ now()->format('Y-m-d') }}',
    formDesignation: '',
    formQuantite: '1',
    formPrix: '',
    formCategorieId: '',
    formPortefeuilleId: '',

    startLoading(form) {
      this.loading = true;
      setTimeout(() => form.submit(), 200);
    },

    openCreate() {
      this.formMode = 'create';
      this.formAction = '{{ route('depenses.store') }}';
      this.formDateOperation = '{{ now()->format('Y-m-d') }}';
      this.formDesignation = '';
      this.formQuantite = '1';
      this.formPrix = '';
      this.formCategorieId = '';
      this.formPortefeuilleId = '';
      this.currentEditSlug = '';
      this.formOpen = true;
    },

    openEdit(slug, dateOp, designation, quantite, prix, categorieId, portefeuilleId) {
      this.formMode = 'edit';
      this.formAction = '/depenses/' + slug;
      this.formDateOperation = dateOp;
      this.formDesignation = designation;
      this.formQuantite = String(quantite);
      this.formPrix = String(prix);
      this.formCategorieId = String(categorieId);
      this.formPortefeuilleId = portefeuilleId ? String(portefeuilleId) : '';
      this.currentEditSlug = slug;
      this.formOpen = true;
    },

    closeForm() {
      this.formOpen = false;
    }
  }
}
</script>
@endsection

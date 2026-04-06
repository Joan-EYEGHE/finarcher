@extends('layouts.app')

@section('title', 'Revenus')

@section('head')
<style>
  .revenus-table {
    width: 100%;
    border-collapse: collapse;
  }
  .revenus-table thead th {
    font-size: 11px;
    font-weight: 500;
    color: #6B7280;
    text-align: left;
    padding: 0 16px 10px 16px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
  }
  .revenus-table thead th:last-child { text-align: right; }
  .revenus-table tbody tr { transition: background 0.1s; }
  .revenus-table tbody tr:hover { background: #FAFAF9; }
  .revenus-table tbody td {
    padding: 11px 16px;
    border-top: 0.5px solid rgba(0,0,0,0.08);
    font-size: 13px;
    vertical-align: middle;
    color: #374151;
  }
  .revenus-table tbody tr:first-child td { border-top: none; }
  .revenus-table tbody td:last-child { text-align: right; }

  .td-date    { color: #6B7280; font-size: 12px; width: 100px; }
  .td-motif   { color: #6B7280; font-size: 12px; }
  .td-contact { font-size: 12px; color: #374151; }

  .badge-compte {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
    background: #E1F5EE;
    color: #085041;
  }

  .amount-positive {
    color: #059669;
    font-weight: 500;
    font-variant-numeric: tabular-nums;
  }

  .rev-action-btn {
    width: 28px; height: 28px;
    border: 0.5px solid rgba(0,0,0,0.08);
    border-radius: 6px;
    background: transparent;
    display: inline-flex; align-items: center; justify-content: center;
    cursor: pointer;
    color: #6B7280;
    transition: all 0.15s;
  }
  .rev-action-btn:hover { background: #f3f4f6; color: #171717; }
  .rev-action-btn.danger:hover { background: #FEE2E2; color: #DC2626; }
  .rev-action-btn svg { width: 13px; height: 13px; }
  .actions-cell { display: inline-flex; align-items: center; gap: 4px; }

  .table-wrapper {
    background: #fff;
    border: 0.5px solid rgba(0,0,0,0.08);
    border-radius: 12px;
    overflow: hidden;
    margin-top: 4px;
    padding: 0 0 4px 0;
  }
  .table-inner { padding: 18px 0 0 0; }

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
  .kpi-value { font-size: 22px; font-weight: 500; font-variant-numeric: tabular-nums; color: #059669; }
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
</style>
@endsection

@section('content')

<div
  x-data="revenusPage()"
  x-init="@if($errors->any()) $nextTick(() => {
    formOpen = true;
    formDateOperation = {{ json_encode(old('date_operation', '')) }};
    formMotif = {{ json_encode(old('motif', '')) }};
    formMontant = {{ json_encode(old('montant', '')) }};
    formPortefeuilleId = {{ json_encode(old('portefeuille_id', '')) }};
    formActeurId = {{ json_encode(old('acteur_id', '')) }};
    @if(old('_method') === 'PUT')
    formMode = 'edit';
    formAction = '/revenus/{{ old('_restore_slug', '') }}';
    currentEditSlug = '{{ old('_restore_slug', '') }}';
    @endif
  }) @endif"
  x-cloak>

  {{-- ══ En-tête + filtre période ══ --}}
  <form method="GET" action="{{ route('revenus.index') }}">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; gap:12px;">
      <h1 class="page-title">Revenus</h1>
      <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
        <input type="date" name="date_debut" class="date-input" value="{{ $dateDebut }}">
        <span style="color:#6B7280; font-size:12px;">—</span>
        <input type="date" name="date_fin" class="date-input" value="{{ $dateFin }}">

        {{-- Conserver les filtres avancés actifs --}}
        @if(request('acteur_id'))
          <input type="hidden" name="acteur_id" value="{{ request('acteur_id') }}">
        @endif
        @if(request('portefeuille_id'))
          <input type="hidden" name="portefeuille_id" value="{{ request('portefeuille_id') }}">
        @endif

        <button type="submit" class="btn-apply">Appliquer</button>

        <button
          type="button"
          @click="openCreate()"
          style="display:inline-flex; align-items:center; gap:6px; background:#D97706; color:#fff; border:none; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:500; cursor:pointer; font-family:'Inter',sans-serif; transition:background 0.15s;"
          onmouseover="this.style.background='#b45309'"
          onmouseout="this.style.background='#D97706'">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
          </svg>
          Nouveau revenu
        </button>
      </div>
    </div>

    {{-- ══ Barre de recherche ══ --}}
    <div style="margin-bottom:0;">
      @include('partials.search-bar', [
        'placeholder' => 'Rechercher un revenu…',
        'xModel'      => 'searchTerm',
        'withFilters' => true,
      ])
    </div>

    {{-- ══ Panel filtres avancés ══ --}}
    <div x-show="showFilters" x-transition class="filters-panel">
      <div class="filter-group">
        <label class="filter-label">Contact</label>
        <select name="acteur_id" class="filter-select">
          <option value="">Tous les contacts</option>
          @foreach($acteurs as $acteur)
            <option value="{{ $acteur->id }}" {{ request('acteur_id') == $acteur->id ? 'selected' : '' }}>
              {{ $acteur->nom }}
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
        <button type="submit" class="btn-apply" style="background:#D97706; color:#fff; border-color:#D97706;"
          onmouseover="this.style.background='#b45309'" onmouseout="this.style.background='#D97706'">
          Appliquer les filtres
        </button>
        <a href="{{ route('revenus.index') }}" class="btn-reset">Réinitialiser</a>
      </div>
    </div>
  </form>

  {{-- ══ KPIs ══ --}}
  <div class="kpi-grid" style="margin-top:20px;">
    <div class="kpi-card">
      <div class="kpi-label">Total revenus</div>
      <div class="kpi-value">{{ number_format($totalRevenus, 0, ',', ' ') }} FCFA</div>
      <div class="kpi-sub">{{ $countRevenus }} opération{{ $countRevenus > 1 ? 's' : '' }} sur la période</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-label">Revenu moyen</div>
      <div class="kpi-value">{{ number_format($moyenneRevenus, 0, ',', ' ') }} FCFA</div>
      <div class="kpi-sub">par opération</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-label">Plus gros revenu</div>
      @if($maxRevenuItem)
        <div class="kpi-value">{{ number_format($maxRevenuItem->montant, 0, ',', ' ') }} FCFA</div>
        <div class="kpi-sub">
          {{ $maxRevenuItem->motif ?: 'Sans motif' }} — {{ $maxRevenuItem->date_operation->format('d/m/Y') }}
        </div>
      @else
        <div class="kpi-value" style="color:#D1D5DB;">—</div>
        <div class="kpi-sub">aucune donnée</div>
      @endif
    </div>
  </div>

  {{-- ══ Tableau ══ --}}
  @if($revenus->isEmpty() && !request()->hasAny(['acteur_id', 'portefeuille_id']))
    <div style="text-align:center; padding:60px 0; color:#6B7280; font-size:13px;">
      <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 12px; display:block;">
        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
        <polyline points="17 6 23 6 23 12"/>
      </svg>
      Aucun revenu sur cette période.<br>
      <span style="font-size:12px; color:#9CA3AF;">Créez votre premier revenu pour commencer.</span>
    </div>
  @else
    <div class="table-wrapper">
      <div class="table-inner">
        <table class="revenus-table">
          <thead>
            <tr>
              <th style="padding-left:16px;">Date</th>
              <th>Motif</th>
              <th>Contact</th>
              <th>Compte</th>
              <th>Montant</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($revenus as $revenu)
              <tr x-show="!searchTerm || '{{ strtolower($revenu->motif ?? '') }}'.includes(searchTerm.toLowerCase())">

                {{-- Date --}}
                <td class="td-date">{{ $revenu->date_operation->format('d/m/Y') }}</td>

                {{-- Motif --}}
                <td class="td-motif">{{ $revenu->motif ?: '—' }}</td>

                {{-- Contact --}}
                <td class="td-contact">{{ $revenu->acteur->nom }}</td>

                {{-- Compte --}}
                <td>
                  <span class="badge-compte">{{ $revenu->portefeuille->nom }}</span>
                </td>

                {{-- Montant --}}
                <td class="amount-positive">+{{ number_format($revenu->montant, 0, ',', ' ') }} FCFA</td>

                {{-- Actions --}}
                <td>
                  <div class="actions-cell">
                    <button
                      class="rev-action-btn"
                      type="button"
                      title="Modifier"
                      @click="openEdit(
                        '{{ $revenu->slug }}',
                        '{{ $revenu->date_operation->format('Y-m-d') }}',
                        {{ json_encode($revenu->motif ?? '') }},
                        '{{ $revenu->montant }}',
                        '{{ $revenu->portefeuille_id }}',
                        '{{ $revenu->acteur_id }}'
                      )">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                      </svg>
                    </button>
                    <button
                      class="rev-action-btn danger"
                      type="button"
                      title="Supprimer"
                      @click="$dispatch('open-delete-modal', {
                        url: '{{ route('revenus.destroy', $revenu) }}',
                        name: {{ json_encode($revenu->motif ?: '+' . number_format($revenu->montant, 0, ',', ' ') . ' FCFA') }}
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
                <td colspan="6" style="text-align:center; padding:40px; color:#9CA3AF; font-size:13px;">
                  Aucun revenu ne correspond aux filtres sélectionnés.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- ══ Pagination ══ --}}
    @include('partials.pagination', [
      'paginator'   => $revenus,
      'entityLabel' => 'revenus',
    ])
  @endif

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
      style="max-width:500px; max-height:90vh; overflow-y:auto;">

      {{-- En-tête --}}
      <div style="display:flex; align-items:center; justify-content:space-between; padding:18px 24px; border-bottom:0.5px solid rgba(0,0,0,0.08);">
        <div>
          <h3 style="font-size:15px; font-weight:500; color:#171717;"
              x-text="formMode === 'create' ? 'Nouveau revenu' : 'Modifier le revenu'"></h3>
          <p style="font-size:12px; color:#6B7280; margin-top:2px;"
             x-text="formMode === 'create' ? 'Enregistrez un nouveau revenu.' : 'Mettez à jour les informations du revenu.'"></p>
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

        {{-- Ligne 1 : Date + Montant --}}
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
            <label style="font-size:12px; font-weight:500; color:#374151;">Montant (FCFA) *</label>
            <input
              type="number"
              name="montant"
              x-model="formMontant"
              placeholder="Ex : 350000"
              min="0.01"
              step="any"
              required
              class="form-input">
          </div>
        </div>

        {{-- Motif --}}
        <div style="display:flex; flex-direction:column; gap:5px;">
          <label style="font-size:12px; font-weight:500; color:#374151;">
            Motif <span style="color:#9CA3AF; font-weight:400;">(optionnel)</span>
          </label>
          <input
            type="text"
            name="motif"
            x-model="formMotif"
            placeholder="Ex : Salaire mars"
            class="form-input">
        </div>

        {{-- Ligne 2 : Contact + Compte --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
          <div style="display:flex; flex-direction:column; gap:5px;">
            <label style="font-size:12px; font-weight:500; color:#374151;">Contact (source) *</label>
            <select name="acteur_id" x-model="formActeurId" required class="form-input" style="cursor:pointer;">
              <option value="">Sélectionner…</option>
              @foreach($acteurs as $acteur)
                <option value="{{ $acteur->id }}">{{ $acteur->nom }}</option>
              @endforeach
            </select>
          </div>
          <div style="display:flex; flex-direction:column; gap:5px;">
            <label style="font-size:12px; font-weight:500; color:#374151;">Compte (destination) *</label>
            <select name="portefeuille_id" x-model="formPortefeuilleId" required class="form-input" style="cursor:pointer;">
              <option value="">Sélectionner…</option>
              @foreach($portefeuilles as $portefeuille)
                <option value="{{ $portefeuille->id }}">{{ $portefeuille->nom }}</option>
              @endforeach
            </select>
          </div>
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
            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'"
            x-text="formMode === 'create' ? 'Enregistrer' : 'Enregistrer'">
          </button>
        </div>
      </form>

    </div>
  </div>

  {{-- ══ Modale suppression ══ --}}
  @include('partials.delete-modal', ['entityLabel' => 'le revenu'])

</div>

@endsection

@section('scripts')
<script>
function revenusPage() {
  return {
    searchTerm: '',
    showFilters: {{ request()->hasAny(['acteur_id', 'portefeuille_id']) ? 'true' : 'false' }},
    formOpen: false,
    formMode: 'create',
    formAction: '',
    currentEditSlug: '',
    formDateOperation: '{{ now()->format('Y-m-d') }}',
    formMotif: '',
    formMontant: '',
    formPortefeuilleId: '',
    formActeurId: '',

    openCreate() {
      this.formMode = 'create';
      this.formAction = '{{ route('revenus.store') }}';
      this.formDateOperation = '{{ now()->format('Y-m-d') }}';
      this.formMotif = '';
      this.formMontant = '';
      this.formPortefeuilleId = '';
      this.formActeurId = '';
      this.currentEditSlug = '';
      this.formOpen = true;
    },

    openEdit(slug, dateOp, motif, montant, portefeuilleId, acteurId) {
      this.formMode = 'edit';
      this.formAction = '/revenus/' + slug;
      this.formDateOperation = dateOp;
      this.formMotif = motif || '';
      this.formMontant = montant;
      this.formPortefeuilleId = String(portefeuilleId);
      this.formActeurId = String(acteurId);
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

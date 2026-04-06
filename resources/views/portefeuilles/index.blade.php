@extends('layouts.app')

@section('title', 'Comptes')

@section('head')
<style>
  .comptes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 14px;
    margin-bottom: 20px;
  }
  .compte-card {
    background: #fff;
    border: 0.5px solid rgba(0,0,0,0.08);
    border-radius: 12px;
    padding: 18px 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    transition: border-color 0.15s;
  }
  .compte-card:hover { border-color: rgba(0,0,0,0.15); }
  .compte-action-btn {
    width: 30px; height: 30px;
    border: 0.5px solid rgba(0,0,0,0.08);
    border-radius: 6px;
    background: transparent;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    color: #6B7280;
    transition: all 0.15s;
  }
  .compte-action-btn:hover { background: #f3f4f6; color: #171717; }
  .compte-action-btn.danger:hover { background: #FEE2E2; color: #DC2626; }
  .compte-action-btn svg { width: 14px; height: 14px; }
</style>
@endsection

@section('content')

@php
$deviseColors = [
  'XOF' => ['bg' => '#E1F5EE', 'color' => '#085041'],
  'EUR' => ['bg' => '#E6F1FB', 'color' => '#0C447C'],
  'USD' => ['bg' => '#EEEDFE', 'color' => '#3C3489'],
  'GBP' => ['bg' => '#FFF7ED', 'color' => '#92400E'],
];
$defaultDeviseColor = ['bg' => '#F3F4F6', 'color' => '#6B7280'];
@endphp

<div
  x-data="comptesPage()"
  x-init="@if($errors->any()) $nextTick(() => {
    formOpen = true;
    formNom = {{ json_encode(old('nom', '')) }};
    formDeviseId = {{ json_encode(old('devise_id', '')) }};
    formIcone = {{ json_encode(old('icone', '')) }};
    @if(old('_method') === 'PUT')
    formMode = 'edit';
    formAction = '/portefeuilles/{{ old('_restore_slug', '') }}';
    currentEditSlug = '{{ old('_restore_slug', '') }}';
    @endif
  }) @endif"
  x-cloak>

  {{-- ══ En-tête ══ --}}
  <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:22px;">
    <h1 class="page-title">Comptes</h1>
    <button
      @click="openCreate()"
      style="display:inline-flex; align-items:center; gap:6px; background:#D97706; color:#fff; border:none; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:500; cursor:pointer; font-family:'Inter',sans-serif; transition:background 0.15s;"
      onmouseover="this.style.background='#b45309'"
      onmouseout="this.style.background='#D97706'">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
      </svg>
      Nouveau compte
    </button>
  </div>

  {{-- ══ Barre de recherche ══ --}}
  <div style="margin-bottom:20px;">
    @include('partials.search-bar', [
      'placeholder' => 'Rechercher un compte...',
      'xModel'      => 'searchTerm',
      'withFilters' => false,
    ])
  </div>

  {{-- ══ Grille des comptes ══ --}}
  @if($portefeuilles->isEmpty())
    <div style="text-align:center; padding:60px 0; color:#6B7280; font-size:13px;">
      <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 12px; display:block;">
        <rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/>
      </svg>
      Aucun compte trouvé.<br>
      <span style="font-size:12px; color:#9CA3AF;">Créez votre premier compte pour commencer à suivre vos finances.</span>
    </div>
  @else
    <div class="comptes-grid">
      @foreach($portefeuilles as $portefeuille)
        @php
          $code   = $portefeuille->devise->code ?? 'XOF';
          $colors = $deviseColors[$code] ?? $defaultDeviseColor;
          $solde  = $portefeuille->solde;
        @endphp

        <div class="compte-card"
             x-show="!searchTerm || '{{ strtolower($portefeuille->nom) }}'.includes(searchTerm.toLowerCase())">

          {{-- Top : badge devise + boutons action --}}
          <div style="display:flex; align-items:flex-start; justify-content:space-between;">

            {{-- Badge devise --}}
            <div style="width:36px; height:36px; border-radius:10px; background:{{ $colors['bg'] }}; color:{{ $colors['color'] }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
              <span style="font-size:11px; font-weight:600;">{{ $code }}</span>
            </div>

            {{-- Boutons --}}
            <div style="display:flex; gap:4px;">
              <button
                class="compte-action-btn"
                title="Modifier"
                @click="openEdit(
                  '{{ $portefeuille->slug }}',
                  {{ json_encode($portefeuille->nom) }},
                  {{ json_encode((string) $portefeuille->devise_id) }},
                  {{ json_encode($portefeuille->icone ?? '') }}
                )">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
              </button>
              <button
                class="compte-action-btn danger"
                title="Supprimer"
                @click="$dispatch('open-delete-modal', {
                  url: '{{ route('portefeuilles.destroy', $portefeuille) }}',
                  name: {{ json_encode($portefeuille->nom) }}
                })">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="3 6 5 6 21 6"/>
                  <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                </svg>
              </button>
            </div>

          </div>

          {{-- Nom + devise --}}
          <div>
            <div style="font-size:14px; font-weight:500; color:#171717;">{{ $portefeuille->nom }}</div>
            <div style="font-size:11px; color:#6B7280; margin-top:2px;">{{ $portefeuille->devise->nom ?? '' }} ({{ $code }})</div>
          </div>

          {{-- Solde --}}
          <div>
            <div style="font-size:11px; color:#6B7280; margin-bottom:2px;">Solde actuel</div>
            <div style="font-size:22px; font-weight:500; font-variant-numeric:tabular-nums; color:{{ $solde >= 0 ? '#059669' : '#DC2626' }};">
              {{ number_format($solde, 0, ',', ' ') }} FCFA
            </div>
          </div>

          {{-- Meta stats --}}
          <div style="display:flex; align-items:center; gap:12px; padding-top:10px; border-top:0.5px solid rgba(0,0,0,0.06);">
            <div style="font-size:11px; color:#6B7280; display:flex; align-items:center; gap:4px;">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/>
              </svg>
              {{ $portefeuille->revenus_count }} revenu{{ $portefeuille->revenus_count > 1 ? 's' : '' }}
            </div>
            <div style="width:3px; height:3px; border-radius:50%; background:#D1D5DB; flex-shrink:0;"></div>
            <div style="font-size:11px; color:#6B7280; display:flex; align-items:center; gap:4px;">
              <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/>
              </svg>
              {{ $portefeuille->depenses_count }} dépense{{ $portefeuille->depenses_count > 1 ? 's' : '' }}
            </div>
          </div>

        </div>
      @endforeach
    </div>

    {{-- Pagination --}}
    @include('partials.pagination', [
        'paginator'   => $portefeuilles,
        'entityLabel' => 'comptes',
    ])
  @endif

  {{-- ══════════════════════════════════════════════
       MODALE FORMULAIRE (création / édition)
  ══════════════════════════════════════════════ --}}
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
      style="max-width:480px; max-height:90vh; overflow-y:auto;">

      {{-- En-tête --}}
      <div style="display:flex; align-items:center; justify-content:space-between; padding:18px 24px; border-bottom:0.5px solid rgba(0,0,0,0.08);">
        <div>
          <h3 style="font-size:15px; font-weight:500; color:#171717;"
              x-text="formMode === 'create' ? 'Nouveau compte' : 'Modifier le compte'"></h3>
          <p style="font-size:12px; color:#6B7280; margin-top:2px;"
             x-text="formMode === 'create' ? 'Ajoutez un compte pour suivre vos finances.' : 'Mettez à jour les informations du compte.'"></p>
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

        {{-- Nom --}}
        <div style="display:flex; flex-direction:column; gap:5px;">
          <label style="font-size:12px; font-weight:500; color:#374151;">Nom du compte *</label>
          <input
            type="text"
            name="nom"
            x-model="formNom"
            placeholder="Ex : Wave Mobile"
            required
            class="form-input">
        </div>

        {{-- Devise --}}
        <div style="display:flex; flex-direction:column; gap:5px;">
          <label style="font-size:12px; font-weight:500; color:#374151;">Devise *</label>
          <select name="devise_id" x-model="formDeviseId" required class="form-input">
            <option value="">Sélectionner une devise</option>
            @foreach($devises as $devise)
              <option value="{{ $devise->id }}">{{ $devise->nom }} ({{ $devise->code }})</option>
            @endforeach
          </select>
        </div>

        {{-- Icône --}}
        <div style="display:flex; flex-direction:column; gap:5px;">
          <label style="font-size:12px; font-weight:500; color:#374151;">
            Icône <span style="color:#9CA3AF; font-weight:400;">(optionnel)</span>
          </label>
          <input
            type="text"
            name="icone"
            x-model="formIcone"
            placeholder="Ex : wallet, bank, phone"
            class="form-input">
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
            x-text="formMode === 'create' ? 'Créer le compte' : 'Enregistrer'">
          </button>
        </div>
      </form>

    </div>
  </div>

  {{-- ══ Modale suppression ══ --}}
  @include('partials.delete-modal', ['entityLabel' => 'le compte'])

</div>

@endsection

@section('scripts')
<script>
function comptesPage() {
  return {
    searchTerm: '',
    showFilters: false,
    formOpen: false,
    formMode: 'create',
    formAction: '',
    formNom: '',
    formDeviseId: '',
    formIcone: '',
    currentEditSlug: '',

    openCreate() {
      this.formMode = 'create';
      this.formAction = '{{ route('portefeuilles.store') }}';
      this.formNom = '';
      this.formDeviseId = '';
      this.formIcone = '';
      this.currentEditSlug = '';
      this.formOpen = true;
    },

    openEdit(slug, nom, deviseId, icone) {
      this.formMode = 'edit';
      this.formAction = '/portefeuilles/' + slug;
      this.formNom = nom;
      this.formDeviseId = deviseId;
      this.formIcone = icone || '';
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

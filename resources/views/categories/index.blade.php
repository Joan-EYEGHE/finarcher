@extends('layouts.app')

@section('title', 'Catégories')

@section('head')
<style>
  .cat-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 20px;
  }
  .cat-card {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 16px;
    min-height: 190px;
  }
  .cat-action-btn {
    width: 28px; height: 28px;
    display: flex; align-items: center; justify-content: center;
    border: 0.5px solid rgba(0,0,0,0.08);
    border-radius: 6px;
    background: #fff;
    cursor: pointer;
    color: #6B7280;
    transition: background 0.15s, color 0.15s;
  }
  .cat-action-btn:hover { background: rgba(0,0,0,0.04); color: #171717; }
  .cat-action-btn.danger:hover { background: #FEE2E2; color: #DC2626; }
  .cat-action-btn svg { width: 14px; height: 14px; }
</style>
@endsection

@section('content')

@php
$colorMap = [
  'alimentation' => ['icon_bg' => '#FAEEDA', 'icon_color' => '#633806', 'bar' => '#D97706', 'pct_color' => '#633806'],
  'transport'    => ['icon_bg' => '#E6F1FB', 'icon_color' => '#0C447C', 'bar' => '#2563EB', 'pct_color' => '#0C447C'],
  'services'     => ['icon_bg' => '#EEEDFE', 'icon_color' => '#3C3489', 'bar' => '#7C3AED', 'pct_color' => '#3C3489'],
  'sante'        => ['icon_bg' => '#FCE7F3', 'icon_color' => '#9D174D', 'bar' => '#EC4899', 'pct_color' => '#9D174D'],
  'logement'     => ['icon_bg' => '#E1F5EE', 'icon_color' => '#085041', 'bar' => '#059669', 'pct_color' => '#085041'],
  'divers'       => ['icon_bg' => '#F1EFE8', 'icon_color' => '#444441', 'bar' => '#a8a8a4', 'pct_color' => '#444441'],
];
$defaultColors = ['icon_bg' => '#F3F4F6', 'icon_color' => '#6B7280', 'bar' => '#9CA3AF', 'pct_color' => '#6B7280'];

$svgIcons = [
  'alimentation' => '<path d="M18 8h1a4 4 0 010 8h-1"/><path d="M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/>',
  'transport'    => '<rect x="1" y="3" width="15" height="13" rx="2"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
  'services'     => '<rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/>',
  'sante'        => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
  'logement'     => '<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
  'divers'       => '<circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/>',
];
$defaultSvg = '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 01-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/>';
@endphp

<div
  x-data="categoriesPage()"
  x-init="@if($errors->any()) $nextTick(() => {
    formOpen = true;
    formNom = {{ json_encode(old('nom', '')) }};
    formDescription = {{ json_encode(old('description', '')) }};
    @if(old('_method') === 'PUT')
    formMode = 'edit';
    formAction = '/categories/{{ old('_restore_slug', '') }}';
    currentEditSlug = '{{ old('_restore_slug', '') }}';
    @endif
  }) @endif"
  x-cloak>

  {{-- ══ En-tête ══ --}}
  <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:22px;">
    <h1 class="page-title">Catégories</h1>
    <button
      @click="openCreate()"
      style="display:inline-flex; align-items:center; gap:6px; background:#D97706; color:#fff; border:none; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:500; cursor:pointer; font-family:'Inter',sans-serif; transition:background 0.15s;"
      onmouseover="this.style.background='#b45309'"
      onmouseout="this.style.background='#D97706'">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
      </svg>
      Nouvelle catégorie
    </button>
  </div>

  {{-- ══ Messages flash ══ --}}
  @if(session('success'))
    <div style="background:#E1F5EE; border:0.5px solid #A7F3D0; border-radius:8px; padding:10px 14px; margin-bottom:16px; font-size:13px; color:#085041;">
      {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div style="background:#FEE2E2; border:0.5px solid #FECACA; border-radius:8px; padding:10px 14px; margin-bottom:16px; font-size:13px; color:#DC2626;">
      {{ session('error') }}
    </div>
  @endif

  {{-- ══ Barre de recherche ══ --}}
  <form method="GET" action="{{ route('categories.index') }}"
        x-data="{ showFilters: false }"
        style="margin-bottom:20px;">
    @include('partials.search-bar', ['placeholder' => 'Rechercher une catégorie...', 'searchName' => 'search'])
    <input type="hidden" name="per_page" value="{{ request('per_page', 12) }}">
  </form>

  {{-- ══ Grille de catégories ══ --}}
  @if($categories->isEmpty())
    <div style="text-align:center; padding:48px 0; color:var(--text-secondary); font-size:13px;">
      Aucune catégorie trouvée.
    </div>
  @else
    <div class="cat-grid">
      @foreach($categories as $category)
        @php
          $slug   = \Illuminate\Support\Str::slug($category->nom);
          $colors = $colorMap[$slug] ?? $defaultColors;
          $icon   = $svgIcons[$slug] ?? $defaultSvg;
          $count  = $category->depenses_count;
          $pct    = $totalDepenses > 0 ? min((int) round($count / $totalDepenses * 100), 100) : 0;
        @endphp

        <div class="card cat-card">

          {{-- Top : badge icône + boutons action --}}
          <div style="display:flex; align-items:flex-start; justify-content:space-between;">

            {{-- Badge icône --}}
            <div style="width:38px; height:38px; border-radius:10px; background:{{ $colors['icon_bg'] }}; color:{{ $colors['icon_color'] }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                {!! $icon !!}
              </svg>
            </div>

            {{-- Boutons --}}
            <div style="display:flex; gap:4px;">
              {{-- Edit (toutes les catégories) --}}
              <button
                class="cat-action-btn"
                title="Modifier"
                @click="openEdit(
                  '{{ $category->slug }}',
                  {{ json_encode($category->nom) }},
                  {{ json_encode($category->description ?? '') }}
                )">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
              </button>

              {{-- Delete (non-default uniquement) --}}
              @unless($category->is_default)
              <button
                class="cat-action-btn danger"
                title="Supprimer"
                @click="$dispatch('open-delete-modal', {
                  url: '{{ route('categories.destroy', $category) }}',
                  name: {{ json_encode($category->nom) }}
                })">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="3 6 5 6 21 6"/>
                  <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                </svg>
              </button>
              @endunless
            </div>

          </div>

          {{-- Nom + badge "Par défaut" --}}
          <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
            <span style="font-size:14px; font-weight:500; color:var(--text-primary);">{{ $category->nom }}</span>
            @if($category->is_default)
              <span style="font-size:10px; font-weight:500; padding:2px 8px; border-radius:20px; background:#F1EFE8; color:#444441;">
                Par défaut
              </span>
            @endif
          </div>

          {{-- Description --}}
          <p style="font-size:12px; color:#6B7280; margin:0; line-height:1.5; min-height:18px;">
            {{ $category->description ?? '' }}
          </p>

          {{-- Stats --}}
          <div style="border-top:0.5px solid rgba(0,0,0,0.06); padding-top:10px; margin-top:auto; display:flex; align-items:center; gap:14px;">

            {{-- Compteur dépenses --}}
            <div style="display:flex; flex-direction:column; gap:2px; flex-shrink:0;">
              <span style="font-size:11px; color:#9CA3AF;">Dépenses</span>
              <span style="font-size:13px; font-weight:500; font-variant-numeric:tabular-nums;">{{ $count }}</span>
            </div>

            {{-- Barre de progression avec flèche --}}
            <div style="flex:1; display:flex; flex-direction:column; gap:3px;">
              <div style="display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:11px; color:#9CA3AF;">Part des dépenses</span>
                <span style="font-size:11px; font-weight:500; color:{{ $colors['pct_color'] }};">{{ $pct }}%</span>
              </div>
              <div class="category-bar-track">
                <div class="category-bar-fill"
                     style="width:{{ $pct }}%; background:{{ $colors['bar'] }}; color:{{ $colors['bar'] }};"></div>
              </div>
            </div>

          </div>

        </div>
      @endforeach
    </div>

    {{-- Pagination --}}
    @include('partials.pagination', [
        'paginator'   => $categories,
        'entityLabel' => 'catégories',
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
        <h3 style="font-size:15px; font-weight:500; color:#171717;"
            x-text="formMode === 'create' ? 'Nouvelle catégorie' : 'Modifier la catégorie'"></h3>
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
        <input type="hidden" name="_method"        :value="formMode === 'edit' ? 'PUT' : ''">
        <input type="hidden" name="_restore_slug"  :value="currentEditSlug">

        {{-- Nom --}}
        <div style="display:flex; flex-direction:column; gap:5px;">
          <label style="font-size:12px; font-weight:500; color:#374151;">Nom de la catégorie *</label>
          <input
            type="text"
            name="nom"
            x-model="formNom"
            placeholder="Ex : Loisirs"
            required
            class="form-input">
        </div>

        {{-- Description --}}
        <div style="display:flex; flex-direction:column; gap:5px;">
          <label style="font-size:12px; font-weight:500; color:#374151;">Description <span style="color:#9CA3AF; font-weight:400;">(optionnel)</span></label>
          <textarea
            name="description"
            x-model="formDescription"
            placeholder="Ex : Sorties, cinéma, divertissements…"
            rows="3"
            class="form-input"
            style="resize:vertical;"></textarea>
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
            x-text="formMode === 'create' ? 'Créer' : 'Enregistrer'">
          </button>
        </div>
      </form>

    </div>
  </div>

  {{-- ══ Modale suppression ══ --}}
  @include('partials.delete-modal', ['entityLabel' => 'la catégorie'])

</div>

@endsection

@section('scripts')
<script>
function categoriesPage() {
  return {
    formOpen: false,
    formMode: 'create',
    formAction: '',
    formNom: '',
    formDescription: '',
    currentEditSlug: '',

    openCreate() {
      this.formMode = 'create';
      this.formAction = '{{ route('categories.store') }}';
      this.formNom = '';
      this.formDescription = '';
      this.currentEditSlug = '';
      this.formOpen = true;
    },

    openEdit(slug, nom, description) {
      this.formMode = 'edit';
      this.formAction = '/categories/' + slug;
      this.formNom = nom;
      this.formDescription = description || '';
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

@extends('layouts.app')

@section('title', 'Contacts')

@section('head')
<style>
  .table-wrapper {
    background: #fff;
    border: 0.5px solid rgba(0,0,0,0.08);
    border-radius: 12px;
    overflow: hidden;
    margin-top: 20px;
  }
  .contacts-table {
    width: 100%;
    border-collapse: collapse;
  }
  .contacts-table thead th {
    font-size: 12px;
    font-weight: 500;
    color: #6B7280;
    text-align: left;
    padding: 12px 16px;
    border-bottom: 0.5px solid rgba(0,0,0,0.08);
    background: #FAFAF9;
  }
  .contacts-table thead th:last-child { text-align: right; }
  .contacts-table tbody tr { transition: background 0.1s; }
  .contacts-table tbody tr:hover { background: #FAFAF9; }
  .contacts-table tbody td {
    padding: 11px 16px;
    border-bottom: 0.5px solid #f0f0f0;
    font-size: 13px;
    vertical-align: middle;
    color: #374151;
  }
  .contacts-table tbody tr:last-child td { border-bottom: none; }
  .contacts-table tbody td:last-child { text-align: right; }

  .contact-name-cell { display: flex; align-items: center; gap: 10px; }
  .contact-avatar {
    width: 30px; height: 30px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 500;
    flex-shrink: 0;
  }
  .av-1 { background: #E1F5EE; color: #085041; }
  .av-2 { background: #FAEEDA; color: #633806; }
  .av-3 { background: #E6F1FB; color: #0C447C; }
  .av-4 { background: #EEEDFE; color: #3C3489; }
  .av-5 { background: #F1EFE8; color: #444441; }
  .contact-name { font-weight: 500; color: #171717; }

  .contact-action-btn {
    width: 30px; height: 30px;
    border: 0.5px solid rgba(0,0,0,0.08);
    border-radius: 6px;
    background: transparent;
    display: inline-flex; align-items: center; justify-content: center;
    cursor: pointer;
    color: #6B7280;
    transition: all 0.15s;
  }
  .contact-action-btn:hover { background: #f3f4f6; color: #171717; }
  .contact-action-btn.danger:hover { background: #FEE2E2; color: #DC2626; }
  .contact-action-btn svg { width: 14px; height: 14px; }
  .actions-cell { display: inline-flex; align-items: center; gap: 4px; }
</style>
@endsection

@section('content')

<div
  x-data="contactsPage()"
  x-init="@if($errors->any()) $nextTick(() => {
    formOpen = true;
    formNom = {{ json_encode(old('nom', '')) }};
    formAdresse = {{ json_encode(old('adresse', '')) }};
    formNumero = {{ json_encode(old('numero', '')) }};
    @if(old('_method') === 'PUT')
    formMode = 'edit';
    formAction = '/acteurs/{{ old('_restore_slug', '') }}';
    currentEditSlug = '{{ old('_restore_slug', '') }}';
    @endif
  }) @endif"
  x-cloak>

  {{-- ══ En-tête ══ --}}
  <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:22px;">
    <h1 class="page-title">Contacts</h1>
    <button
      @click="openCreate()"
      style="display:inline-flex; align-items:center; gap:6px; background:#D97706; color:#fff; border:none; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:500; cursor:pointer; font-family:'Inter',sans-serif; transition:background 0.15s;"
      onmouseover="this.style.background='#b45309'"
      onmouseout="this.style.background='#D97706'">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
      </svg>
      Nouveau contact
    </button>
  </div>

  {{-- ══ Barre de recherche ══ --}}
  <div style="margin-bottom:4px;">
    @include('partials.search-bar', [
      'placeholder' => 'Rechercher un contact...',
      'xModel'      => 'searchTerm',
      'withFilters' => false,
    ])
  </div>

  {{-- ══ Contenu ══ --}}
  @if($acteurs->isEmpty())
    <div style="text-align:center; padding:60px 0; color:#6B7280; font-size:13px;">
      <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 12px; display:block;">
        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
        <circle cx="9" cy="7" r="4"/>
        <path d="M23 21v-2a4 4 0 00-3-3.87"/>
        <path d="M16 3.13a4 4 0 010 7.75"/>
      </svg>
      Aucun contact trouvé.<br>
      <span style="font-size:12px; color:#9CA3AF;">Créez votre premier contact pour commencer.</span>
    </div>
  @else

    {{-- ══ Tableau ══ --}}
    <div class="table-wrapper">
      <table class="contacts-table">
        <thead>
          <tr>
            <th>Nom</th>
            <th>Adresse</th>
            <th>Numéro</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($acteurs as $acteur)
            @php
              $avatarClass = 'av-' . ($loop->index % 5 + 1);
              $words = preg_split('/\s+/', trim($acteur->nom));
              $initials = implode('', array_map(
                fn($w) => mb_strtoupper(mb_substr($w, 0, 1)),
                array_slice($words, 0, 2)
              ));
            @endphp
            <tr x-show="!searchTerm || '{{ strtolower($acteur->nom) }}'.includes(searchTerm.toLowerCase())">

              {{-- Nom + avatar --}}
              <td>
                <div class="contact-name-cell">
                  <div class="contact-avatar {{ $avatarClass }}">{{ $initials }}</div>
                  <span class="contact-name">{{ $acteur->nom }}</span>
                </div>
              </td>

              {{-- Adresse --}}
              <td style="color:{{ $acteur->adresse ? '#374151' : '#9CA3AF' }};">
                {{ $acteur->adresse ?: '—' }}
              </td>

              {{-- Numéro --}}
              <td style="color:{{ $acteur->numero ? '#374151' : '#9CA3AF' }}; font-variant-numeric:tabular-nums;">
                {{ $acteur->numero ?: '—' }}
              </td>

              {{-- Actions --}}
              <td>
                <div class="actions-cell">
                  <button
                    class="contact-action-btn"
                    title="Modifier"
                    @click="openEdit(
                      '{{ $acteur->slug }}',
                      {{ json_encode($acteur->nom) }},
                      {{ json_encode($acteur->adresse ?? '') }},
                      {{ json_encode($acteur->numero ?? '') }}
                    )">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                      <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                  </button>
                  <button
                    class="contact-action-btn danger"
                    title="Supprimer"
                    @click="$dispatch('open-delete-modal', {
                      url: '{{ route('acteurs.destroy', $acteur) }}',
                      name: {{ json_encode($acteur->nom) }}
                    })">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="3 6 5 6 21 6"/>
                      <path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                    </svg>
                  </button>
                </div>
              </td>

            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- ══ Pagination ══ --}}
    @include('partials.pagination', [
      'paginator'   => $acteurs,
      'entityLabel' => 'contacts',
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
      style="max-width:480px; max-height:90vh; overflow-y:auto;">

      {{-- En-tête --}}
      <div style="display:flex; align-items:center; justify-content:space-between; padding:18px 24px; border-bottom:0.5px solid rgba(0,0,0,0.08);">
        <div>
          <h3 style="font-size:15px; font-weight:500; color:#171717;"
              x-text="formMode === 'create' ? 'Nouveau contact' : 'Modifier le contact'"></h3>
          <p style="font-size:12px; color:#6B7280; margin-top:2px;"
             x-text="formMode === 'create' ? 'Renseignez les informations du contact.' : 'Mettez à jour les informations du contact.'"></p>
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
          <label style="font-size:12px; font-weight:500; color:#374151;">Nom complet *</label>
          <input
            type="text"
            name="nom"
            x-model="formNom"
            placeholder="Ex : Amadou Diallo"
            required
            class="form-input">
        </div>

        {{-- Adresse --}}
        <div style="display:flex; flex-direction:column; gap:5px;">
          <label style="font-size:12px; font-weight:500; color:#374151;">
            Adresse <span style="color:#9CA3AF; font-weight:400;">(optionnel)</span>
          </label>
          <input
            type="text"
            name="adresse"
            x-model="formAdresse"
            placeholder="Ex : Mermoz, Dakar"
            class="form-input">
        </div>

        {{-- Numéro --}}
        <div style="display:flex; flex-direction:column; gap:5px;">
          <label style="font-size:12px; font-weight:500; color:#374151;">
            Numéro de téléphone <span style="color:#9CA3AF; font-weight:400;">(optionnel)</span>
          </label>
          <input
            type="tel"
            name="numero"
            x-model="formNumero"
            placeholder="Ex : 77 123 45 67"
            class="form-input">
          <span style="font-size:11px; color:#9CA3AF;">Format sénégalais recommandé</span>
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
            x-text="formMode === 'create' ? 'Créer le contact' : 'Enregistrer'">
          </button>
        </div>
      </form>

    </div>
  </div>

  {{-- ══ Modale suppression ══ --}}
  @include('partials.delete-modal', ['entityLabel' => 'le contact'])

</div>

@endsection

@section('scripts')
<script>
function contactsPage() {
  return {
    searchTerm: '',
    formOpen: false,
    formMode: 'create',
    formAction: '',
    formNom: '',
    formAdresse: '',
    formNumero: '',
    currentEditSlug: '',

    openCreate() {
      this.formMode = 'create';
      this.formAction = '{{ route('acteurs.store') }}';
      this.formNom = '';
      this.formAdresse = '';
      this.formNumero = '';
      this.currentEditSlug = '';
      this.formOpen = true;
    },

    openEdit(slug, nom, adresse, numero) {
      this.formMode = 'edit';
      this.formAction = '/acteurs/' + slug;
      this.formNom = nom;
      this.formAdresse = adresse || '';
      this.formNumero = numero || '';
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

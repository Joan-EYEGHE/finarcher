{{--
  Partial : modale de formulaire Alpine.js — création / édition

  Variables attendues :
    $formTitle    (string)  — ex: 'Nouveau contact', 'Modifier le contact'
    $formAction   (string)  — URL de l'action du formulaire
    $formMethod   (string)  — 'POST' ou 'PUT' (défaut: 'POST')
    $submitLabel  (string)  — texte du bouton, ex: 'Enregistrer' (défaut: 'Enregistrer')
    $formFields   (string)  — HTML brut des champs spécifiques ({!! $formFields !!})

  Utilisation depuis une page :
    @php
      $formFields = '
        <div>
          <label class="form-label">Nom</label>
          <input name="nom" class="form-input" value="' . old('nom') . '" required>
        </div>
      ';
    @endphp
    @include('partials.form-modal', [
        'formTitle'   => 'Nouveau contact',
        'formAction'  => route('acteurs.store'),
        'formMethod'  => 'POST',
        'submitLabel' => 'Enregistrer',
        'formFields'  => $formFields,
    ])

  Déclenchement depuis un bouton :
    <button @click="$dispatch('open-form-modal')">+ Nouveau contact</button>

  Pour l'édition (pré-remplir depuis le serveur) :
    <button @click="$dispatch('open-form-modal')">Modifier</button>
    (Préférer un formulaire inline ou une page dédiée pour les cas complexes)
--}}

@php
  $formMethod  = $formMethod  ?? 'POST';
  $submitLabel = $submitLabel ?? 'Enregistrer';
@endphp

<div
  x-data="{ open: false }"
  x-on:open-form-modal.window="open = true"
  x-on:close-form-modal.window="open = false"
  x-cloak>

  {{-- Backdrop --}}
  <div
    x-show="open"
    x-transition:enter="transition ease-out duration-150"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="background: rgba(0,0,0,0.40);"
    @click="open = false">

    {{-- Boîte modale --}}
    <div
      @click.stop
      x-transition:enter="transition ease-out duration-150"
      x-transition:enter-start="opacity-0 scale-95"
      x-transition:enter-end="opacity-100 scale-100"
      x-transition:leave="transition ease-in duration-100"
      x-transition:leave-start="opacity-100 scale-100"
      x-transition:leave-end="opacity-0 scale-95"
      class="bg-white rounded-xl shadow-xl w-full"
      style="max-width: 480px; max-height: 90vh; overflow-y: auto;">

      {{-- En-tête --}}
      <div style="display:flex; align-items:center; justify-content:space-between; padding:18px 24px; border-bottom:0.5px solid rgba(0,0,0,0.08);">
        <h3 style="font-size:15px; font-weight:500; color:#171717;">{{ $formTitle }}</h3>
        <button
          type="button"
          @click="open = false"
          style="width:28px; height:28px; display:flex; align-items:center; justify-content:center; border:none; background:none; cursor:pointer; border-radius:6px; color:#6B7280;"
          onmouseover="this.style.background='rgba(0,0,0,0.05)'"
          onmouseout="this.style.background='none'">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
               stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"/>
            <line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      </div>

      {{-- Corps : formulaire --}}
      <form
        action="{{ $formAction }}"
        method="POST"
        style="padding:20px 24px; display:flex; flex-direction:column; gap:16px;">
        @csrf
        @if(strtoupper($formMethod) !== 'POST')
          @method($formMethod)
        @endif

        {{-- Champs spécifiques injectés par la page parente --}}
        {!! $formFields !!}

        {{-- Erreurs de validation globales --}}
        @if($errors->any())
          <div style="background:#FEF2F2; border:0.5px solid #FECACA; border-radius:8px; padding:10px 14px;">
            <ul style="margin:0; padding-left:16px; font-size:12px; color:#DC2626;">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        {{-- Pied de formulaire --}}
        <div style="display:flex; justify-content:flex-end; gap:10px; padding-top:6px;">

          <button
            type="button"
            @click="open = false"
            style="padding:8px 18px; font-size:13px; font-family:'Inter',sans-serif; border:0.5px solid rgba(0,0,0,0.10); border-radius:8px; color:#6B7280; background:transparent; cursor:pointer;"
            onmouseover="this.style.background='rgba(0,0,0,0.03)'"
            onmouseout="this.style.background='transparent'">
            Annuler
          </button>

          <button
            type="submit"
            style="padding:8px 18px; font-size:13px; font-weight:500; font-family:'Inter',sans-serif; border:none; border-radius:8px; background:#D97706; color:#fff; cursor:pointer;"
            onmouseover="this.style.opacity='0.9'"
            onmouseout="this.style.opacity='1'">
            {{ $submitLabel }}
          </button>

        </div>
      </form>

    </div>
  </div>
</div>
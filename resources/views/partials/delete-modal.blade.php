{{--
  Partial : modale de suppression Alpine.js — confirmation nominative

  Variables attendues :
    $entityLabel  (string)  — article + nom de l'entité, ex: 'le contact', 'la catégorie', 'le compte'

  Utilisation depuis une page :
    @include('partials.delete-modal', ['entityLabel' => 'le contact'])

  Déclenchement depuis un bouton de liste :
    <button @click="$dispatch('open-delete-modal', {
        url: '{{ route('acteurs.destroy', $acteur->slug) }}',
        name: '{{ $acteur->nom }}'
    })">Supprimer</button>

  La page parente doit avoir Alpine.js chargé (déjà dans app.blade.php).
--}}

<div
  x-data="{ open: false, deleteUrl: '', itemName: '' }"
  @open-delete-modal.window="open = true; deleteUrl = $event.detail.url; itemName = $event.detail.name"
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
      style="max-width: 400px; padding: 24px;">

      {{-- Icône d'alerte --}}
      <div style="width:44px; height:44px; border-radius:50%; background:#FEE2E2; display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
             stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 6h18M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
        </svg>
      </div>

      {{-- Titre --}}
      <h3 style="font-size:15px; font-weight:500; color:#171717; margin-bottom:8px;">
        Confirmer la suppression
      </h3>

      {{-- Message nominatif --}}
      <p style="font-size:13px; color:#6B7280; line-height:1.6; margin-bottom:24px;">
        Supprimer {{ $entityLabel }}
        <strong style="color:#171717;" x-text="itemName"></strong>
        ? Cette action est irréversible.
      </p>

      {{-- Actions --}}
      <div style="display:flex; justify-content:flex-end; gap:10px;">

        {{-- Annuler --}}
        <button
          type="button"
          @click="open = false"
          style="padding:8px 18px; font-size:13px; font-family:'Inter',sans-serif; border:0.5px solid rgba(0,0,0,0.10); border-radius:8px; color:#6B7280; background:transparent; cursor:pointer;"
          onmouseover="this.style.background='rgba(0,0,0,0.03)'"
          onmouseout="this.style.background='transparent'">
          Annuler
        </button>

        {{-- Formulaire DELETE --}}
        <form :action="deleteUrl" method="POST" style="margin:0;">
          @csrf
          @method('DELETE')
          <button
            type="submit"
            style="padding:8px 18px; font-size:13px; font-weight:500; font-family:'Inter',sans-serif; border:none; border-radius:8px; background:#DC2626; color:#fff; cursor:pointer;"
            onmouseover="this.style.opacity='0.9'"
            onmouseout="this.style.opacity='1'">
            Supprimer
          </button>
        </form>

      </div>
    </div>
  </div>
</div>
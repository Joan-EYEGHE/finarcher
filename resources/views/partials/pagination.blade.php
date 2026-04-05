{{--
  Partial : pagination Laravel stylisée

  Variables attendues :
    $paginator    (LengthAwarePaginator)  — résultat d'un ->paginate()
    $entityLabel  (string)               — ex: 'contacts', 'revenus', 'catégories'

  Utilisation :
    @include('partials.pagination', [
        'paginator'   => $contacts,
        'entityLabel' => 'contacts',
    ])

  Note : conserve les autres paramètres GET (search, per_page, filtres, etc.)
--}}

@if($paginator->total() > 0)
<div style="display:flex; align-items:center; justify-content:space-between; padding:12px 0; border-top:0.5px solid rgba(0,0,0,0.06); font-size:12px; color:#6B7280;">

  {{-- ═══ GAUCHE : info + sélecteur lignes par page ═══ --}}
  <div style="display:flex; align-items:center; gap:16px;">

    {{-- Affichage X–Y sur Z --}}
    <span>
      Affichage
      <strong style="color:#171717; font-weight:500;">{{ $paginator->firstItem() }}</strong>–<strong style="color:#171717; font-weight:500;">{{ $paginator->lastItem() }}</strong>
      sur
      <strong style="color:#171717; font-weight:500;">{{ $paginator->total() }}</strong>
      {{ $entityLabel }}
    </span>

    {{-- Sélecteur lignes par page --}}
    <span style="display:flex; align-items:center; gap:6px;">
      Lignes par page
      <select
        onchange="
          var url = new URL(window.location.href);
          url.searchParams.set('per_page', this.value);
          url.searchParams.set('page', 1);
          window.location = url.toString();
        "
        style="border:0.5px solid rgba(0,0,0,0.10); border-radius:6px; padding:3px 6px; font-size:12px; font-family:'Inter',sans-serif; color:#171717; background:#fff; outline:none; cursor:pointer;">
        @foreach([10, 25, 50] as $n)
          <option value="{{ $n }}" {{ (int) request('per_page', 10) === $n ? 'selected' : '' }}>{{ $n }}</option>
        @endforeach
      </select>
    </span>

  </div>

  {{-- ═══ DROITE : navigation pages ═══ --}}
  @if($paginator->hasPages())
  <div style="display:flex; align-items:center; gap:6px;">

    {{-- Précédent --}}
    @if($paginator->onFirstPage())
      <span style="padding:4px 12px; color:#C4C7CC; user-select:none;">← Précédent</span>
    @else
      <a href="{{ $paginator->previousPageUrl() }}"
         style="padding:4px 12px; color:#6B7280; text-decoration:none;"
         onmouseover="this.style.color='#171717'"
         onmouseout="this.style.color='#6B7280'">← Précédent</a>
    @endif

    {{-- Numéros de pages --}}
    @php
      $current = $paginator->currentPage();
      $last    = $paginator->lastPage();
      // Affiche max 5 pages autour de la page courante
      $start = max(1, $current - 2);
      $end   = min($last, $current + 2);
    @endphp

    @if($start > 1)
      <a href="{{ $paginator->url(1) }}"
         style="width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; text-decoration:none; color:#6B7280;"
         onmouseover="this.style.background='#F3F4F6'"
         onmouseout="this.style.background='transparent'">1</a>
      @if($start > 2)
        <span style="color:#C4C7CC; padding:0 2px;">…</span>
      @endif
    @endif

    @for($page = $start; $page <= $end; $page++)
      @if($page === $current)
        <span style="width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; background:#1A1D23; color:#fff; font-weight:500;">
          {{ $page }}
        </span>
      @else
        <a href="{{ $paginator->url($page) }}"
           style="width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; text-decoration:none; color:#6B7280;"
           onmouseover="this.style.background='#F3F4F6'"
           onmouseout="this.style.background='transparent'">{{ $page }}</a>
      @endif
    @endfor

    @if($end < $last)
      @if($end < $last - 1)
        <span style="color:#C4C7CC; padding:0 2px;">…</span>
      @endif
      <a href="{{ $paginator->url($last) }}"
         style="width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; text-decoration:none; color:#6B7280;"
         onmouseover="this.style.background='#F3F4F6'"
         onmouseout="this.style.background='transparent'">{{ $last }}</a>
    @endif

    {{-- Compteur page / total --}}
    <span style="color:#C4C7CC; padding:0 4px;">/ {{ $last }}</span>

    {{-- Suivant --}}
    @if($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}"
         style="padding:4px 12px; color:#6B7280; text-decoration:none;"
         onmouseover="this.style.color='#171717'"
         onmouseout="this.style.color='#6B7280'">Suivant →</a>
    @else
      <span style="padding:4px 12px; color:#C4C7CC; user-select:none;">Suivant →</span>
    @endif

  </div>
  @endif

</div>
@endif
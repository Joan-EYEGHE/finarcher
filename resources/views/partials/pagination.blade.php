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

  Note : conserve les autres paramètres GET (search, filtres, etc.)
--}}

@if($paginator->total() > 0)
<div style="display:flex; align-items:center; justify-content:space-between; padding:14px 0; border-top:0.5px solid rgba(0,0,0,0.06); font-size:12px; color:#6B7280;">

  {{-- ═══ GAUCHE : info ═══ --}}
  <span>
    Affichage
    <strong style="color:#171717; font-weight:500;">{{ $paginator->firstItem() }}</strong>–<strong style="color:#171717; font-weight:500;">{{ $paginator->lastItem() }}</strong>
    sur
    <strong style="color:#171717; font-weight:500;">{{ $paginator->total() }}</strong>
    {{ $entityLabel }}
  </span>

  {{-- ═══ DROITE : navigation pages ═══ --}}
  @if($paginator->hasPages())
  <div style="display:flex; align-items:center; gap:4px;">

    {{-- Précédent --}}
    @if($paginator->onFirstPage())
      <span style="width:32px; height:32px; border:0.5px solid rgba(0,0,0,0.12); border-radius:6px; background:#fff; display:flex; align-items:center; justify-content:center; color:#C4C7CC; cursor:default;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
      </span>
    @else
      <a href="{{ $paginator->previousPageUrl() }}"
         style="width:32px; height:32px; border:0.5px solid rgba(0,0,0,0.12); border-radius:6px; background:#fff; display:flex; align-items:center; justify-content:center; color:#6B7280; text-decoration:none;"
         onmouseover="this.style.color='#171717'" onmouseout="this.style.color='#6B7280'">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
      </a>
    @endif

    {{-- Numéros de pages --}}
    @php
      $current = $paginator->currentPage();
      $last    = $paginator->lastPage();
      $start   = max(1, $current - 2);
      $end     = min($last, $current + 2);
    @endphp

    @if($start > 1)
      <a href="{{ $paginator->url(1) }}"
         style="width:32px; height:32px; border:0.5px solid rgba(0,0,0,0.12); border-radius:6px; display:flex; align-items:center; justify-content:center; text-decoration:none; color:#6B7280;"
         onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='transparent'">1</a>
      @if($start > 2)
        <span style="color:#C4C7CC; padding:0 4px;">…</span>
      @endif
    @endif

    @for($page = $start; $page <= $end; $page++)
      @if($page === $current)
        <span style="width:32px; height:32px; border-radius:6px; display:flex; align-items:center; justify-content:center; background:#1A1D23; color:#fff; font-weight:500;">
          {{ $page }}
        </span>
      @else
        <a href="{{ $paginator->url($page) }}"
           style="width:32px; height:32px; border:0.5px solid rgba(0,0,0,0.12); border-radius:6px; display:flex; align-items:center; justify-content:center; text-decoration:none; color:#6B7280;"
           onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='transparent'">{{ $page }}</a>
      @endif
    @endfor

    @if($end < $last)
      @if($end < $last - 1)
        <span style="color:#C4C7CC; padding:0 4px;">…</span>
      @endif
      <a href="{{ $paginator->url($last) }}"
         style="width:32px; height:32px; border:0.5px solid rgba(0,0,0,0.12); border-radius:6px; display:flex; align-items:center; justify-content:center; text-decoration:none; color:#6B7280;"
         onmouseover="this.style.background='#F3F4F6'" onmouseout="this.style.background='transparent'">{{ $last }}</a>
    @endif

    {{-- Suivant --}}
    @if($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}"
         style="width:32px; height:32px; border:0.5px solid rgba(0,0,0,0.12); border-radius:6px; background:#fff; display:flex; align-items:center; justify-content:center; color:#6B7280; text-decoration:none;"
         onmouseover="this.style.color='#171717'" onmouseout="this.style.color='#6B7280'">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
      </a>
    @else
      <span style="width:32px; height:32px; border:0.5px solid rgba(0,0,0,0.12); border-radius:6px; background:#fff; display:flex; align-items:center; justify-content:center; color:#C4C7CC; cursor:default;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
      </span>
    @endif

  </div>
  @endif

</div>
@endif

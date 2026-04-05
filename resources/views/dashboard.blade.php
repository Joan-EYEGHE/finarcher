@extends('layouts.app')

@section('title', 'Dashboard')

@section('head')
<style>
  /* ── Metric cards ── */
  .metric-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 20px;
  }

  .metric-card {
    padding: 18px 20px;
    border-radius: 12px;
    background: #fff;
    border: 0.5px solid var(--border-color);
    position: relative;
    overflow: hidden;
  }

  .metric-card.dark {
    background: var(--card-dark);
    border: none;
  }

  .metric-card.dark::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='24' height='24' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 4l5 5-5 5' stroke='%23ffffff' stroke-width='1.5' fill='none' opacity='0.04'/%3E%3C/svg%3E");
    background-size: 28px 28px;
    pointer-events: none;
  }

  .metric-label {
    font-size: 12px;
    font-weight: 400;
    color: var(--text-secondary);
    margin-bottom: 8px;
  }

  .metric-card.dark .metric-label { color: rgba(255,255,255,0.55); }

  .metric-value {
    font-size: 26px;
    font-weight: 500;
    font-variant-numeric: tabular-nums;
    color: var(--text-primary);
    margin-bottom: 6px;
  }

  .metric-card.dark .metric-value { color: #fff; }

  .metric-trend {
    font-size: 11px;
    font-weight: 400;
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .metric-trend.up   { color: var(--success); }
  .metric-trend.down { color: var(--danger); }
  .metric-card.dark .metric-trend.up { color: #6EE7B7; }

  /* ── Grid 2 colonnes ── */
  .grid-2 {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    gap: 14px;
    margin-bottom: 20px;
  }

  /* ── Chart tabs ── */
  .chart-tabs {
    display: flex;
    gap: 6px;
    margin-bottom: 14px;
  }

  .chart-tab {
    padding: 5px 14px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 500;
    font-family: 'Inter', sans-serif;
    cursor: pointer;
    border: 0.5px solid var(--border-color);
    background: transparent;
    color: var(--text-secondary);
    transition: all 0.15s;
  }

  .chart-tab.active {
    background: var(--card-dark);
    color: #fff;
    border-color: var(--card-dark);
  }

  .chart-container {
    position: relative;
    height: 200px;
  }

  /* ── Category list ── */
  .category-list {
    display: flex;
    flex-direction: column;
    gap: 14px;
  }

  .category-row { display: flex; flex-direction: column; gap: 5px; }

  .category-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .category-name   { font-size: 12px; font-weight: 400; color: var(--text-primary); }
  .category-amount { font-size: 12px; font-weight: 500; font-variant-numeric: tabular-nums; color: var(--text-primary); }

  /* ── Transactions table ── */
  .transactions-table { width: 100%; border-collapse: collapse; }

  .transactions-table th {
    text-align: left;
    font-size: 11px;
    font-weight: 500;
    color: var(--text-secondary);
    padding: 0 0 10px 0;
    text-transform: uppercase;
    letter-spacing: 0.3px;
  }

  .transactions-table th:last-child { text-align: right; }

  .transactions-table td {
    padding: 11px 0;
    border-top: 0.5px solid var(--border-color);
    font-size: 13px;
    vertical-align: middle;
  }

  .transactions-table td:last-child {
    text-align: right;
    font-weight: 500;
    font-variant-numeric: tabular-nums;
  }

  .td-date { color: var(--text-secondary); font-size: 12px; width: 90px; }
  .td-desc { font-weight: 400; color: var(--text-primary); }

  /* ── Date filter inputs ── */
  .date-input {
    padding: 7px 12px;
    border: 0.5px solid var(--border-color);
    border-radius: 8px;
    font-size: 12px;
    font-family: 'Inter', sans-serif;
    color: var(--text-primary);
    background: #fff;
    outline: none;
  }

  .date-input:focus { border-color: var(--accent); }
</style>
@endsection

@section('content')

{{-- ── En-tête + filtre période ── --}}
<div class="page-header">
  <h1 class="page-title">Dashboard</h1>
  <form method="GET" action="{{ route('dashboard') }}" style="display:flex;align-items:center;gap:10px;">
    <input type="date" name="date_debut" class="date-input" value="{{ $dateDebut }}">
    <span style="color:var(--text-secondary);font-size:12px;">—</span>
    <input type="date" name="date_fin" class="date-input" value="{{ $dateFin }}">
    <button type="submit" class="btn-primary" style="padding:7px 16px;font-size:12px;">Filtrer</button>
  </form>
</div>

{{-- ── 3 cartes KPI ── --}}
<div class="metric-cards">

  {{-- Solde total (carte sombre) --}}
  <div class="metric-card dark">
    <div class="metric-label">Solde total</div>
    <div class="metric-value">{{ number_format($soldeTotal, 0, ',', ' ') }} FCFA</div>
    <div class="metric-trend up">
      <span class="trend-arrow up"></span>
      Tous les comptes
    </div>
  </div>

  {{-- Revenus de la période --}}
  <div class="metric-card">
    <div class="metric-label">Revenus de la période</div>
    <div class="metric-value" style="color:var(--success)">
      {{ number_format($revenusTotal, 0, ',', ' ') }} FCFA
    </div>
    <div class="metric-trend up">
      <span class="trend-arrow up"></span>
      Du {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }}
      au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
    </div>
  </div>

  {{-- Dépenses de la période --}}
  <div class="metric-card">
    <div class="metric-label">Dépenses de la période</div>
    <div class="metric-value" style="color:var(--danger)">
      {{ number_format($depensesTotal, 0, ',', ' ') }} FCFA
    </div>
    <div class="metric-trend down">
      <span class="trend-arrow down"></span>
      Du {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }}
      au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
    </div>
  </div>

</div>

{{-- ── Graphique + Catégories ── --}}
<div class="grid-2">

  {{-- Graphique revenus vs dépenses --}}
  <div class="card">
    <div class="card-header">
      <span class="card-title">Revenus vs Dépenses</span>
      <div class="chart-tabs">
        <button class="chart-tab active">6 mois</button>
      </div>
    </div>
    <div class="chart-container">
      <canvas id="revDepChart"></canvas>
    </div>
  </div>

  {{-- Répartition par catégories --}}
  <div class="card">
    <div class="card-header">
      <span class="card-title">Répartition par catégorie</span>
      <a href="{{ route('depenses.index') }}" class="card-link">Voir tout</a>
    </div>

    @if($depensesParCategorie->isEmpty())
      <p style="font-size:12px;color:var(--text-secondary);padding-top:8px;">Aucune dépense sur la période.</p>
    @else
      <div class="category-list">
        @php
          $totalDepenses = $depensesParCategorie->sum('montant');
          $barColorMap = [
            'alimentation' => 'bar-alimentation',
            'transport'    => 'bar-transport',
            'services'     => 'bar-services',
            'sante'        => 'bar-sante',
            'logement'     => 'bar-logement',
            'divers'       => 'bar-divers',
          ];
        @endphp

        @foreach($depensesParCategorie as $cat)
          @php
            $pct = $totalDepenses > 0 ? round($cat['montant'] / $totalDepenses * 100) : 0;
            $slug = \Illuminate\Support\Str::slug($cat['nom']);
            $barClass = $barColorMap[$slug] ?? 'bar-divers';
          @endphp
          <div class="category-row">
            <div class="category-info">
              <span class="category-name">{{ $cat['nom'] }}</span>
              <span class="category-amount">{{ number_format($cat['montant'], 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="category-bar-track">
              <div class="category-bar-fill {{ $barClass }}" style="width:{{ $pct }}%"></div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>

</div>

{{-- ── 5 dernières opérations ── --}}
<div class="card">
  <div class="card-header">
    <span class="card-title">5 dernières opérations</span>
    <div style="display:flex;gap:12px;">
      <a href="{{ route('revenus.index') }}" class="card-link">Revenus</a>
      <a href="{{ route('depenses.index') }}" class="card-link">Dépenses</a>
    </div>
  </div>

  @if($dernieresOps->isEmpty())
    <p style="font-size:12px;color:var(--text-secondary);padding-top:8px;">Aucune opération sur la période.</p>
  @else
    @php
      $badgeMap = [
        'alimentation' => 'badge-alimentation',
        'transport'    => 'badge-transport',
        'services'     => 'badge-services',
        'sante'        => 'badge-sante',
        'logement'     => 'badge-logement',
        'divers'       => 'badge-divers',
      ];
    @endphp

    <table class="transactions-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Description</th>
          <th>Catégorie</th>
          <th>Montant</th>
        </tr>
      </thead>
      <tbody>
        @foreach($dernieresOps as $op)
          @if($op instanceof \App\Models\Revenu)
            <tr>
              <td class="td-date">{{ $op->date_operation->format('d/m/Y') }}</td>
              <td class="td-desc">{{ $op->motif }}</td>
              <td><span class="badge badge-revenu">Revenu</span></td>
              <td class="amount-positive">+{{ number_format($op->montant, 0, ',', ' ') }} FCFA</td>
            </tr>
          @else
            @php
              $catSlug = $op->categorie ? \Illuminate\Support\Str::slug($op->categorie->nom) : 'divers';
              $badgeClass = $badgeMap[$catSlug] ?? 'badge-divers';
              $catNom = $op->categorie ? $op->categorie->nom : 'Divers';
            @endphp
            <tr>
              <td class="td-date">{{ $op->date_operation->format('d/m/Y') }}</td>
              <td class="td-desc">{{ $op->designation }}</td>
              <td><span class="badge {{ $badgeClass }}">{{ $catNom }}</span></td>
              <td class="amount-negative">-{{ number_format($op->montant_total, 0, ',', ' ') }} FCFA</td>
            </tr>
          @endif
        @endforeach
      </tbody>
    </table>
  @endif
</div>

@endsection

@section('scripts')
<script>
const ctx = document.getElementById('revDepChart').getContext('2d');

new Chart(ctx, {
  type: 'bar',
  data: {
    labels: {!! json_encode($chartLabels) !!},
    datasets: [
      {
        label: 'Revenus',
        data: {!! json_encode($chartRevenus) !!},
        backgroundColor: '#059669',
        borderRadius: 4,
        barPercentage: 0.4,
        categoryPercentage: 0.65
      },
      {
        label: 'Dépenses',
        data: {!! json_encode($chartDepenses) !!},
        backgroundColor: '#DC2626',
        borderRadius: 4,
        barPercentage: 0.4,
        categoryPercentage: 0.65
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'bottom',
        labels: {
          usePointStyle: true,
          pointStyle: 'circle',
          boxWidth: 6,
          padding: 16,
          font: { family: 'Inter', size: 11 }
        }
      },
      tooltip: {
        backgroundColor: '#1A1D23',
        titleFont: { family: 'Inter', size: 11 },
        bodyFont: { family: 'Inter', size: 11 },
        padding: 10,
        cornerRadius: 8,
        callbacks: {
          label: function(context) {
            return context.dataset.label + ' : ' +
              context.parsed.y.toLocaleString('fr-FR') + ' FCFA';
          }
        }
      }
    },
    scales: {
      x: {
        grid: { display: false },
        ticks: { font: { family: 'Inter', size: 11 }, color: '#6B7280' },
        border: { display: false }
      },
      y: {
        grid: { color: 'rgba(0,0,0,0.04)' },
        ticks: {
          font: { family: 'Inter', size: 10 },
          color: '#6B7280',
          callback: function(value) { return (value / 1000) + 'k'; },
          maxTicksLimit: 5
        },
        border: { display: false },
        beginAtZero: true
      }
    }
  }
});
</script>
@endsection

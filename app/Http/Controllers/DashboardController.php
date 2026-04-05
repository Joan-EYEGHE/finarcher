<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $dateDebut = $request->input('date_debut', now()->startOfMonth()->toDateString());
        $dateFin   = $request->input('date_fin',   now()->toDateString());

        // ── KPIs ────────────────────────────────────────────────────────────
        $soldeTotal    = Auth::user()->portefeuilles()->sum('solde');

        $revenusTotal  = Auth::user()->revenus()
            ->whereBetween('date_operation', [$dateDebut, $dateFin])
            ->sum('montant');

        $depensesTotal = Auth::user()->depenses()
            ->whereBetween('date_operation', [$dateDebut, $dateFin])
            ->sum('montant_total');

        // ── Graphique — 6 derniers mois ──────────────────────────────────────
        $chartLabels   = [];
        $chartRevenus  = [];
        $chartDepenses = [];

        for ($i = 5; $i >= 0; $i--) {
            $mois = Carbon::now()->subMonths($i);

            $chartLabels[] = ucfirst($mois->locale('fr')->isoFormat('MMM'));

            $chartRevenus[] = (float) Auth::user()->revenus()
                ->whereYear('date_operation',  $mois->year)
                ->whereMonth('date_operation', $mois->month)
                ->sum('montant');

            $chartDepenses[] = (float) Auth::user()->depenses()
                ->whereYear('date_operation',  $mois->year)
                ->whereMonth('date_operation', $mois->month)
                ->sum('montant_total');
        }

        // ── Répartition par catégories ───────────────────────────────────────
        $depensesParCategorie = Auth::user()->depenses()
            ->whereBetween('date_operation', [$dateDebut, $dateFin])
            ->with('categorie')
            ->get()
            ->filter(fn ($d) => $d->categorie !== null)
            ->groupBy('categorie_id')
            ->map(fn ($items) => [
                'nom'     => $items->first()->categorie->nom,
                'montant' => (float) $items->sum('montant_total'),
            ])
            ->sortByDesc('montant')
            ->values();

        // ── 5 dernières opérations ───────────────────────────────────────────
        $revenus  = Auth::user()->revenus()
            ->with('acteur')
            ->whereBetween('date_operation', [$dateDebut, $dateFin])
            ->get();

        $depenses = Auth::user()->depenses()
            ->with('categorie')
            ->whereBetween('date_operation', [$dateDebut, $dateFin])
            ->get();

        $dernieresOps = $revenus->concat($depenses)
            ->sortByDesc('date_operation')
            ->take(5);

        return view('dashboard', compact(
            'dateDebut',
            'dateFin',
            'soldeTotal',
            'revenusTotal',
            'depensesTotal',
            'depensesParCategorie',
            'dernieresOps',
            'chartLabels',
            'chartRevenus',
            'chartDepenses'
        ));
    }
}

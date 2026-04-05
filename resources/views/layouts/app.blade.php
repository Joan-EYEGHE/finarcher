<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FinArcher — @yield('title', 'Tableau de bord')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

  <style>
    :root {
      --primary-sidebar: #065F46;
      --accent: #D97706;
      --success: #059669;
      --danger: #DC2626;
      --card-dark: #1A1D23;
      --bg-page: #F8F8F7;
      --text-primary: #171717;
      --text-secondary: #6B7280;
      --border-color: rgba(0,0,0,0.08);

      --badge-revenu-bg: #E1F5EE;
      --badge-revenu-text: #085041;
      --badge-alimentation-bg: #FAEEDA;
      --badge-alimentation-text: #633806;
      --badge-transport-bg: #E6F1FB;
      --badge-transport-text: #0C447C;
      --badge-services-bg: #EEEDFE;
      --badge-services-text: #3C3489;
      --badge-sante-bg: #FEE2E2;
      --badge-sante-text: #991B1B;
      --badge-logement-bg: #E0F2FE;
      --badge-logement-text: #075985;
      --badge-divers-bg: #F1EFE8;
      --badge-divers-text: #444441;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Inter', -apple-system, sans-serif;
      background: var(--bg-page);
      color: var(--text-primary);
      font-size: 13px;
      font-weight: 400;
    }

    [x-cloak] { display: none !important; }

    /* ═══════════════════ SIDEBAR ═══════════════════ */
    .sidebar {
      width: 210px;
      min-width: 210px;
      background: var(--primary-sidebar);
      display: flex;
      flex-direction: column;
      padding: 20px 12px;
      position: fixed;
      top: 0; left: 0; bottom: 0;
      z-index: 100;
    }

    .sidebar-logo {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 0 8px;
      margin-bottom: 28px;
    }

    .sidebar-logo-icon {
      width: 34px; height: 34px;
      background: rgba(255,255,255,0.15);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .sidebar-logo-icon svg { width: 18px; height: 18px; }

    .sidebar-logo-text {
      font-size: 16px;
      font-weight: 600;
      color: #fff;
      letter-spacing: -0.3px;
    }

    .sidebar-nav {
      display: flex;
      flex-direction: column;
      gap: 2px;
      flex: 1;
    }

    .sidebar-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 9px 12px;
      border-radius: 8px;
      color: rgba(255,255,255,0.7);
      text-decoration: none;
      font-size: 13px;
      font-weight: 400;
      transition: background 0.15s, color 0.15s;
      cursor: pointer;
      border: none;
      background: transparent;
      width: 100%;
      text-align: left;
    }

    .sidebar-item:hover {
      background: rgba(255,255,255,0.08);
      color: #fff;
    }

    .sidebar-item.active {
      background: rgba(255,255,255,0.15);
      color: #fff;
      font-weight: 500;
    }

    .sidebar-item svg {
      width: 16px; height: 16px;
      flex-shrink: 0;
    }

    .sidebar-bottom {
      border-top: 1px solid rgba(255,255,255,0.12);
      padding-top: 14px;
      margin-top: auto;
      display: flex;
      flex-direction: column;
      gap: 2px;
    }

    .sidebar-profile {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 9px 12px;
    }

    .sidebar-avatar {
      width: 30px; height: 30px;
      border-radius: 50%;
      background: rgba(255,255,255,0.15);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 11px;
      font-weight: 500;
      flex-shrink: 0;
      text-transform: uppercase;
    }

    .sidebar-profile-info {
      display: flex;
      flex-direction: column;
      min-width: 0;
    }

    .sidebar-profile-name {
      font-size: 12px;
      color: #fff;
      font-weight: 500;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .sidebar-profile-email {
      font-size: 10px;
      color: rgba(255,255,255,0.5);
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    /* ═══════════════════ MAIN CONTENT ═══════════════════ */
    .main-content {
      margin-left: 210px;
      flex: 1;
      padding: 24px 28px;
      min-height: 100vh;
    }

    /* ═══════════════════ CARDS ═══════════════════ */
    .card {
      background: #fff;
      border: 0.5px solid var(--border-color);
      border-radius: 12px;
      padding: 18px 20px;
    }

    .card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 16px;
    }

    .card-title {
      font-size: 14px;
      font-weight: 500;
      color: var(--text-primary);
    }

    .card-link {
      font-size: 12px;
      color: var(--accent);
      text-decoration: none;
      font-weight: 500;
    }

    .card-link:hover { text-decoration: underline; }

    /* ═══════════════════ BADGES ═══════════════════ */
    .badge {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 500;
    }
    .badge-revenu       { background: var(--badge-revenu-bg);       color: var(--badge-revenu-text); }
    .badge-alimentation { background: var(--badge-alimentation-bg);  color: var(--badge-alimentation-text); }
    .badge-transport    { background: var(--badge-transport-bg);     color: var(--badge-transport-text); }
    .badge-services     { background: var(--badge-services-bg);      color: var(--badge-services-text); }
    .badge-sante        { background: var(--badge-sante-bg);         color: var(--badge-sante-text); }
    .badge-logement     { background: var(--badge-logement-bg);      color: var(--badge-logement-text); }
    .badge-divers       { background: var(--badge-divers-bg);        color: var(--badge-divers-text); }

    /* ═══════════════════ CATEGORY BARS (Dashboard) ═══════════════════ */
    .category-bar-track {
      width: 100%; height: 6px;
      background: #F3F4F6;
      border-radius: 3px;
      overflow: visible;
      position: relative;
    }

    .category-bar-fill {
      height: 6px;
      border-radius: 3px 0 0 3px;
      position: relative;
    }

    /* Pointe de flèche identité FinArcher */
    .category-bar-fill::after {
      content: '';
      position: absolute;
      right: -5px;
      top: 50%;
      transform: translateY(-50%);
      width: 0; height: 0;
      border-top: 5px solid transparent;
      border-bottom: 5px solid transparent;
      border-left: 6px solid currentColor;
    }

    .bar-alimentation { background: #D97706; color: #D97706; }
    .bar-transport    { background: #2563EB; color: #2563EB; }
    .bar-services     { background: #7C3AED; color: #7C3AED; }
    .bar-sante        { background: #DC2626; color: #DC2626; }
    .bar-logement     { background: #0891B2; color: #0891B2; }
    .bar-divers       { background: #6B7280; color: #6B7280; }

    /* ═══════════════════ DARK METRIC CARD (solde) ═══════════════════ */
    .metric-card-dark {
      background: var(--card-dark);
      border-radius: 12px;
      padding: 18px 20px;
      position: relative;
      overflow: hidden;
    }

    .metric-card-dark::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background-image: url("data:image/svg+xml,%3Csvg width='24' height='24' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 4l5 5-5 5' stroke='%23ffffff' stroke-width='1.5' fill='none' opacity='0.04'/%3E%3C/svg%3E");
      background-size: 28px 28px;
      pointer-events: none;
    }

    /* ═══════════════════ TREND ARROWS ═══════════════════ */
    .trend-arrow {
      display: inline-block;
      width: 0; height: 0;
      border-left: 3.5px solid transparent;
      border-right: 3.5px solid transparent;
    }
    .trend-arrow.up   { border-bottom: 5px solid currentColor; }
    .trend-arrow.down { border-top: 5px solid currentColor; }

    /* ═══════════════════ FORM INPUTS ═══════════════════ */
    .form-input {
      width: 100%;
      padding: 10px 14px;
      border: 0.5px solid rgba(0,0,0,0.10);
      border-radius: 8px;
      font-size: 13px;
      font-family: 'Inter', sans-serif;
      color: var(--text-primary);
      background: #fff;
      outline: none;
      transition: border-color 0.15s;
    }

    .form-input::placeholder { color: #C4C7CC; }
    .form-input:focus { border-color: var(--accent); }

    .form-label {
      display: block;
      font-size: 12px;
      font-weight: 500;
      color: var(--text-primary);
      margin-bottom: 5px;
    }

    /* ═══════════════════ BUTTONS ═══════════════════ */
    .btn-primary {
      padding: 8px 16px;
      background: var(--accent);
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 500;
      font-family: 'Inter', sans-serif;
      cursor: pointer;
      transition: opacity 0.15s;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .btn-primary:hover { opacity: 0.9; }

    .btn-secondary {
      padding: 8px 16px;
      background: transparent;
      color: var(--text-secondary);
      border: 0.5px solid var(--border-color);
      border-radius: 8px;
      font-size: 13px;
      font-weight: 400;
      font-family: 'Inter', sans-serif;
      cursor: pointer;
      transition: background 0.15s;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .btn-secondary:hover { background: rgba(0,0,0,0.03); }

    /* ═══════════════════ DATA TABLES ═══════════════════ */
    .data-table {
      width: 100%;
      border-collapse: collapse;
    }

    .data-table th {
      text-align: left;
      font-size: 11px;
      font-weight: 500;
      color: var(--text-secondary);
      padding: 0 0 10px 0;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }

    .data-table td {
      padding: 11px 0;
      border-top: 0.5px solid var(--border-color);
      font-size: 13px;
      vertical-align: middle;
    }

    /* ═══════════════════ AMOUNT COLORS ═══════════════════ */
    .amount-positive { color: var(--success); }
    .amount-negative { color: var(--danger); }

    /* ═══════════════════ PAGE HEADER ═══════════════════ */
    .page-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .page-title {
      font-size: 18px;
      font-weight: 500;
      color: var(--text-primary);
    }
  </style>

  @yield('head')
</head>
<body>

@include('partials.sidebar')

<main class="main-content">
  @yield('content')
</main>

@yield('scripts')
</body>
</html>
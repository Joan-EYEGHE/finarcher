<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FinArcher — @yield('title', 'Connexion')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

  <style>
    :root {
      --primary: #065F46;
      --accent: #D97706;
      --bg-page: #F8F8F7;
      --text-primary: #171717;
      --text-secondary: #6B7280;
      --border: rgba(0,0,0,0.10);
      --danger: #DC2626;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Inter', -apple-system, sans-serif;
      min-height: 100vh;
      display: flex;
      background: var(--bg-page);
      color: var(--text-primary);
      font-size: 13px;
    }

    [x-cloak] { display: none !important; }

    /* ═══════════ PANNEAU GAUCHE (branding) ═══════════ */
    .brand-panel {
      width: 420px;
      min-height: 100vh;
      background: var(--primary);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 48px;
      position: relative;
      overflow: hidden;
      flex-shrink: 0;
    }

    /* Motif chevron filigrane */
    .brand-panel::before {
      content: '';
      position: absolute;
      inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg width='24' height='24' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 4l5 5-5 5' stroke='%23ffffff' stroke-width='1.5' fill='none' opacity='0.04'/%3E%3C/svg%3E");
      background-size: 32px 32px;
      pointer-events: none;
    }

    .brand-logo {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 32px;
      position: relative;
      z-index: 1;
    }

    .brand-logo-icon {
      width: 44px; height: 44px;
      background: rgba(255,255,255,0.15);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .brand-logo-icon svg { width: 22px; height: 22px; }

    .brand-logo-text {
      font-size: 22px;
      font-weight: 600;
      color: #fff;
      letter-spacing: -0.3px;
    }

    .brand-tagline {
      font-size: 14px;
      color: rgba(255,255,255,0.7);
      text-align: center;
      line-height: 1.6;
      position: relative;
      z-index: 1;
      max-width: 280px;
    }

    /* ═══════════ PANNEAU DROIT (formulaire) ═══════════ */
    .form-panel {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 48px;
    }

    .form-container {
      width: 100%;
      max-width: 380px;
    }

    /* Tabs Login / Register */
    .auth-tabs {
      display: flex;
      gap: 6px;
      margin-bottom: 24px;
    }

    .auth-tab {
      padding: 7px 20px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 500;
      font-family: 'Inter', sans-serif;
      cursor: pointer;
      border: 0.5px solid var(--border);
      background: transparent;
      color: var(--text-secondary);
      transition: all 0.15s;
    }

    .auth-tab.active {
      background: #1A1D23;
      color: #fff;
      border-color: #1A1D23;
    }

    .auth-tab:hover:not(.active) {
      background: rgba(0,0,0,0.03);
    }

    /* Champs de formulaire */
    .form-group { margin-bottom: 16px; }

    .form-label {
      display: block;
      font-size: 12px;
      font-weight: 500;
      color: var(--text-primary);
      margin-bottom: 5px;
    }

    .form-label .optional {
      font-weight: 400;
      color: var(--text-secondary);
    }

    .form-input {
      width: 100%;
      padding: 10px 14px;
      border: 0.5px solid var(--border);
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

    .form-input.error { border-color: var(--danger); }

    /* Bouton principal */
    .btn-primary {
      width: 100%;
      padding: 11px;
      background: var(--accent);
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 500;
      font-family: 'Inter', sans-serif;
      cursor: pointer;
      transition: opacity 0.15s;
      margin-top: 4px;
    }
    .btn-primary:hover { opacity: 0.9; }

    /* Lien */
    .form-link {
      font-size: 12px;
      color: var(--accent);
      text-decoration: none;
      font-weight: 500;
      cursor: pointer;
    }
    .form-link:hover { text-decoration: underline; }

    /* Footer texte */
    .form-footer {
      text-align: center;
      margin-top: 20px;
      font-size: 12px;
      color: var(--text-secondary);
    }
    .form-footer a {
      color: var(--accent);
      text-decoration: none;
      font-weight: 500;
      cursor: pointer;
    }
    .form-footer a:hover { text-decoration: underline; }

    /* Titres de formulaire */
    .form-title {
      font-size: 18px;
      font-weight: 500;
      color: var(--text-primary);
      margin-bottom: 4px;
    }

    .form-subtitle {
      font-size: 13px;
      color: var(--text-secondary);
      margin-bottom: 28px;
    }

    /* Erreurs */
    .error-message {
      font-size: 11px;
      color: var(--danger);
      margin-top: 4px;
    }

    /* ═══════════ RESPONSIVE ═══════════ */
    @media (max-width: 860px) {
      .brand-panel { display: none; }
      .form-panel { padding: 32px 24px; }
    }
  </style>

  @yield('head')
</head>
<body>

<!-- ═══════════ PANNEAU GAUCHE ═══════════ -->
<div class="brand-panel">
  <div class="brand-logo">
    <div class="brand-logo-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="5" y1="12" x2="19" y2="12"/>
        <polyline points="13 6 19 12 13 18"/>
      </svg>
    </div>
    <span class="brand-logo-text">FinArcher</span>
  </div>
  <p class="brand-tagline">Suivez vos finances avec précision.<br>Chaque dépense compte, chaque revenu aussi.</p>
</div>

<!-- ═══════════ PANNEAU DROIT ═══════════ -->
<div class="form-panel">
  <div class="form-container">
    @yield('content')
  </div>
</div>

@yield('scripts')
</body>
</html>
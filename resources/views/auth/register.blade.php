@extends('layouts.auth')

@section('title', 'FinArcher — Créer un compte')

@section('content')
<div x-data="{ tab: 'register' }">

  {{-- Tabs --}}
  <div class="auth-tabs">
    <button type="button" class="auth-tab" :class="{ active: tab === 'login' }" @click="tab = 'login'">Connexion</button>
    <button type="button" class="auth-tab" :class="{ active: tab === 'register' }" @click="tab = 'register'">Créer un compte</button>
  </div>

  {{-- ══ FORMULAIRE LOGIN ══ --}}
  <div x-show="tab === 'login'" x-cloak>
    <h1 class="form-title">Bon retour !</h1>
    <p class="form-subtitle">Connectez-vous pour accéder à votre espace.</p>

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email"
               class="form-input"
               value="{{ old('email') }}"
               placeholder="votre@email.com"
               autocomplete="email">
      </div>

      <div class="form-group">
        <label class="form-label">Mot de passe</label>
        <input type="password" name="password"
               class="form-input"
               placeholder="••••••••"
               autocomplete="current-password">
      </div>

      <button type="submit" class="btn-primary">Se connecter</button>
    </form>

    <div class="form-footer">
      Pas encore de compte ? <a @click.prevent="tab = 'register'" href="#">Créer un compte</a>
    </div>

    <div class="form-footer" style="margin-top: 10px;">
      <a href="#" class="form-link">Mot de passe oublié ?</a>
    </div>
  </div>

  {{-- ══ FORMULAIRE REGISTER ══ --}}
  <div x-show="tab === 'register'">
    <h1 class="form-title">Créer un compte</h1>
    <p class="form-subtitle">Rejoignez FinArcher pour gérer vos finances.</p>

    <form method="POST" action="{{ route('register') }}">
      @csrf

      <div class="form-group">
        <label class="form-label">Nom complet</label>
        <input type="text" name="name"
               class="form-input @error('name') error @enderror"
               value="{{ old('name') }}"
               placeholder="Joan Ndione"
               autocomplete="name">
        @error('name')
          <p class="error-message">{{ $message }}</p>
        @enderror
      </div>

      <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email"
               class="form-input @error('email') error @enderror"
               value="{{ old('email') }}"
               placeholder="votre@email.com"
               autocomplete="email">
        @error('email')
          <p class="error-message">{{ $message }}</p>
        @enderror
      </div>

      <div class="form-group">
        <label class="form-label">Numéro de téléphone <span class="optional">(optionnel)</span></label>
        <input type="tel" name="numero"
               class="form-input"
               value="{{ old('numero') }}"
               placeholder="+221 77 000 00 00">
      </div>

      <div class="form-group">
        <label class="form-label">Mot de passe</label>
        <input type="password" name="password"
               class="form-input @error('password') error @enderror"
               placeholder="••••••••"
               autocomplete="new-password">
        @error('password')
          <p class="error-message">{{ $message }}</p>
        @enderror
      </div>

      <div class="form-group">
        <label class="form-label">Confirmer le mot de passe</label>
        <input type="password" name="password_confirmation"
               class="form-input"
               placeholder="••••••••"
               autocomplete="new-password">
      </div>

      <button type="submit" class="btn-primary">Créer mon compte</button>
    </form>

    <div class="form-footer">
      Déjà un compte ? <a @click.prevent="tab = 'login'" href="#">Se connecter</a>
    </div>
  </div>

</div>
@endsection

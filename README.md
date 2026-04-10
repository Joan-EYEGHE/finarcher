<div align="center">

# 🎯 FinArcher

**Pilotez vos finances personnelles avec précision.**

*Suivez vos revenus, dépenses et comptes en un seul endroit — conçu pour l'Afrique de l'Ouest et au-delà.*

![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Blade](https://img.shields.io/badge/Blade-Templating-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-CDN-8BC0D0?style=for-the-badge&logo=alpinedotjs&logoColor=black)
![Vite](https://img.shields.io/badge/Vite-5.x-646CFF?style=for-the-badge&logo=vite&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Status](https://img.shields.io/badge/Statut-En_développement-F39C12?style=for-the-badge)
![License](https://img.shields.io/badge/Licence-MIT-2ECC71?style=for-the-badge)

</div>

---

## Aperçu

**FinArcher** est une application web de gestion des finances personnelles qui vous donne une visibilité claire sur l'origine et la destination de votre argent. Gérez vos comptes (espèces, Wave, Orange Money, banques), catégorisez vos dépenses et suivez vos revenus — le tout depuis un tableau de bord épuré, sans superflu.

<!-- TODO: ajouter screenshot du dashboard ici -->
<p align="center">
  <img src="https://res.cloudinary.com/di3dithde/image/upload/v1775837925/dashboard-temp_l1ms59.png" alt="Dashboard FinArcher" width="800"/>
</p>

---

## Table des matières

- [✨ Fonctionnalités](#-fonctionnalités)
- [🛠️ Stack technique](#️-stack-technique)
- [📁 Structure du projet](#-structure-du-projet)
- [⚙️ Prérequis système](#️-prérequis-système)
- [🚀 Installation pas à pas](#-installation-pas-à-pas)
- [▶️ Lancer le projet](#️-lancer-le-projet)
- [🌐 Variables d'environnement](#-variables-denvironnement)
- [🗃️ Base de données](#️-base-de-données)
- [📸 Captures d'écran](#-captures-décran)
- [👤 Auteur](#-auteur)
- [📜 Licence](#-licence)

---

## ✨ Fonctionnalités

- 🔐 **Authentification sécurisée** — Inscription, connexion avec limitation des tentatives (rate limiting) et déconnexion
- 📊 **Tableau de bord dynamique** — KPIs en temps réel (solde total, revenus/dépenses du mois), graphique barres groupées sur 6 mois, répartition des dépenses par catégorie, historique des 5 dernières opérations
- 💳 **Gestion des comptes** — Suivi multi-comptes (Espèces, Wave, Orange Money, Banques…) avec support multi-devises (XOF, EUR, USD)
- 🏷️ **Gestion des catégories** — Classifiez vos dépenses avec des catégories personnalisées et icônes
- 👥 **Gestion des contacts** — Répertoire des sources de revenus et bénéficiaires (Acteurs)
- 💰 **Suivi des revenus** — Enregistrement avec motif, date, montant, compte et contact ; filtres avancés par période, compte et contact
- 💸 **Suivi des dépenses** — Saisie avec quantité × prix unitaire (calcul automatique du total), filtres avancés par période, catégorie et compte
- 🔍 **Recherche en temps réel** — Filtrage instantané sur toutes les listes via Alpine.js, sans rechargement de page
- ⚡ **Calcul automatique du solde** — Le solde de chaque compte se met à jour automatiquement à chaque opération
- 🎁 **Données par défaut à l'inscription** — 6 catégories préconfigurées + 1 compte "Espèce" (XOF) créés automatiquement
- 🛡️ **Sécurité renforcée** — Protection CSRF, autorisation fine par utilisateur (Policies), URLs par slugs (jamais d'ID en clair), suppression logique (SoftDeletes)
- 🗑️ **Suppression protégée** — Confirmation nominative avant toute suppression ; blocage si des opérations sont encore liées au compte

---

## 🛠️ Stack technique

| Technologie | Rôle | Version |
|---|---|---|
| [Laravel](https://laravel.com) | Framework PHP — backend, routing, ORM, auth | ^10.10 |
| [PHP](https://www.php.net) | Langage serveur | ^8.1 |
| [MySQL](https://www.mysql.com) | Base de données relationnelle | 8.x recommandé |
| [Blade](https://laravel.com/docs/10.x/blade) | Moteur de templates Laravel | Natif Laravel 10 |
| [Alpine.js](https://alpinejs.dev) | Réactivité légère côté client (modales, filtres, recherche) | CDN v3.x |
| [Chart.js](https://www.chartjs.org) | Graphiques dynamiques (dashboard) | CDN v4.x |
| [Tailwind CSS](https://tailwindcss.com) | Classes utilitaires CSS (via CDN + design system custom) | CDN v3.x |
| [Inter](https://fonts.google.com/specimen/Inter) | Police principale avec `tabular-nums` pour les montants | Google Fonts |
| [Vite](https://vitejs.dev) | Bundler frontend + hot-reload | ^5.0.0 |
| [Laravel Pint](https://laravel.com/docs/10.x/pint) | Formatage automatique du code PHP (PSR-12) | ^1.0 |
| [Composer](https://getcomposer.org) | Gestionnaire de dépendances PHP | 2.x |

---

## 📁 Structure du projet

```
finarcher/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php          # Inscription, connexion, déconnexion
│   │       ├── DashboardController.php     # KPIs, graphique 6 mois, filtre période
│   │       ├── CategorieController.php     # CRUD catégories
│   │       ├── PortefeuilleController.php  # CRUD comptes (met à jour solde)
│   │       ├── ActeurController.php        # CRUD contacts
│   │       ├── RevenuController.php        # CRUD revenus (met à jour solde)
│   │       ├── DepenseController.php       # CRUD dépenses (met à jour solde)
│   │       └── DeviseController.php        # Devises (usage interne)
│   ├── Models/
│   │   ├── User.php
│   │   ├── Devise.php                      # Table globale (non user-scopée)
│   │   ├── Categorie.php                   # HasSlug, SoftDeletes
│   │   ├── Portefeuille.php                # HasSlug, SoftDeletes
│   │   ├── Acteur.php                      # HasSlug, SoftDeletes
│   │   ├── Revenu.php                      # HasSlug, SoftDeletes
│   │   └── Depense.php                     # HasSlug, SoftDeletes
│   ├── Policies/
│   │   ├── CategoriePolicy.php
│   │   ├── PortefeuillePolicy.php
│   │   ├── ActeurPolicy.php
│   │   ├── RevenuPolicy.php
│   │   └── DepensePolicy.php               # Autorisation : user->id === model->user_id
│   ├── Providers/
│   │   └── AuthServiceProvider.php         # Enregistrement des Policies
│   └── Traits/
│       └── HasSlug.php                     # Génération de slugs URL-friendly (auto à la création)
├── database/
│   ├── migrations/
│   │   ├── ..._create_users_table.php
│   │   ├── ..._add_numero_to_users_table.php
│   │   ├── ..._create_devises_table.php
│   │   ├── ..._create_categories_table.php
│   │   ├── ..._create_portefeuilles_table.php
│   │   ├── ..._create_acteurs_table.php
│   │   ├── ..._create_revenus_table.php
│   │   ├── ..._create_depenses_table.php
│   │   └── ..._add_slugs_to_tables.php
│   └── seeders/
│       ├── DatabaseSeeder.php              # Orchestrateur (DeviseSeeder → DemoSeeder)
│       ├── DeviseSeeder.php                # Devises globales : XOF, EUR, USD
│       └── DemoSeeder.php                  # User démo avec données d'exemple
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php               # Layout principal (sidebar fixe verte #065F46)
│       │   └── auth.blade.php              # Layout split-screen (branding + formulaire)
│       ├── partials/
│       │   ├── sidebar.blade.php           # Navigation active, profil, logout
│       │   ├── search-bar.blade.php        # Recherche temps réel + bouton "Filtres avancés"
│       │   ├── delete-modal.blade.php      # Modale suppression nominative
│       │   ├── pagination.blade.php        # Pagination avec ellipsis
│       │   └── form-modal.blade.php        # Modale création/édition générique
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── dashboard.blade.php             # Dashboard : KPIs, Chart.js, dernières opérations
│       ├── categories/                     # index, create, edit
│       ├── portefeuilles/                  # index, create, edit
│       ├── acteurs/                        # index, create, edit
│       ├── revenus/                        # index, create, edit
│       └── depenses/                       # index, create, edit
├── routes/
│   └── web.php                             # Toutes les routes web (auth + ressources)
├── .env.example                            # Variables d'environnement à configurer
├── composer.json                           # Dépendances PHP
└── package.json                            # Dépendances frontend
```

---

## ⚙️ Prérequis système

- **PHP** >= 8.1 avec les extensions : `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`
- **Composer** >= 2.x
- **Node.js** >= 18.x + **npm** >= 9.x
- **MySQL** >= 8.x (ou MariaDB compatible)
- **Git**

---

## 🚀 Installation pas à pas

### 1. Cloner le dépôt

```bash
git clone https://github.com/Joan-EYEGHE/finarcher.git
cd finarcher
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

Ouvrez ensuite `.env` et renseignez vos paramètres de base de données :

```env
APP_NAME=FinArcher
APP_URL=http://localhost:8000

DB_DATABASE=finarcher
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

### 4. Créer la base de données MySQL

```sql
CREATE DATABASE finarcher CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Exécuter les migrations

```bash
# Migrations uniquement (base vide)
php artisan migrate

# Ou migration complète avec données de démonstration
php artisan migrate:fresh --seed
```

> **Note :** `--seed` exécute `DeviseSeeder` (devises globales XOF/EUR/USD) puis `DemoSeeder` (données d'exemple).
>
> Compte démo disponible après seeding : `demo@finarcher.com` / `password`

### 6. Installer les dépendances frontend et compiler les assets

```bash
npm install
npm run build
```

---

## ▶️ Lancer le projet

Ouvrez deux terminaux dans le dossier du projet :

```bash
# Terminal 1 — Serveur Laravel
php artisan serve
```

```bash
# Terminal 2 — Vite (hot-reload en développement)
npm run dev
```

L'application est accessible sur : [http://localhost:8000](http://localhost:8000)

---

## 🌐 Variables d'environnement

Les variables essentielles à configurer dans votre fichier `.env` :

| Variable | Valeur exemple | Description |
|---|---|---|
| `APP_NAME` | `FinArcher` | Nom affiché de l'application |
| `APP_ENV` | `local` | Environnement (`local`, `production`) |
| `APP_KEY` | *(généré par `artisan key:generate`)* | Clé de chiffrement Laravel |
| `APP_DEBUG` | `true` | Affichage des erreurs détaillées |
| `APP_URL` | `http://localhost:8000` | URL de base de l'application |
| `DB_CONNECTION` | `mysql` | Driver de base de données |
| `DB_HOST` | `127.0.0.1` | Hôte du serveur MySQL |
| `DB_PORT` | `3306` | Port MySQL |
| `DB_DATABASE` | `finarcher` | Nom de la base de données |
| `DB_USERNAME` | `root` | Utilisateur MySQL |
| `DB_PASSWORD` | *(vide ou votre mot de passe)* | Mot de passe MySQL |
| `SESSION_DRIVER` | `file` | Pilote de session (`file`, `database`, `redis`) |
| `SESSION_LIFETIME` | `120` | Durée de session en minutes |
| `CACHE_DRIVER` | `file` | Pilote de cache |
| `QUEUE_CONNECTION` | `sync` | Pilote de queue |
| `MAIL_MAILER` | `smtp` | Pilote d'envoi d'e-mails |
| `MAIL_FROM_ADDRESS` | `hello@finarcher.com` | Adresse expéditeur des e-mails |
| `VITE_APP_NAME` | `${APP_NAME}` | Nom de l'app exposé à Vite |

> Les variables `PUSHER_*`, `REDIS_*` et `AWS_*` sont présentes dans `.env.example` mais non utilisées dans la configuration actuelle de l'application.

---

## 🗃️ Base de données

### Tables principales

| Table | Description |
|---|---|
| `users` | Comptes utilisateurs (nom, email, mot de passe hashé, numéro) |
| `devises` | Devises globales non user-scopées : XOF (F CFA), EUR (€), USD ($) |
| `categories` | Catégories de dépenses par utilisateur — SoftDeletes |
| `portefeuilles` | Comptes financiers par utilisateur (solde calculé, devise) — SoftDeletes |
| `acteurs` | Contacts / sources de revenus par utilisateur — SoftDeletes |
| `revenus` | Flux entrants par utilisateur — SoftDeletes |
| `depenses` | Flux sortants par utilisateur — SoftDeletes |
| `password_reset_tokens` | Tokens de réinitialisation de mot de passe |
| `personal_access_tokens` | Tokens Sanctum (présents, non utilisés en session web) |
| `failed_jobs` | File d'attente — jobs en échec |

### Relations

```
User ──< Categorie
User ──< Portefeuille >── Devise
User ──< Acteur
User ──< Revenu >── Portefeuille
              └──── Acteur
User ──< Depense >── Categorie
               └──── Portefeuille (optionnel — nullable)
```

### Règles métier importantes

- **Étanchéité stricte** — Un utilisateur ne peut accéder qu'aux ressources dont le `user_id` lui appartient (contrôlé par des Policies).
- **Solde automatique** — Le champ `solde` d'un `Portefeuille` est recalculé dans les contrôleurs à chaque création, modification ou suppression de revenu (+) ou de dépense (−). Il n'est pas modifiable manuellement.
- **Catégorie "Divers" protégée** — Créée automatiquement à l'inscription avec `is_default = true` ; sa suppression est bloquée.
- **Blocage de suppression liée** — Un `Portefeuille` lié à des revenus ou dépenses ne peut pas être supprimé.
- **Slugs en URL** — Les IDs numériques ne sont jamais exposés dans les URLs ; seuls les slugs générés automatiquement sont utilisés.
- **Dépense sans compte** — Le champ `portefeuille_id` est nullable pour permettre une saisie rapide sans associer de compte.

---

## 📸 Captures d'écran

<!-- TODO: ajouter screenshot — chemin suggéré : docs/screenshots/login.png -->
**1. Page de connexion** — Split-screen : panneau branding vert à gauche, formulaire à droite.

<p align="center">
  <img src="docs/screenshots/login.png" alt="Page de connexion FinArcher" width="800"/>
</p>

<!-- TODO: ajouter screenshot — chemin suggéré : docs/screenshots/dashboard.png -->
**2. Tableau de bord** — KPIs, graphique barres groupées (6 mois), répartition catégories, dernières opérations.

<p align="center">
  <img src="docs/screenshots/dashboard.png" alt="Dashboard FinArcher" width="800"/>
</p>

<!-- TODO: ajouter screenshot — chemin suggéré : docs/screenshots/comptes.png -->
**3. Mes comptes (Portefeuilles)** — Vue des comptes multi-devises avec solde et compteurs.

<p align="center">
  <img src="docs/screenshots/comptes.png" alt="Liste des comptes FinArcher" width="800"/>
</p>

<!-- TODO: ajouter screenshot — chemin suggéré : docs/screenshots/depenses.png -->
**4. Mes dépenses** — Tableau avec catégories, quantités, prix unitaires, filtres avancés.

<p align="center">
  <img src="docs/screenshots/depenses.png" alt="Liste des dépenses FinArcher" width="800"/>
</p>

<!-- TODO: ajouter screenshot — chemin suggéré : docs/screenshots/revenus.png -->
**5. Mes revenus** — Tableau avec KPIs (total, moyenne, maximum), filtres par période et contact.

<p align="center">
  <img src="docs/screenshots/revenus.png" alt="Liste des revenus FinArcher" width="800"/>
</p>

---

## 👤 Auteur

**Joan EYEGHE**
M2 CDSD 2026 — Dakar, Sénégal

- GitHub : [@Joan-EYEGHE](https://github.com/Joan-EYEGHE)
- Dépôt : [github.com/Joan-EYEGHE/finarcher](https://github.com/Joan-EYEGHE/finarcher)

---

## 📜 Licence

Ce projet est distribué sous licence **MIT**.
Voir le fichier [LICENSE](LICENSE) pour plus de détails.

---

<div align="center">
  <sub>Fait avec précision — FinArcher 🎯</sub>
</div>

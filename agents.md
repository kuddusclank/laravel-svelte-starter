# Laravel Svelte Starter — Project Guide for AI Agents

## Project Overview

A production-ready authentication starter kit built with Laravel 12 and Svelte 5. Provides complete auth scaffolding (login, registration, password reset, email verification, two-factor authentication, and social OAuth for 5 providers) so developers can skip boilerplate and start building features immediately.

The starter offers **two frontend variants**, selected during `php artisan app:setup`:

1. **Svelte (Inertia)** — Traditional Inertia.js SPA where Laravel handles routing and renders Svelte pages server-side. No API needed; data flows via Inertia shared props.
2. **SvelteKit** — SvelteKit handles routing, SSR, and data loading. Laravel becomes a pure API backend (session-based, cookie auth). Inertia is removed entirely.

Target audience: developers who want a modern, type-safe, SSR-capable full-stack starter with authentication out of the box.

---

## Tech Stack

| Layer | Technology | Version | Purpose |
|-------|-----------|---------|---------|
| Backend | Laravel | 12.x | PHP framework, routing, ORM, auth |
| Frontend | Svelte | 5.x | Reactive UI with runes API |
| Bridge | Inertia.js | 2.x | SPA-like experience without an API |
| UI Library | Skeleton UI | 4.x | Component library (Cerberus theme) |
| CSS | Tailwind CSS | 4.x | Utility-first styling (config-free) |
| Auth | Laravel Fortify | 1.x | Backend auth scaffolding |
| Social Auth | Laravel Socialite | 5.x | OAuth provider integration |
| Routing (JS) | Ziggy | 2.x | Named Laravel routes in JavaScript |
| Icons | Lucide Svelte | 0.x | SVG icon components |
| Build | Vite | 7.x | Dev server, HMR, SSR bundling |
| SvelteKit | @sveltejs/kit | 2.x | Full-stack framework (SvelteKit variant) |
| SvelteKit Adapter | adapter-node | 5.x | Node.js production adapter (SvelteKit variant) |
| Language | TypeScript | 5.x | Type-safe frontend code |
| Testing | PHPUnit | 11.x | Backend test suite |
| Database | SQLite | — | Default dev database |

---

## Directory Structure

```
├── app/
│   ├── Actions/Fortify/           # Auth actions (CreateNewUser, ResetUserPassword, etc.)
│   ├── Console/
│   │   ├── Commands/SetupCommand.php  # Interactive setup wizard
│   │   └── Support/EnvFileEditor.php  # .env file read/write utility
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/ProfileController.php        # JSON profile endpoints (SvelteKit)
│   │   │   ├── Auth/SocialAuthController.php    # OAuth redirect + callback
│   │   │   └── Settings/                        # Profile, Password, TwoFactor controllers
│   │   ├── Middleware/
│   │   │   ├── HandleInertiaRequests.php       # Shares auth, social providers, ziggy
│   │   │   └── HandleAppearance.php            # Theme preference middleware
│   │   └── Requests/Settings/                  # Form request validation
│   ├── Models/User.php            # User model with social + 2FA fields
│   └── Providers/
│       ├── AppServiceProvider.php
│       └── FortifyServiceProvider.php  # Auth views, rate limiting, action bindings
├── resources/
│   ├── css/app.css                # Global styles, Tailwind imports
│   └── js/
│       ├── app.ts                 # Client-side Inertia bootstrap
│       ├── ssr.ts                 # Server-side rendering entry
│       ├── components/            # Reusable Svelte components
│       ├── layouts/               # Layout system (app, auth, settings)
│       ├── lib/                   # Utilities (theme, utils, initials)
│       ├── pages/                 # Inertia page components
│       └── types/                 # TypeScript type definitions
├── sveltekit/                       # SvelteKit variant (promoted to root by wizard)
│   ├── src/                        # SvelteKit app source
│   ├── package.json                # SvelteKit dependencies
│   ├── svelte.config.js            # adapter-node config
│   └── vite.config.ts              # SvelteKit + Tailwind
├── routes/
│   ├── web.php                    # Main routes (home, dashboard, social auth)
│   ├── api.php                    # API routes for SvelteKit (web middleware)
│   ├── settings.php               # Settings routes (profile, password, appearance, 2FA)
│   └── console.php                # Console route definitions
├── config/
│   ├── fortify.php                # Fortify config (features, guards, views)
│   └── services.php               # OAuth provider credentials
├── database/
│   └── migrations/                # Schema migrations
├── tests/                         # PHPUnit test suite
├── composer.json                  # PHP dependencies + scripts
├── package.json                   # JS dependencies + scripts
├── vite.config.ts                 # Vite + Svelte + Tailwind config
└── .env.example                   # Environment variable template
```

---

## Authentication System

### Fortify-Powered Auth Flow

All authentication is handled by Laravel Fortify. The `FortifyServiceProvider` binds:

- **Actions**: `CreateNewUser`, `UpdateUserProfileInformation`, `UpdateUserPassword`, `ResetUserPassword`
- **Views**: Each auth route renders an Inertia Svelte page (no Blade)
- **Rate Limiting**: 5 attempts/min for login and 2FA

### Auth Pages

| Route | Page Component | Purpose |
|-------|---------------|---------|
| `/login` | `pages/auth/Login.svelte` | Email/password + social login |
| `/register` | `pages/auth/Register.svelte` | User registration |
| `/forgot-password` | `pages/auth/ForgotPassword.svelte` | Request password reset link |
| `/reset-password/{token}` | `pages/auth/ResetPassword.svelte` | Set new password |
| `/email/verify` | `pages/auth/VerifyEmail.svelte` | Email verification prompt |
| `/password/confirm` | `pages/auth/ConfirmPassword.svelte` | Re-enter password for sensitive actions |
| `/two-factor-challenge` | `pages/auth/TwoFactorChallenge.svelte` | Enter TOTP code or recovery code |

### Two-Factor Authentication (2FA)

- Uses TOTP via `TwoFactorAuthenticatable` trait on `User` model
- Setup/disable at `/settings/two-factor`
- QR code display + manual entry key
- 8 recovery codes generated on enable
- Recovery codes viewable and regenerable in settings

### Email Verification

- Disabled by default (import is commented out in `User.php`)
- Enabled via setup wizard or manually: uncomment `use MustVerifyEmail` import and add `implements MustVerifyEmail` to `User` class
- When enabled, the `verified` middleware on dashboard routes enforces verification

---

## SSO / Social Login

### Supported Providers

| Provider | Socialite Driver | Env Prefix | Callback URL |
|----------|-----------------|-----------|-------------|
| GitHub | `github` | `GITHUB_` | `/auth/github/callback` |
| Facebook | `facebook` | `FACEBOOK_` | `/auth/facebook/callback` |
| X (Twitter) | `twitter` | `X_` | `/auth/x/callback` |
| Google | `google` | `GOOGLE_` | `/auth/google/callback` |
| Apple | `apple` | `APPLE_` | `/auth/apple/callback` |

### Flow

1. `SocialAuthController::redirect()` validates the provider and initiates OAuth via Socialite
2. Provider redirects back to `SocialAuthController::callback()`
3. Controller looks up user by `provider` + `provider_id`, or links by email, or creates new user
4. User is logged in with `remember: true` and redirected to `/dashboard`

### Conditional Display

`HandleInertiaRequests` middleware shares `socialProviders` — an array of provider slugs where `{PREFIX}_CLIENT_ID` is non-empty. The `SocialLoginButtons` component renders buttons only for providers with configured credentials.

### Env Variable Pattern

Each provider needs three env vars:
```
{PREFIX}_CLIENT_ID=
{PREFIX}_CLIENT_SECRET=
{PREFIX}_REDIRECT_URI=${APP_URL}/auth/{slug}/callback
```

The redirect URI is already set in `.env.example` using `${APP_URL}` interpolation.

---

## Frontend Architecture

### Svelte 5 Runes API

This project uses the Svelte 5 runes API exclusively (not legacy `$:` reactive declarations):

- `$state()` — reactive state declaration
- `$derived()` — computed values
- `$effect()` — side effects
- `$props()` — component props (replaces `export let`)
- `$bindable()` — two-way bindable props
- `{#snippet}` / `{@render}` — replaces slots

### Inertia Page Components

Pages live in `resources/js/pages/` and are resolved by name in the Inertia app setup (`app.ts`). Each page component sets its layout via module-level script:

```svelte
<script lang="ts" module>
    import AppLayout from '@/layouts/AppLayout.svelte';
    export const layout = AppLayout;
</script>
```

### Layout System

Layouts use a resolver pattern:

- `AppLayout.svelte` → resolves to `app/AppSidebarLayout.svelte`
- `AuthLayout.svelte` → resolves to `auth/AuthSimpleLayout.svelte`

Layout subdirectories: `layouts/app/`, `layouts/auth/`, `layouts/settings/`.

### TypeScript Types

Defined in `resources/js/types/`:

- `auth.ts` — `User`, `Auth` interfaces
- `navigation.ts` — `NavItem`, `BreadcrumbItem`
- `ui.ts` — `Appearance` type (light/dark/system)
- `global.d.ts` — global augmentations
- `index.ts` — barrel exports

### Path Aliases

- `@` → `resources/js/` (configured in both `vite.config.ts` and `tsconfig.json`)
- `ziggy-js` → resolved via Vite config

---

## Styling

### Skeleton UI v4 with Cerberus Theme

- Components from `@skeletonlabs/skeleton-svelte` (buttons, inputs, modals, etc.)
- Cerberus theme applied globally
- Preset classes: `preset-tonal`, `preset-filled`, etc.

### Tailwind CSS v4

- Config-free setup (no `tailwind.config.js`)
- Imported via `@tailwindcss/vite` plugin
- Styles in `resources/css/app.css`

### Dark Mode

- Controlled via `data-mode="dark"` attribute on `<body>`
- Theme toggled in `/settings/appearance`
- Three modes: light, dark, system
- Persisted via cookie (`appearance`)

### Glass Morphism Patterns

The Welcome page and auth layouts use glass morphism effects:
```css
backdrop-blur-xl bg-white/70 dark:bg-surface-900/70
```

---

## Database

### Default: SQLite

Database file: `database/database.sqlite` (auto-created by setup wizard).

### Migrations

1. `create_users_table` — users, password_reset_tokens, sessions
2. `create_cache_table` — cache + cache_locks
3. `create_jobs_table` — jobs, job_batches, failed_jobs
4. `add_two_factor_columns_to_users_table` — two_factor_secret, two_factor_recovery_codes, two_factor_confirmed_at
5. `add_social_columns_to_users_table` — provider (nullable string), provider_id (nullable string)

### User Model Schema

| Column | Type | Notes |
|--------|------|-------|
| id | integer | Primary key |
| name | string | |
| email | string | Unique |
| email_verified_at | timestamp | Nullable |
| password | string | Hashed, nullable (social users) |
| provider | string | Nullable (github, facebook, x, google, apple) |
| provider_id | string | Nullable |
| two_factor_secret | text | Nullable, encrypted |
| two_factor_recovery_codes | text | Nullable, encrypted |
| two_factor_confirmed_at | timestamp | Nullable |
| remember_token | string | Nullable |
| created_at | timestamp | |
| updated_at | timestamp | |

---

## CLI Setup Wizard

### Command: `php artisan app:setup`

Interactive wizard that runs automatically after `composer create-project` or manually via `composer setup`.

### Flow

1. Welcome banner
2. Framework selection — **Svelte (Inertia)** or **SvelteKit** (fully functional, scaffolds the selected variant)
3. Email verification toggle (yes/no)
4. SSO provider multi-select (GitHub, Facebook, X, Google, Apple)
5. For each selected provider: collect Client ID and Client Secret (secret input masked)
6. Summary table showing all selections
7. Confirmation prompt
8. Apply configuration:
   - Write SSO credentials to `.env` via `EnvFileEditor`
   - Toggle `MustVerifyEmail` on `User` model
9. Scaffold project:
   - Generate app key (if not set)
   - Create SQLite database (if missing)
   - Run migrations (`--graceful`)
   - Install npm dependencies (if `node_modules` missing)
   - Build frontend assets
10. Display completion message with `composer dev` command

### Idempotency

The wizard is safe to re-run:
- `.env` values are updated in-place (not appended as duplicates)
- `key:generate` only runs if `APP_KEY` is empty
- `migrate --graceful` skips if no pending migrations
- `npm install` skips if `node_modules` exists
- `MustVerifyEmail` toggle checks current state before modifying

---

## Development Workflow

### Start Development Server

```bash
composer dev
```

Runs concurrently:
- `php artisan serve` — Laravel dev server (port 8000)
- `npm run dev` — Vite dev server with HMR

### Build for Production

```bash
npm run build
```

Runs two Vite builds: client-side and SSR.

### Run Tests

```bash
php artisan test
```

PHPUnit test suite with 27 tests covering auth flows, profile management, and password updates.

### Code Quality

```bash
npm run lint      # ESLint
npm run format    # Prettier
npm run types:check  # Svelte type checking
./vendor/bin/pint    # PHP code style (Laravel Pint)
```

---

## Key Files Reference

| File | Purpose |
|------|---------|
| `app/Models/User.php` | User model with social + 2FA support |
| `app/Providers/FortifyServiceProvider.php` | Auth configuration and view bindings |
| `app/Http/Controllers/Auth/SocialAuthController.php` | OAuth redirect/callback handler |
| `app/Http/Middleware/HandleInertiaRequests.php` | Shares auth data + enabled social providers |
| `app/Console/Commands/SetupCommand.php` | Interactive setup wizard |
| `app/Console/Support/EnvFileEditor.php` | Safe .env file editor |
| `config/services.php` | OAuth provider credentials |
| `config/fortify.php` | Fortify feature flags |
| `resources/js/app.ts` | Client-side Inertia bootstrap |
| `resources/js/ssr.ts` | SSR entry point |
| `resources/js/pages/` | All page components |
| `resources/js/components/SocialLoginButtons.svelte` | Conditional social login buttons |
| `resources/js/layouts/` | Layout resolver system |
| `resources/js/types/` | TypeScript type definitions |
| `resources/css/app.css` | Global styles + Tailwind imports |
| `routes/web.php` | Main routes (home, dashboard, social auth) |
| `routes/settings.php` | Settings routes |
| `vite.config.ts` | Vite + Svelte + Tailwind + path aliases |
| `.env.example` | All environment variables with defaults |
| `composer.json` | PHP deps + setup/dev scripts |
| `package.json` | JS deps + build/lint/format scripts |

---

## Conventions

### Backend

- **Fortify actions** for all auth operations (no custom auth controllers for login/register)
- **Single-action concerns** in `app/Concerns/` for shared behavior
- **Form requests** in `app/Http/Requests/Settings/` for validation
- **API routes** exist for SvelteKit variant (`routes/api.php` with `web` middleware); Inertia variant doesn't use them
- **SQLite by default** — zero config database for development

### Frontend

- **Svelte 5 runes API only** — no legacy reactive declarations
- **Module-level `export const layout`** — for setting page layouts
- **`$page.props`** — access shared server data (auth, socialProviders, etc.)
- **Inertia `useForm()`** — for all form submissions with automatic error handling
- **`@` path alias** — always use `@/` imports, never relative paths from pages
- **Skeleton UI presets** — use `preset-filled`, `preset-tonal`, etc. for consistent styling
- **`{#snippet}` blocks** — for reusable template fragments (replaces Svelte 4 slots)

### Naming

- **Svelte components**: PascalCase (`SocialLoginButtons.svelte`)
- **Page components**: PascalCase, mirror route structure (`auth/Login.svelte`)
- **TypeScript types**: PascalCase interfaces in `types/` directory
- **Env variables**: SCREAMING_SNAKE_CASE with provider prefix (`GITHUB_CLIENT_ID`)
- **Routes**: kebab-case URLs, named with dot notation (`profile.edit`)

### Form Handling Pattern (Inertia)

```svelte
<script lang="ts">
    import { useForm } from '@inertiajs/svelte';

    const form = useForm({ name: '', email: '' });

    function submit(e: SubmitEvent) {
        e.preventDefault();
        $form.put('/settings/profile');
    }
</script>

<form onsubmit={submit}>
    <input bind:value={$form.name} />
    <InputError message={$form.errors.name} />
</form>
```

### Form Handling Pattern (SvelteKit)

```svelte
<script lang="ts">
    import { enhance } from '$app/forms';

    let { form } = $props();
    let processing = $state(false);
</script>

<form method="POST" use:enhance={() => {
    processing = true;
    return async ({ update }) => {
        processing = false;
        await update();
    };
}}>
    <input name="name" value={form?.name ?? ''} />
    <InputError message={form?.errors?.name} />
</form>
```

---

## SvelteKit Variant Architecture

### Overview

When the SvelteKit variant is selected, the architecture changes fundamentally:

- **SvelteKit** handles routing, SSR, and data loading (replaces Inertia.js)
- **Laravel** becomes a pure API backend — session-based, cookie authentication
- **Laravel Fortify** still handles auth logic, but with `'views' => false`
- Both servers run concurrently: Laravel on port 8000, SvelteKit on port 5173

### Directory Structure (after setup)

```
├── src/                              # SvelteKit application
│   ├── app.html                      # Root HTML template
│   ├── app.css                       # Global styles (identical to Inertia variant)
│   ├── app.d.ts                      # Type declarations (App.Locals, App.PageData)
│   ├── hooks.server.ts               # Extracts cookies + XSRF token per request
│   ├── lib/
│   │   ├── api.ts                    # laravelFetch() helper + cookie forwarding
│   │   ├── types/                    # Same types as Inertia variant
│   │   ├── utils/                    # Same utils as Inertia variant
│   │   └── components/               # Ported components ($lib/ aliases instead of @/)
│   └── routes/
│       ├── +layout.svelte            # Root layout (imports app.css)
│       ├── +layout.server.ts         # Fetches user + socialProviders from API
│       ├── +page.svelte              # Welcome/landing page
│       ├── (auth)/                   # Auth group (login, register, etc.)
│       │   ├── +layout.svelte        # Auth layout (centered card)
│       │   ├── login/                # +page.svelte + +page.server.ts
│       │   ├── register/
│       │   ├── forgot-password/
│       │   ├── reset-password/[token]/
│       │   ├── verify-email/
│       │   ├── confirm-password/
│       │   └── two-factor-challenge/
│       ├── (app)/                    # Authenticated app group
│       │   ├── +layout.svelte        # Sidebar layout
│       │   ├── +layout.server.ts     # Auth guard (redirect if not logged in)
│       │   ├── dashboard/
│       │   └── settings/             # Profile, password, appearance, 2FA
│       ├── auth/[provider]/          # OAuth proxy (redirect + callback)
│       └── logout/                   # POST action to Laravel /logout
├── routes/
│   ├── api.php                       # Laravel API endpoints (web middleware)
│   └── web.php                       # Social auth routes only
├── svelte.config.js                  # adapter-node
├── vite.config.ts                    # @tailwindcss/vite + sveltekit
├── package.json                      # SvelteKit deps (no Inertia/axios/ziggy)
└── tsconfig.json                     # Extends .svelte-kit/tsconfig.json
```

### CSRF & Cookie Strategy

SvelteKit form actions run server-side. The `laravelFetch()` helper:

1. Forwards the browser's session cookies to Laravel
2. Extracts XSRF-TOKEN from cookies, sends as `X-XSRF-TOKEN` header
3. Uses `forwardCookies()` to relay `Set-Cookie` headers from Laravel responses back to the browser
4. Sets `redirect: 'manual'` to prevent auto-following redirects (uses SvelteKit's `redirect()` instead)

### API Routes

`routes/api.php` uses `web` middleware (not `api`) so sessions/CSRF work:

| Method | Route | Purpose |
|--------|-------|---------|
| GET | `/api/user` | Current authenticated user (or 401) |
| GET | `/api/social-providers` | Array of configured OAuth provider slugs |
| PUT | `/api/settings/profile` | Update profile (JSON response) |
| DELETE | `/api/settings/profile` | Delete account (JSON response) |

### Development Workflow (SvelteKit)

```bash
composer dev    # Starts both Laravel (8000) and SvelteKit (5173) servers
```

- SvelteKit dev server proxies auth requests server-side to Laravel
- Both share the same session cookies (same domain, different ports)
- HMR works for SvelteKit pages; Laravel serves API responses

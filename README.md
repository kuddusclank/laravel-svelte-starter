# Laravelte — Laravel + Svelte Starter Kit

A production-ready Laravel 12 starter kit with **Svelte 5**, **Skeleton UI**, **Tailwind CSS v4**, and complete authentication scaffolding — including SSO, two-factor auth, and email verification.

Choose between two frontend architectures during setup:

- **Svelte (Inertia)** — Laravel handles routing; Svelte renders pages via Inertia.js
- **SvelteKit** — SvelteKit handles routing and SSR; Laravel serves as an API backend

## Quick Start

```bash
npm create laravelte my-app
```

That's it. The CLI scaffolds the project, installs dependencies, and launches an interactive setup wizard that configures everything.

### Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- npm

### What the setup wizard does

1. **Framework selection** — Choose Svelte (Inertia) or SvelteKit
2. **Email verification** — Enable or disable `MustVerifyEmail` on the User model
3. **SSO providers** — Select from GitHub, Facebook, X, Google, Apple
4. **Credential collection** — Enter Client ID and Client Secret for each selected provider
5. **Summary & confirmation** — Review all choices before applying
6. **Scaffolding** — Removes unused framework files, configures routes, writes `.env` values, runs migrations, installs npm packages, and builds assets

After setup, start developing:

```bash
cd my-app
composer dev
```

This launches Laravel (port 8000) and the frontend dev server concurrently.

## Features

### Authentication

- Email/password registration and login
- "Remember me" persistent sessions
- Password reset with token-based email flow
- Email verification (optional, configurable during setup)
- Password confirmation for sensitive actions

### Two-Factor Authentication (2FA)

- TOTP-based (works with Google Authenticator, Authy, etc.)
- QR code setup with manual secret key fallback
- 8 recovery codes generated on enable
- Recovery codes viewable and regeneratable
- Recovery code login as backup

### Social Login (SSO)

Five OAuth providers supported out of the box:

| Provider | Env Prefix | Callback URL |
|----------|------------|--------------|
| GitHub | `GITHUB_` | `/auth/github/callback` |
| Facebook | `FACEBOOK_` | `/auth/facebook/callback` |
| X (Twitter) | `X_` | `/auth/x/callback` |
| Google | `GOOGLE_` | `/auth/google/callback` |
| Apple | `APPLE_` | `/auth/apple/callback` |

For each provider, the setup wizard writes three env vars:

```
{PREFIX}_CLIENT_ID=your-client-id
{PREFIX}_CLIENT_SECRET=your-client-secret
{PREFIX}_REDIRECT_URI=http://localhost/auth/{slug}/callback
```

Only providers with a non-empty `CLIENT_ID` render login buttons on the frontend.

**How it works:**
- `/auth/{provider}/redirect` initiates the OAuth flow
- `/auth/{provider}/callback` handles the response
- If the email matches an existing user, the accounts are linked
- If the email is new, a user is created with auto-verified email and no password

### Settings Pages

- **Profile** — Update name and email
- **Password** — Change password with current password confirmation
- **Two-Factor Authentication** — Enable/disable 2FA, view recovery codes
- **Appearance** — Light, dark, or system theme
- **Account Deletion** — Delete account with password confirmation

## Framework Architectures

### Svelte (Inertia)

Laravel owns routing. Svelte components are rendered via Inertia.js with SPA-like navigation.

```
resources/js/
├── pages/           # Svelte page components (routed by Laravel)
├── components/      # Reusable components
├── layouts/         # Layout system
├── app.ts           # Client-side Inertia bootstrap
└── ssr.ts           # Server-side rendering entry
```

**Key characteristics:**
- All routes defined in `routes/web.php`
- Data passed to pages as Inertia props
- Forms use Inertia's `useForm()` hook
- Ziggy provides named route resolution in JS
- Single server process (`php artisan serve`)

### SvelteKit

SvelteKit owns routing and SSR. Laravel serves a REST API with session-based auth.

```
src/
├── routes/          # SvelteKit file-based routing
│   ├── (auth)/      # Auth pages (login, register, etc.)
│   ├── (app)/       # Authenticated pages (dashboard, settings)
│   └── auth/        # OAuth proxy endpoints
├── lib/
│   ├── api.ts       # laravelFetch() helper with CSRF handling
│   ├── components/  # Reusable Svelte components
│   ├── types/       # TypeScript interfaces
│   └── utils/       # Utilities (theme, initials, etc.)
└── hooks.server.ts  # Cookie and XSRF token extraction
```

**Key characteristics:**
- All routes defined in `src/routes/`
- Data loaded via `+page.server.ts` load functions
- Forms use SvelteKit's `enhance` action
- API calls use `laravelFetch()` with cookie forwarding
- Two server processes (`php artisan serve` + `npm run dev`)
- `LARAVEL_URL` env var points SvelteKit to the Laravel API

## Environment Variables

### Core

| Variable | Default | Description |
|----------|---------|-------------|
| `APP_NAME` | Laravel | Application name |
| `APP_URL` | http://localhost | Base URL (used for SSO callbacks) |
| `APP_KEY` | — | Generated during setup |
| `DB_CONNECTION` | sqlite | Database driver |

### SSO Providers

Each provider uses three env vars:

| Variable | Description |
|----------|-------------|
| `{PREFIX}_CLIENT_ID` | OAuth Client ID |
| `{PREFIX}_CLIENT_SECRET` | OAuth Client Secret |
| `{PREFIX}_REDIRECT_URI` | OAuth callback URL |

Prefixes: `GITHUB`, `FACEBOOK`, `X`, `GOOGLE`, `APPLE`

### SvelteKit-only

| Variable | Default | Description |
|----------|---------|-------------|
| `LARAVEL_URL` | http://localhost:8000 | Laravel API URL for SvelteKit |

## Available Commands

### Composer

| Command | Description |
|---------|-------------|
| `composer dev` | Start Laravel + frontend dev servers concurrently |
| `composer setup` | Re-run the interactive setup wizard |

### Artisan

| Command | Description |
|---------|-------------|
| `php artisan app:setup` | Interactive setup wizard |
| `php artisan serve` | Start Laravel dev server |
| `php artisan test` | Run PHPUnit tests |
| `php artisan migrate` | Run database migrations |

### npm

| Command | Description |
|---------|-------------|
| `npm run dev` | Start Vite/SvelteKit dev server |
| `npm run build` | Production build |
| `npm run preview` | Preview production build |
| `npm run lint` | ESLint with auto-fix |
| `npm run format` | Prettier formatting |
| `npm run types:check` | TypeScript type checking |

## Setting Up SSO Providers

### GitHub

1. Go to [GitHub Developer Settings](https://github.com/settings/developers) > OAuth Apps > New OAuth App
2. Set **Authorization callback URL** to `http://localhost/auth/github/callback`
3. Copy the Client ID and Client Secret into the setup wizard (or `.env`)

### Google

1. Go to [Google Cloud Console](https://console.cloud.google.com/apis/credentials) > Create Credentials > OAuth Client ID
2. Set **Authorized redirect URI** to `http://localhost/auth/google/callback`
3. Copy the Client ID and Client Secret into the setup wizard (or `.env`)

### Facebook

1. Go to [Facebook Developers](https://developers.facebook.com/apps/) > Create App > Consumer
2. Add Facebook Login product, set **Valid OAuth Redirect URI** to `http://localhost/auth/facebook/callback`
3. Copy the App ID and App Secret into the setup wizard (or `.env`)

### X (Twitter)

1. Go to [X Developer Portal](https://developer.x.com/en/portal/dashboard) > Create Project & App
2. Enable OAuth 2.0, set **Callback URL** to `http://localhost/auth/x/callback`
3. Copy the Client ID and Client Secret into the setup wizard (or `.env`)

### Apple

1. Go to [Apple Developer](https://developer.apple.com/account/resources/identifiers/list/serviceId) > Certificates, Identifiers & Profiles
2. Create a Services ID, configure Sign in with Apple, set **Return URL** to `http://localhost/auth/apple/callback`
3. Copy the Service ID (Client ID) and generated Client Secret into the setup wizard (or `.env`)

## Tech Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| Backend | Laravel | 12.x |
| Auth | Laravel Fortify | 1.x |
| Social Auth | Laravel Socialite | 5.x |
| Frontend | Svelte | 5.x |
| Full-stack (option) | SvelteKit | 2.x |
| Routing (option) | Inertia.js | 2.x |
| CSS | Tailwind CSS | 4.x |
| UI Components | Skeleton UI | 4.x |
| Icons | Lucide Svelte | — |
| Build Tool | Vite | 6.x |
| Language | TypeScript | 5.x |
| Database | SQLite (default) | — |
| Testing | PHPUnit | 11.x |

## Manual Installation

If you prefer not to use the CLI:

```bash
# Clone the repo
git clone https://github.com/kuddusclank/laravel-svelte-starter.git my-app
cd my-app

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Run the setup wizard
php artisan app:setup
```

## License

MIT

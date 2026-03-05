# Laravel + Svelte Starter Kit

A custom Laravel 12 starter kit with **Svelte 5**, **Inertia.js v2**, **Skeleton UI v3**, **Ziggy**, and **SSR support**.

## Features

- **Laravel 12** with Fortify authentication
- **Svelte 5** with runes and TypeScript
- **Inertia.js v2** for SPA-like navigation
- **Skeleton UI v3** component library (Cerberus theme)
- **Ziggy** for named route resolution
- **SSR** (Server-Side Rendering) support
- **Tailwind CSS v4** with custom dark mode via `data-mode`
- **Two-Factor Authentication** (TOTP)
- Full auth scaffolding: login, register, password reset, email verification, 2FA
- Settings pages: profile, password, appearance, two-factor auth
- Sidebar and header layout variants
- Dark/light/system appearance toggle

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+

## Installation

```bash
# Clone the repository
git clone <repo-url> my-app
cd my-app

# Install dependencies
composer install
npm install

# Configure environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Start development
php artisan serve
npm run dev
```

## Usage with `laravel new`

```bash
laravel new my-app --using=your-vendor/laravel-svelte-starter
```

## SSR

```bash
# Build with SSR
npm run build

# Start SSR server
php artisan inertia:start-ssr
```

## Testing

```bash
php artisan test
```

## Tech Stack

| Technology | Version |
|---|---|
| Laravel | 12.x |
| Svelte | 5.x |
| Inertia.js | 2.x |
| Skeleton UI | 3.x |
| Tailwind CSS | 4.x |
| Ziggy | 2.x |
| Fortify | 1.x |

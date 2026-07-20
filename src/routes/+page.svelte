<script lang="ts">
    import { page } from '$app/stores';
    import AppHead from '$lib/components/AppHead.svelte';
    import AppLogoIcon from '$lib/components/AppLogoIcon.svelte';
    import { createAppearanceState } from '$lib/utils/theme.svelte';
    import type { Appearance } from '$lib/types';
    import {
        ArrowRight,
        ExternalLink,
        Shield,
        KeyRound,
        Users,
        Zap,
        Code,
        Terminal,
        Sun,
        Moon,
        Copy,
        Check,
    } from 'lucide-svelte';

    const user = $derived($page.data.user);

    // Initial theme setup
    const theme = createAppearanceState(
        ((typeof document !== 'undefined' ? document.cookie.match(/appearance=(\w+)/)?.[1] : 'system') as Appearance) ??
            'system',
    );

    function toggleTheme() {
        const next = theme.appearance === 'dark' ? 'light' : 'dark';
        theme.update(next);
    }

    let copied = $state(false);
    function copyCommand() {
        navigator.clipboard.writeText('npm create laravelte@latest');
        copied = true;
        setTimeout(() => {
            copied = false;
        }, 2000);
    }

    const resources = [
        {
            title: 'Laravel Documentation',
            desc: 'The PHP framework for Web Artisans. Read about routing, controllers, Fortify, and services.',
            url: 'https://laravel.com/docs',
            badge: 'Backend',
        },
        {
            title: 'Laravel Cloud',
            desc: 'Deploy your full-stack applications with push-to-deploy, autoscaling, and database provisioning.',
            url: 'https://cloud.laravel.com',
            badge: 'Hosting',
        },
        {
            title: 'Shadcn Svelte',
            desc: 'Beautifully designed Svelte components built with Radix and Tailwind. accessible & customizable.',
            url: 'https://shadcn-svelte.com',
            badge: 'UI Kit',
        },
        {
            title: 'Lucide Icons',
            desc: 'Beautiful, clean, and consistent vector icons designed for modern user interfaces.',
            url: 'https://lucide.dev',
            badge: 'Assets',
        },
    ];

    const features = [
        {
            icon: Shield,
            title: 'Robust Security Scaffold',
            desc: 'Password resets, email verification, and session management pre-scaffolded with Laravel Fortify.',
        },
        {
            icon: KeyRound,
            title: 'Multi-factor Security',
            desc: 'Pristine, built-in TOTP two-factor authentication including recovery keys and session management.',
        },
        {
            icon: Users,
            title: 'Social Identity (SSO)',
            desc: 'One-click sign-in using GitHub, Google, Apple, Facebook, or X. Automatic account linking by email.',
        },
        {
            icon: Zap,
            title: 'Svelte 5 Runes & SSR',
            desc: 'Blazing fast Svelte 5 frontend using SvelteKit file-based routing and SSR for pristine search indexing.',
        },
        {
            icon: Code,
            title: 'Strict Type-Safety',
            desc: 'End-to-end typed contracts. TypeScript interfaces for components, forms, layout loads, and server responses.',
        },
        {
            icon: Terminal,
            title: 'Pristine Developer Flow',
            desc: 'Vite compilation, concurrent server execution, Pest testing framework, and premium interactive scaffolding.',
        },
    ];
</script>

<AppHead title="Laravelte Starter" />

<div class="bg-background text-foreground min-h-screen transition-colors duration-300">
    <!-- Ambient top glow -->
    <div
        class="pointer-events-none absolute inset-x-0 top-0 h-[600px] bg-gradient-to-b from-neutral-100/50 to-transparent dark:from-neutral-900/50"
    ></div>

    <!-- Header / Navbar -->
    <nav class="border-border/60 bg-background/80 sticky top-0 z-50 border-b backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8">
            <a href="/" class="text-foreground flex items-center gap-2 font-semibold tracking-tight hover:opacity-90">
                <AppLogoIcon />
                <span class="text-lg">Laravelte</span>
            </a>

            <div class="hidden items-center gap-8 md:flex">
                <a
                    href="#resources"
                    class="text-muted-foreground hover:text-foreground text-sm font-medium transition-colors"
                    >Resources</a
                >
                <a
                    href="#features"
                    class="text-muted-foreground hover:text-foreground text-sm font-medium transition-colors"
                    >Features</a
                >
                <a
                    href="#setup"
                    class="text-muted-foreground hover:text-foreground text-sm font-medium transition-colors"
                    >Quickstart</a
                >
            </div>

            <div class="flex items-center gap-4">
                <!-- Theme Toggle Button -->
                <button
                    onclick={toggleTheme}
                    class="border-border text-muted-foreground hover:bg-accent hover:text-foreground rounded-lg border p-2.5 transition-all duration-200"
                    aria-label="Toggle dark mode"
                >
                    {#if theme.appearance === 'dark'}
                        <Sun class="size-4" />
                    {:else}
                        <Moon class="size-4" />
                    {/if}
                </button>

                {#if user}
                    <a
                        href="/dashboard"
                        class="bg-primary text-primary-foreground hover:bg-primary/90 inline-flex h-9 items-center justify-center rounded-lg px-4 text-sm font-medium shadow-sm transition-all duration-200"
                    >
                        Dashboard
                    </a>
                {:else}
                    <a
                        href="/login"
                        class="text-muted-foreground hover:text-foreground hidden text-sm font-medium transition-colors sm:inline-block"
                    >
                        Log in
                    </a>
                    <a
                        href="/register"
                        class="bg-primary text-primary-foreground hover:bg-primary/90 inline-flex h-9 items-center justify-center rounded-lg px-4 text-sm font-medium shadow-sm transition-all duration-200"
                    >
                        Get Started
                    </a>
                {/if}
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative mx-auto max-w-5xl px-6 pt-20 pb-16 text-center lg:px-8 lg:pt-32">
        <div
            class="border-border bg-card text-muted-foreground inline-flex items-center gap-2 rounded-full border px-3.5 py-1.5 text-xs font-medium"
        >
            <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
            Laravel 12 + Svelte 5 + Tailwind v4 Scaffold
        </div>

        <h1 class="text-foreground mt-8 font-sans text-4xl font-extrabold tracking-tight sm:text-6xl">
            Laravel & SvelteKit. <br />
            <span class="text-muted-foreground font-light">Done right.</span>
        </h1>

        <p class="text-muted-foreground mx-auto mt-6 max-w-2xl text-lg leading-relaxed">
            A premium, neutral, and minimalist template for full-stack developers. Beautifully pre-configured with
            Fortify auth, two-factor keys, social SSO logins, and modern tailwind-merge helper utilities.
        </p>

        <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
            <a
                href="/register"
                class="bg-primary text-primary-foreground hover:bg-primary/90 inline-flex h-11 items-center justify-center rounded-lg px-6 font-medium shadow-lg shadow-neutral-900/10 transition-all duration-200 dark:shadow-none"
            >
                Start Scaffolding
                <ArrowRight class="ml-2 size-4" />
            </a>
            <a
                href="#setup"
                class="border-border bg-card text-foreground hover:bg-accent inline-flex h-11 items-center justify-center rounded-lg border px-6 font-medium transition-all duration-200"
            >
                Setup Command
            </a>
        </div>
    </header>

    <!-- Resources Grid Section -->
    <section id="resources" class="border-border/40 mx-auto max-w-7xl border-t px-6 py-12 lg:px-8">
        <div class="mb-10 text-center md:text-left">
            <h2 class="text-2xl font-bold tracking-tight">Core Resources</h2>
            <p class="text-muted-foreground mt-2 text-sm">
                Learn about the technologies powering your application scaffolding.
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            {#each resources as res}
                <a
                    href={res.url}
                    target="_blank"
                    rel="noopener noreferrer"
                    class="group border-border bg-card relative flex flex-col justify-between rounded-xl border p-6 transition-all duration-300 hover:border-neutral-400 hover:shadow-md dark:hover:border-neutral-600"
                >
                    <div>
                        <div class="flex items-center justify-between">
                            <span
                                class="text-muted-foreground inline-flex items-center rounded-md bg-neutral-100 px-2 py-1 text-xs font-semibold dark:bg-neutral-800"
                            >
                                {res.badge}
                            </span>
                            <ExternalLink
                                class="text-muted-foreground size-4 opacity-50 transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5 group-hover:opacity-100"
                            />
                        </div>
                        <h3 class="text-foreground group-hover:text-primary mt-4 text-base font-bold transition-colors">
                            {res.title}
                        </h3>
                        <p class="text-muted-foreground mt-2 text-sm leading-normal">
                            {res.desc}
                        </p>
                    </div>
                </a>
            {/each}
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="border-border/40 mx-auto max-w-7xl border-t px-6 py-20 lg:px-8">
        <div class="mx-auto mb-16 max-w-3xl text-center">
            <h2 class="text-3xl font-extrabold tracking-tight">Batteries Included</h2>
            <p class="text-muted-foreground mt-4">
                Everything you need to ship your SaaS safely, styled with premium minimalistic aesthetics.
            </p>
        </div>

        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            {#each features as feat}
                <div class="border-border bg-card flex gap-4 rounded-xl border p-6 transition-all duration-200">
                    <div
                        class="text-foreground flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-neutral-100 dark:bg-neutral-800"
                    >
                        <feat.icon class="size-5" />
                    </div>
                    <div>
                        <h3 class="text-foreground text-base font-bold">{feat.title}</h3>
                        <p class="text-muted-foreground mt-2 text-sm leading-relaxed">{feat.desc}</p>
                    </div>
                </div>
            {/each}
        </div>
    </section>

    <!-- Quickstart / Setup Terminal Section -->
    <section id="setup" class="border-border/40 mx-auto max-w-4xl border-t px-6 py-16 lg:px-8">
        <div class="border-border bg-card rounded-2xl border p-8 shadow-sm md:p-12">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-2xl font-bold tracking-tight">Launch in Under a Minute</h2>
                <p class="text-muted-foreground mt-3">
                    Run our CLI installer locally to select your configurations and generate rules files.
                </p>

                <!-- Terminal Command Box -->
                <div
                    class="mt-8 flex items-center justify-between rounded-lg bg-neutral-950 px-5 py-4 font-mono text-sm text-neutral-200"
                >
                    <div class="flex items-center gap-2">
                        <span class="text-neutral-500 select-none">$</span>
                        <span>npm create laravelte@latest</span>
                    </div>
                    <button
                        onclick={copyCommand}
                        class="text-neutral-400 transition-colors hover:text-neutral-200"
                        title="Copy to clipboard"
                    >
                        {#if copied}
                            <Check class="size-4 text-emerald-500" />
                        {:else}
                            <Copy class="size-4" />
                        {/if}
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-border/60 text-muted-foreground border-t py-10 text-center text-xs">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <p>&copy; {new Date().getFullYear()} Laravelte. Open source software under the MIT License.</p>
        </div>
    </footer>
</div>
